<?php

namespace App\Livewire\Dashboard\Tickets;

use App\Models\Department;
use App\Models\Ticket;
use App\Models\Status;
use App\Models\Priority;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ListTickets extends Component
{
    use WithPagination;

    public $search = '';
    public $sortColumn = 'created_at';
    public $sortDirection = 'desc';

    public $status_id = '';
    public $priority_id = '';
    public $department_id = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'sortColumn' => ['except' => 'created_at'],
        'sortColumn' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'status_id' => ['except' => ''],
        'priority_id' => ['except' => ''],
        'department_id' => ['except' => ''],
    ];

    public function sortBy($column)
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortColumn = $column;
    }

    public function render()
    {
        $user = Auth::user();

        $query = Ticket::query();
        
        // Search
        if ($this->search) {
             $query->where(function ($q) {
                $q->where('subject', 'like', '%' . $this->search . '%')
                  ->orWhere('uuid', 'like', '%' . $this->search . '%');
            });
        }

        // Filters
        if ($this->status_id) {
            $query->where('status_id', $this->status_id);
        }
        if ($this->priority_id) {
            $query->where('priority_id', $this->priority_id);
        }
        if ($this->department_id) {
            $query->where('department_id', $this->department_id);
        }

        // Access Control (migrated from TicketController::index)
        if ($user->role_id != 1) { // 1 = Admin
            $query->where(function (Builder $q) use ($user) {
                $q->where('agent_id', $user->id);
                $q->orWhere('closed_by', $user->id);
                $q->orWhereIn('department_id', $user->departments()->pluck('id')->toArray());
                $q->orWhere(function (Builder $q2) use ($user) {
                    $departments = array_unique(array_merge(
                        $user->departments()->pluck('id')->toArray(), 
                        Department::where('all_agents', 1)->pluck('id')->toArray()
                    ));
                    $q2->whereNull('agent_id');
                    $q2->whereIn('department_id', $departments);
                });
            });
        }

        $tickets = $query->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate(10);

        return view('livewire.dashboard.tickets.list-tickets', [
            'tickets' => $tickets,
            'statuses' => Status::all(),
            'priorities' => Priority::all(),
            'departments' => Department::all(),
        ])->layout('layouts.dashboard');
    }
}
