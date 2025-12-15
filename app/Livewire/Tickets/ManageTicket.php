<?php

namespace App\Livewire\Tickets;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class ManageTicket extends Component
{
    use WithFileUploads;

    public Ticket $ticket;
    public $replyMessage;
    public $attachments = [];

    protected $rules = [
         'replyMessage' => 'required|min:2',
         'attachments.*' => 'image|max:10240',
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
            'replies' => $this->ticket->ticketReplies()->with(['user', 'ticketAttachments'])->orderBy('created_at', 'asc')->get()
        ])->layout('layouts.dashboard');
    }

    public function postReply()
    {
        $this->validate();

        $reply = $this->ticket->ticketReplies()->create([
            'user_id' => Auth::id(),
            'body' => $this->replyMessage,
        ]);

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

             $reply->ticketAttachments()->attach($file->id);
        }

        // You might want to update ticket status here
        // $this->ticket->update(['status_id' => ...]);

        $this->replyMessage = '';
        $this->attachments = [];
        session()->flash('success', 'Reply posted successfully.');
    }
}
