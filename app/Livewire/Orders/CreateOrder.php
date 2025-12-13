<?php

namespace App\Livewire\Orders;

use App\Models\Branch;
use App\Models\Order;
use App\Models\Priority;
use App\Models\OrderStatus; // Assuming OrderStatus model exists or implicit
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateOrder extends Component
{
    public $subject;
    public $branches_id;
    public $priority_id;
    public $message;

    protected $rules = [
        'subject' => 'required|min:3|max:255',
        'branches_id' => 'required|exists:branches,id',
        'priority_id' => 'required|exists:priorities,id',
        'message' => 'required|min:10',
    ];

    public function mount()
    {
        $defaultPriority = Priority::where('name', 'Normal')->first();
        if ($defaultPriority) {
            $this->priority_id = $defaultPriority->id;
        }
    }

    public function render()
    {
        return view('livewire.orders.create-order', [
            'branches' => Branch::where('public', 1)->get(),
            'priorities' => Priority::all(),
        ])->layout('layouts.dashboard');
    }

    public function store()
    {
        $validatedData = $this->validate();

        DB::transaction(function () use ($validatedData) {
            // Create Order
            $order = Order::create([
                'uuid' => (string) \Illuminate\Support\Str::uuid(),
                'subject' => $validatedData['subject'],
                'branches_id' => $validatedData['branches_id'],
                'priority_id' => $validatedData['priority_id'],
                'user_id' => Auth::id(),
                'orders_status_id' => 1, // Default status: Open/Pending?
            ]);

            // Create Initial Reply
            $order->orderReplies()->create([
                'user_id' => Auth::id(),
                'body' => $validatedData['message'],
            ]);

            session()->flash('success', 'Order created successfully.');
            return redirect()->route('orders.list');
        });
    }
}
