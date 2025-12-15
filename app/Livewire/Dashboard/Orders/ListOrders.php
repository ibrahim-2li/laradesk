<?php

namespace App\Livewire\Dashboard\Orders;

use App\Models\Branch;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ListOrders extends Component
{
    use WithPagination;

    public $search = '';
    public $sortColumn = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'sortColumn' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
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

        $query = Order::query();

        // Search
        if ($this->search) {
             $query->where(function ($q) {
                $q->where('subject', 'like', '%' . $this->search . '%')
                  ->orWhere('uuid', 'like', '%' . $this->search . '%');
            });
        }

        // Access Control (migrated from OrderController::index)
        // Original controller checked ($user->role_id === 2 & 3) which likely meant role != 1 (Admin)
        if ($user->role_id !== 1) { 
            $query->where(function (Builder $q) use ($user) {
                $q->where('agent_id', $user->id);
                $q->orWhere('closed_by', $user->id);
                
                // Using 'branch' relationship as defined in User model (not 'branchese')
                $branchIds = $user->branch()->pluck('branches.id')->toArray();
                $q->orWhereIn('branches_id', $branchIds);
                
                $q->orWhere(function (Builder $q2) use ($user, $branchIds) {
                    $publicBranches = Branch::where('all_agents', 1)->pluck('id')->toArray();
                    $allAllowedBranches = array_unique(array_merge($branchIds, $publicBranches));
                    
                    $q2->whereNull('agent_id');
                    $q2->whereIn('branches_id', $allAllowedBranches);
                });
            });
        }

        $orders = $query->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate(10);

        return view('livewire.dashboard.orders.list-orders', [
            'orders' => $orders
        ])->layout('layouts.dashboard');
    }
}
