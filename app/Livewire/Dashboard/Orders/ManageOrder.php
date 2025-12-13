<?php

namespace App\Livewire\Dashboard\Orders;

use App\Models\OrderStatus;
use App\Models\Order;
use App\Models\OrderReply;
use App\Models\Stock;
use App\Models\OrderItem;
use App\Notifications\Order\NewOrderReplyFromAgentToUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class ManageOrder extends Component
{
    use WithFileUploads;

    public Order $order;
    public $body = '';
    public $orders_status_id;
    public $attachments = [];
    
    // Order Items (Stocks)
    // We can add a simple interface to add stocks if needed
    
    public $statuses;

    public function mount(Order $order)
    {
        $this->order = $order;
        $this->authorizeAccess();
        
        $this->orders_status_id = $order->orders_status_id;
        $this->statuses = OrderStatus::all();
    }

    public function authorizeAccess()
    {
        $user = Auth::user();
        if (!$this->order->verifyUser($user)) {
             abort(403, __('You do not have permissions to manage this order'));
        }
    }

    public function reply()
    {
        $this->validate([
            'body' => 'required',
            'orders_status_id' => 'required|exists:order_statuses,id',
        ]);

        $orderReply = new OrderReply();
        $orderReply->user_id = Auth::id();
        $orderReply->order_id = $this->order->id;
        $orderReply->body = $this->body;
        $orderReply->save();
        
        // Update Order
        $this->order->orders_status_id = $this->orders_status_id;
        $this->order->updated_at = Carbon::now();
        // If closing?
        // $this->order->closed_by = ...
        $this->order->save();

        if (Auth::user()->role_id !== 1 && Auth::id() === $this->order->user_id) {
             // User replying
        } else {
             // Agent/Admin replying
             $this->order->user->notify((new NewOrderReplyFromAgentToUser($this->order))->locale('en')); 
        }

        $this->body = ''; 
        $this->attachments = [];
        
        session()->flash('success', __('Reply posted successfully.'));
    }

    public function render()
    {
        return view('livewire.dashboard.orders.manage-order', [
            'replies' => $this->order->orderReplies()->with('user')->oldest()->get()
        ])->layout('layouts.dashboard');
    }
}
