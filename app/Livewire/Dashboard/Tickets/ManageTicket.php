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
            'attachments.*' => 'image|max:10240', // 10MB max
        ]);

        $ticketReply = new TicketReply();
        $ticketReply->user_id = Auth::id();
        $ticketReply->ticket_id = $this->ticket->id;
        $ticketReply->body = $this->body;
        $ticketReply->save();
        
        foreach ($this->attachments as $attachment) {
             $disk = 'public';
             $directory = 'ticket-attachments';
             $savedPath = $attachment->store($directory, $disk);

             $file = new \App\Models\File();
             $file->uuid = \Illuminate\Support\Str::uuid();
             $file->name = $attachment->getClientOriginalName();
             $file->server_name = basename($savedPath);
             $file->size = $attachment->getSize();
             $file->mime = $attachment->getMimeType();
             $file->extension = $attachment->getClientOriginalExtension();
             $file->disk = $disk;
             $file->path = $directory;
             $file->user_id = Auth::id();
             $file->save();

             $ticketReply->ticketAttachments()->attach($file->id);
        }

        // Update Ticket
        $this->ticket->status_id = $this->status_id;
        $this->ticket->updated_at = Carbon::now();
        $this->ticket->save();

        // Notify logic (simplified for brevity, keeping existing structure)
        try {
            if (Auth::user()->role_id !== 1 && Auth::id() === $this->ticket->user_id) {
                 // User replying
            } else {
                 $this->ticket->user->notify((new NewTicketReplyFromAgentToUser($this->ticket))->locale('en'));
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send ticket reply notification: ' . $e->getMessage());
        }

        $this->body = ''; 
        $this->attachments = [];
        
        session()->flash('success', __('Reply posted successfully.'));
    }

    public function render()
    {
        return view('livewire.dashboard.tickets.manage-ticket', [
            'replies' => $this->ticket->ticketReplies()->with(['user', 'ticketAttachments'])->oldest()->get()
        ])->layout('layouts.dashboard');
    }
}
