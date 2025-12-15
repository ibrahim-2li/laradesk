<?php

namespace App\Livewire\Orders;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class ManageOrder extends Component
{
    use WithFileUploads;

    public Order $order;
    public $replyMessage;
    public $attachments = [];

    protected $rules = [
         'replyMessage' => 'required|min:2',
         'attachments.*' => 'image|max:10240',
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
            'replies' => $this->order->orderReplies()->with(['user', 'orderAttachments'])->orderBy('created_at', 'asc')->get()
        ])->layout('layouts.dashboard');
    }

    public function postReply()
    {
        $this->validate();

        $reply = $this->order->orderReplies()->create([
            'user_id' => Auth::id(),
            'body' => $this->replyMessage,
        ]);

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

             $reply->orderAttachments()->attach($file->id);
        }

        $this->replyMessage = '';
        $this->attachments = [];
        session()->flash('success', __('Reply posted successfully.'));
    }
}
