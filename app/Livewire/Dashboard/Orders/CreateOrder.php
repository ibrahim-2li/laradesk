<?php

namespace App\Livewire\Dashboard\Orders;

use App\Models\Branch;
use App\Models\OrderStatus;
use App\Models\Priority;
use App\Models\Order;
use App\Models\Stock; // Import Stock
use App\Models\OrderItem; // Import OrderItem
use App\Models\OrderReply;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateOrder extends Component
{
    use WithFileUploads;

    public $subject = '';
    public $body = ''; 
    public $user_id; 
    public $branches_id;
    public $orders_status_id;
    public $priority_id;
    public $agent_id;
    public $attachments = [];

    // Order Items
    public $items = []; // [['stock_id' => '', 'quantity' => 1]]
    
    // Dropdown options
    public $customers;
    public $availableStocks;
    public $branches;
    public $statuses;
    public $priorities;
    public $agents;

    public function mount()
    {
        $this->loadOptions();
        
        // Set defaults
        $this->orders_status_id = OrderStatus::where('name', 'Open')->first()->id ?? OrderStatus::first()->id ?? null;
        $this->priority_id = Priority::where('value', 1)->first()->id ?? Priority::first()->id ?? null;
    }

    public function loadOptions()
    {
        $this->customers = User::all(); 
        $this->branches = Branch::all();
        $this->statuses = OrderStatus::all();
        $this->priorities = Priority::orderBy('value')->get();
        
        $this->agents = User::whereHas('userRole', function($q) {
            $q->where('dashboard_access', true);
        })->get();

        $this->availableStocks = Stock::with('brands')->get();
    }

    public function addItem()
    {
        $this->items[] = ['stock_id' => '', 'quantity' => 1];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function save()
    {
        $this->validate([
            'subject' => 'required|max:255',
            'body' => 'required',
            'user_id' => 'required|exists:users,id',
            'branches_id' => 'required|exists:branches,id',
            'orders_status_id' => 'required|exists:order_statuses,id',
            'priority_id' => 'required|exists:priorities,id',
            'agent_id' => 'nullable|exists:users,id',
            'attachments.*' => 'image|max:10240',
            'items.*.stock_id' => 'required|exists:stocks,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $order = new Order();
        $order->uuid = Str::uuid();
        $order->subject = $this->subject;
        $order->orders_status_id = $this->orders_status_id;
        $order->priority_id = $this->priority_id;
        $order->branches_id = $this->branches_id;
        $order->user_id = $this->user_id;
        $order->agent_id = $this->agent_id;
        $order->save();

        $orderReply = new OrderReply();
        $orderReply->user_id = Auth::id();
        $orderReply->order_id = $order->id;
        $orderReply->body = $this->body;
        $orderReply->save();

        // Attachments placeholder
        /*
        if ($this->attachments) {
            // Store attachments
        }
        */

        // Save Items
        foreach ($this->items as $item) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->stock_id = $item['stock_id'];
            $orderItem->quantity = $item['quantity'];
            $orderItem->save();
        }

        session()->flash('success', __('Order created successfully.'));
        return redirect()->route('orders.list');
    }

    public function render()
    {
        return view('livewire.dashboard.orders.create-order')->layout('layouts.dashboard');
    }
}
