<?php

namespace App\Livewire\Dashboard\Admin\Departments;

use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class ListDepartments extends Component
{
    use WithPagination;

    public $search = '';
    
    // Modal State
    public $isModalOpen = false;
    public $editingId = null;
    
    // Form Fields
    public $name = '';
    public $all_agents = true; // Default to true
    public $public = true;     // Default to true

    protected function rules() 
    {
        return [
            'name' => 'required|min:2|unique:departments,name,' . $this->editingId,
            'all_agents' => 'boolean',
            'public' => 'boolean',
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
        $query = Department::query()->withCount('agent');

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $departments = $query->orderBy('name', 'asc')->paginate(10);

        return view('livewire.dashboard.admin.departments.list-departments', [
            'departments' => $departments
        ])->layout('layouts.dashboard');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $department = Department::findOrFail($id);
        
        $this->editingId = $id;
        $this->name = $department->name;
        $this->all_agents = (bool) $department->all_agents;
        $this->public = (bool) $department->public;
        
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'all_agents' => $this->all_agents,
            'public' => $this->public,
        ];

        Department::updateOrCreate(['id' => $this->editingId], $data);

        session()->flash('success', $this->editingId ? 'Department updated successfully.' : 'Department created successfully.');
        
        $this->closeModal();
        $this->resetInputFields();
    }

    public function delete($id)
    {
        $department = Department::findOrFail($id);
        
        // Add check if department has tickets? 
        // For now, allow delete.
        $department->delete();
        session()->flash('success', 'Department deleted successfully.');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->all_agents = true;
        $this->public = true;
        $this->editingId = null;
    }
}
