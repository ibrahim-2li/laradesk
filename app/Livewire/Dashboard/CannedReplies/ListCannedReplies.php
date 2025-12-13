<?php

namespace App\Livewire\Dashboard\CannedReplies;

use App\Models\CannedReply;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ListCannedReplies extends Component
{
    use WithPagination;

    public $search = '';
    
    // Minimal Create/Edit Modal State
    public $isModalOpen = false;
    public $editingId = null;
    public $name = '';
    public $body = '';
    public $shared = false;

    protected $rules = [
        'name' => 'required|max:255',
        'body' => 'required',
        'shared' => 'boolean',
    ];

    public function render()
    {
        $query = CannedReply::query();
        
        // Filter logic from CannedReplyController::index
        $query->where(function ($builder) {
            $builder->where('shared', true)
                    ->orWhere('user_id', Auth::id());
        });

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $cannedReplies = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.dashboard.canned-replies.list-canned-replies', [
            'cannedReplies' => $cannedReplies
        ])->layout('layouts.dashboard');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $reply = CannedReply::findOrFail($id);
        
        if ($reply->user_id !== Auth::id()) {
            session()->flash('error', __('You can only edit your own canned replies.'));
            return;
        }

        $this->editingId = $id;
        $this->name = $reply->name;
        $this->body = $reply->body;
        $this->shared = (bool) $reply->shared;
        
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate();

        CannedReply::updateOrCreate(['id' => $this->editingId], [
            'name' => $this->name,
            'body' => $this->body,
            'shared' => $this->shared ? 1 : 0, // Ensure boolean/integer compatibility
            'user_id' => Auth::id(), // If editing, this might overwrite owner if we weren't careful, but we only allow editing own.
        ]);

        session()->flash('success', $this->editingId ? 'Canned Reply updated successfully.' : 'Canned Reply created successfully.');
        
        $this->closeModal();
        $this->resetInputFields();
    }

    public function delete($id)
    {
        $reply = CannedReply::findOrFail($id);
        if ($reply->user_id !== Auth::id()) {
            session()->flash('error', __('You can only delete your own canned replies.'));
            return;
        }
        $reply->delete();
        session()->flash('success', 'Canned Reply deleted successfully.');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->body = '';
        $this->shared = false;
        $this->editingId = null;
    }
}
