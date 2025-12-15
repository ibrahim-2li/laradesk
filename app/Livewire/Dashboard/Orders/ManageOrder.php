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
        $this->order->load(['items.stock.brands', 'orderStatus', 'priority', 'branches', 'agent', 'user']);
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
            'attachments.*' => 'image|max:10240',
        ]);

        $orderReply = new OrderReply();
        $orderReply->user_id = Auth::id();
        $orderReply->order_id = $this->order->id;
        $orderReply->body = $this->body;
        $orderReply->save();
        
        foreach ($this->attachments as $attachment) {
             $disk = 'public';
             $directory = 'order-attachments';
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

             $orderReply->orderAttachments()->attach($file->id);
        }
        
        // Update Order
        $this->order->orders_status_id = $this->orders_status_id;
        $this->order->updated_at = Carbon::now();
        // If closing?
        // $this->order->closed_by = ...
        $this->order->save();

        try {
            if (Auth::user()->role_id !== 1 && Auth::id() === $this->order->user_id) {
                 // User replying
            } else {
                 // Agent/Admin replying
                 $this->order->user->notify((new NewOrderReplyFromAgentToUser($this->order))->locale('en')); 
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send order reply notification: ' . $e->getMessage());
        }

        $this->body = ''; 
        $this->attachments = [];
        
        session()->flash('success', __('Reply posted successfully.'));
    }

    public function render()
    {
        return view('livewire.dashboard.orders.manage-order', [
            'replies' => $this->order->orderReplies()->with(['user', 'orderAttachments'])->oldest()->get()
        ])->layout('layouts.dashboard');
    }
}
