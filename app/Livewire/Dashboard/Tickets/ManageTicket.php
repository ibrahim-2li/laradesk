<?php

namespace App\Livewire\Dashboard\Tickets;

use App\Models\Status;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Notifications\Ticket\NewTicketReplyFromAgentToUser;
use App\Notifications\Ticket\NewTicketReplyFromUserToUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class ManageTicket extends Component
{
    use WithFileUploads;

    public Ticket $ticket;
    public $body = '';
    public $status_id;
    public $attachments = [];

    public $statuses;

    public function mount(Ticket $ticket)
    {
        $this->ticket = $ticket;
        $this->authorizeAccess();
        
        $this->status_id = $ticket->status_id;
        $this->statuses = Status::all();
    }

    public function authorizeAccess()
    {
        $user = Auth::user();
        if (!$this->ticket->verifyUser($user)) {
             abort(403, __('You do not have permissions to manage this ticket'));
        }
    }

    public function reply()
    {
        $this->validate([
            'body' => 'required',
            'status_id' => 'required|exists:statuses,id',
        ]);

        $ticketReply = new TicketReply();
        $ticketReply->user_id = Auth::id();
        $ticketReply->ticket_id = $this->ticket->id;
        $ticketReply->body = $this->body;
        $ticketReply->save();
        
        // Attachments logic here (placeholder)

        // Update Ticket
        $this->ticket->status_id = $this->status_id;
        $this->ticket->updated_at = Carbon::now();
        $this->ticket->save();

        // Notify
        // Logic to determine if we are sending TO user or FROM user
        // If Auth user is the ticket owner, notify agents? 
        // If Auth user is agent, notify ticket owner.
        // The original logic `NewTicketReplyFromAgentToUser` assumes agent is replying.
        // We should check roles.
        
        if (Auth::user()->role_id !== 1 && Auth::id() === $this->ticket->user_id) {
             // User replying
             // $this->ticket->agent->notify(...) if agent exists
             // NewTicketReplyFromUserToUser?
        } else {
             // Agent/Admin replying
             $this->ticket->user->notify((new NewTicketReplyFromAgentToUser($this->ticket))->locale('en')); // TODO: Locale
        }

        $this->body = ''; // Reset input
        $this->attachments = [];
        
        session()->flash('success', __('Reply posted successfully.'));
    }

    public function render()
    {
        return view('livewire.dashboard.tickets.manage-ticket', [
            'replies' => $this->ticket->ticketReplies()->with('user')->oldest()->get()
        ])->layout('layouts.dashboard');
    }
}
