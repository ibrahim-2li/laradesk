<?php

namespace App\Livewire\Tickets;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ListTickets extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $query = Ticket::query()
            ->where('user_id', Auth::id())
            ->with(['status', 'priority', 'department']);

        if ($this->search) {
            $query->where('subject', 'like', '%' . $this->search . '%')
                  ->orWhere('id', 'like', '%' . $this->search . '%');
        }

        $tickets = $query->orderBy('updated_at', 'desc')->paginate(10);

        return view('livewire.tickets.list-tickets', [
            'tickets' => $tickets
        ])->layout('layouts.dashboard');
    }
}
