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
    public $selectedAgents = [];
    public $availableAgents = [];

    protected function rules() 
    {
        return [
            'name' => 'required|min:2|unique:departments,name,' . $this->editingId,
            'all_agents' => 'boolean',
            'public' => 'boolean',
            'selectedAgents' => 'array',
            'selectedAgents.*' => 'exists:users,id',
        ];
    }

    public function mount()
    {
        if (Auth::user()->role_id !== 1) {
            abort(403, __('Unauthorized'));
        }

        $this->availableAgents = \App\Models\User::whereHas('userRole', function($q) {
            $q->where('dashboard_access', true);
        })->get();
    }

    public function render()
    {
        $query = Department::query()->with(['agent' => function($q) {
            $q->limit(5);
        }])->withCount('agent');

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
        
        // Load assigned agents into selectedAgents array
        $this->selectedAgents = $department->agent()->pluck('users.id')->toArray();
        
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

        $department = Department::updateOrCreate(['id' => $this->editingId], $data);

        // Sync agents if not all_agents
        if (!$this->all_agents) {
            $department->agent()->sync($this->selectedAgents);
        } else {
            // If all_agents is true, we might want to detach all specific assignments
            // or keep them? Usually "all agents" implies dynamic access, but cleaning up is safer.
            $department->agent()->detach();
        }

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
        $this->selectedAgents = [];
    }
}
