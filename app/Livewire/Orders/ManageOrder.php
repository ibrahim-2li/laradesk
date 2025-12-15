<?php

namespace App\Livewire\Orders;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ManageOrder extends Component
{
    public Order $order;
    public $replyMessage;

    protected $rules = [
         'replyMessage' => 'required|min:2',
    ];

    public function mount(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        $this->order = $order->load(['orderStatus', 'priority', 'branches']);
    }

    public function render()
    {
        return view('livewire.orders.manage-order', [
            'replies' => $this->order->orderReplies()->with('user')->orderBy('created_at', 'asc')->get()
        ])->layout('layouts.dashboard');
    }

    public function postReply()
    {
        $this->validate();

        $this->order->orderReplies()->create([
            'user_id' => Auth::id(),
            'body' => $this->replyMessage,
        ]);

        $this->replyMessage = '';
        session()->flash('success', __('Reply posted successfully.'));
    }
}
