<?php

namespace App\Livewire\Dashboard\Admin\Priorities;

use App\Models\Priority;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ListPriorities extends Component
{
    use WithPagination;

    public $search = '';
    
    // Modal State
    public $isModalOpen = false;
    public $editingId = null;
    
    // Form Fields
    public $name = '';
    public $value = 1;

    protected function rules() 
    {
        return [
            'name' => 'required|min:2|unique:priorities,name,' . $this->editingId,
            'value' => 'required|integer',
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
        $query = Priority::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $priorities = $query->orderBy('value', 'desc')->paginate(10);

        return view('livewire.dashboard.admin.priorities.list-priorities', [
            'priorities' => $priorities
        ])->layout('layouts.dashboard');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $priority = Priority::findOrFail($id);
        
        $this->editingId = $id;
        $this->name = $priority->name;
        $this->value = $priority->value;
        
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'value' => $this->value,
        ];

        Priority::updateOrCreate(['id' => $this->editingId], $data);

        session()->flash('success', $this->editingId ? 'Priority updated successfully.' : 'Priority created successfully.');
        
        $this->closeModal();
        $this->resetInputFields();
    }

    public function delete($id)
    {
        $priority = Priority::findOrFail($id);
        $priority->delete();
        session()->flash('success', 'Priority deleted successfully.');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->value = 1;
        $this->editingId = null;
    }
}
