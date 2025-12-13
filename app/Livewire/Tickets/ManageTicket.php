<?php

namespace App\Livewire\Tickets;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ManageTicket extends Component
{
    public Ticket $ticket;
    public $replyMessage;

    protected $rules = [
         'replyMessage' => 'required|min:2',
    ];

    public function mount(Ticket $ticket)
    {
        // Authorization: Check if the ticket belongs to the user
        if ($ticket->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this ticket.');
        }

        $this->ticket = $ticket;
    }

    public function render()
    {
        return view('livewire.tickets.manage-ticket', [
            'replies' => $this->ticket->ticketReplies()->with('user')->orderBy('created_at', 'asc')->get()
        ])->layout('layouts.dashboard');
    }

    public function postReply()
    {
        $this->validate();

        $this->ticket->ticketReplies()->create([
            'user_id' => Auth::id(),
            'body' => $this->replyMessage,
        ]);

        // You might want to update ticket status here
        // $this->ticket->update(['status_id' => ...]);

        $this->replyMessage = '';
        session()->flash('success', 'Reply posted successfully.');
    }
}
