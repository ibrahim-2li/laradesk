<?php

namespace App\Livewire\Dashboard\Tickets;

use App\Models\Department;
use App\Models\Priority;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateTicket extends Component
{
    use WithFileUploads;

    public $subject = '';
    public $body = ''; // For the initial reply/description
    public $user_id; // Customer
    public $department_id;
    public $status_id;
    public $priority_id;
    public $agent_id;
    public $attachments = [];

    // Dropdown options
    public $customers;
    public $departments;
    public $statuses;
    public $priorities;
    public $agents;

    public function mount()
    {
        $this->loadOptions();
        
        // Set defaults if available
        $this->status_id = Status::where('name', 'Open')->first()->id ?? Status::first()->id ?? null;
        $this->priority_id = Priority::where('value', 1)->first()->id ?? Priority::first()->id ?? null;
    }

    public function loadOptions()
    {
        // Simple loading for now. In a real app with many users, customers should be loaded via search/select2.
        // For migration parity, we'll just load them all as the Vue app seemed to filters() which returned all.
        $this->customers = User::all(); 
        $this->departments = Department::all();
        $this->statuses = Status::all();
        $this->priorities = Priority::orderBy('value')->get();
        // Agents: Users with dashboard access? Or specific role?
        // TicketController::filters logic: UserRole::where('dashboard_access', true)
        // We can just load all users for now or refine if needed.
        // Let's stick to the controller logic approximately.
        $this->agents = User::whereHas('userRole', function($q) {
            $q->where('dashboard_access', true);
        })->get();
    }

    public function save()
    {
        $this->validate([
            'subject' => 'required|max:255',
            'body' => 'required',
            'user_id' => 'required|exists:users,id',
            'department_id' => 'required|exists:departments,id',
            'status_id' => 'required|exists:statuses,id',
            'priority_id' => 'required|exists:priorities,id',
            'agent_id' => 'nullable|exists:users,id',
            'attachments.*' => 'image|max:10240', // 10MB max, modify as needed
        ]);

        $ticket = new Ticket();
        $ticket->uuid = Str::uuid();
        $ticket->subject = $this->subject;
        $ticket->status_id = $this->status_id;
        $ticket->priority_id = $this->priority_id;
        $ticket->department_id = $this->department_id;
        $ticket->user_id = $this->user_id;
        $ticket->agent_id = $this->agent_id;
        $ticket->save();

        $ticketReply = new TicketReply();
        $ticketReply->user_id = Auth::id(); // Created by the logged in user (agent/admin)
        $ticketReply->ticket_id = $ticket->id;
        $ticketReply->body = $this->body;
        $ticketReply->save();

        // Handle Attachments (TODO: Implement proper file storage logic matching the system)
        /*
        if ($this->attachments) {
            foreach ($this->attachments as $photo) {
                $path = $photo->store('tickets');
                // Create File model to link?
            }
        }
        */

        session()->flash('success', __('Ticket created successfully.'));
        return redirect()->route('tickets.list');
    }

    public function render()
    {
        return view('livewire.dashboard.tickets.create-ticket')->layout('layouts.dashboard');
    }
}
