<?php

namespace App\Livewire\Dashboard\Admin\Statuses;

use App\Models\Status;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ListStatuses extends Component
{
    use WithPagination;

    public $search = '';
    
    // Modal State
    public $isModalOpen = false;
    public $editingId = null;
    
    // Form Fields
    public $name = '';

    protected function rules() 
    {
        return [
            'name' => 'required|min:2|unique:statuses,name,' . $this->editingId,
        ];
    }

    public function mount()
    {
        if (Auth::user()->role_id !== 1) {
            abort(403, __('Unauthorized'));
        }
    }

    public function render()
    {
        $query = Status::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $statuses = $query->orderBy('name', 'asc')->paginate(10);

        return view('livewire.dashboard.admin.statuses.list-statuses', [
            'statuses' => $statuses
        ])->layout('layouts.dashboard');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $status = Status::findOrFail($id);
        
        $this->editingId = $id;
        $this->name = $status->name;
        
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
        ];

        Status::updateOrCreate(['id' => $this->editingId], $data);

        session()->flash('success', $this->editingId ? 'Status updated successfully.' : 'Status created successfully.');
        
        $this->closeModal();
        $this->resetInputFields();
    }

    public function delete($id)
    {
        $status = Status::findOrFail($id);
        $status->delete();
        session()->flash('success', 'Status deleted successfully.');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->editingId = null;
    }
}
