<?php

namespace App\Livewire\Tickets;

use App\Models\Category;
use App\Models\Department;
use App\Models\Priority;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateTicket extends Component
{
    public $subject;
    public $department_id;
    public $priority_id;
    public $message;

    protected $rules = [
        'subject' => 'required|min:3|max:255',
        'department_id' => 'required|exists:departments,id',
        'priority_id' => 'required|exists:priorities,id',
        'message' => 'required|min:10',
    ];

    public function mount()
    {
        // Set default priority if exists, e.g., 'Normal' or 'Low'
        $defaultPriority = Priority::where('name', 'Normal')->first();
        if ($defaultPriority) {
            $this->priority_id = $defaultPriority->id;
        }
    }

    public function render()
    {
        return view('livewire.tickets.create-ticket', [
            'departments' => Department::where('public', 1)->get(),
            'priorities' => Priority::all(),
        ])->layout('layouts.dashboard');
    }

    public function store()
    {
        $validatedData = $this->validate();

        DB::transaction(function () use ($validatedData) {
            // Create Ticket
            $ticket = Ticket::create([
                'uuid' => (string) \Illuminate\Support\Str::uuid(),
                'subject' => $validatedData['subject'],
                'department_id' => $validatedData['department_id'],
                'priority_id' => $validatedData['priority_id'],
                'user_id' => Auth::id(),
                'status_id' => 1, // Default status: Open? Need to check Status seeder/model
            ]);

            // Create Initial Reply (The message)
            $ticket->ticketReplies()->create([
                'user_id' => Auth::id(),
                'body' => $validatedData['message'],
            ]);

            session()->flash('success', 'Ticket created successfully.');
            return redirect()->route('tickets.list');
        });
    }
}
