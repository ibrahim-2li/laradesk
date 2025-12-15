<?php

namespace App\Livewire\Dashboard\Admin\Branches;

use App\Models\Branch;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ListBranches extends Component
{
    use WithPagination;

    public $search = '';
    
    // Modal State
    public $isModalOpen = false;
    public $editingId = null;
    
    // Form Fields
    public $name = '';
    public $all_agents = true;
    public $public = true;

    protected function rules() 
    {
        return [
            'name' => 'required|min:2|unique:branches,name,' . $this->editingId,
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
        $query = Branch::query()->with(['users' => function($q) {
            $q->limit(5);
        }])->withCount('users');

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $branches = $query->orderBy('name', 'asc')->paginate(10);

        return view('livewire.dashboard.admin.branches.list-branches', [
            'branches' => $branches
        ])->layout('layouts.dashboard');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $branch = Branch::findOrFail($id);
        
        $this->editingId = $id;
        $this->name = $branch->name;
        $this->all_agents = (bool) $branch->all_agents;
        $this->public = (bool) $branch->public;
        
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

        Branch::updateOrCreate(['id' => $this->editingId], $data);

        session()->flash('success', $this->editingId ? 'Branch updated successfully.' : 'Branch created successfully.');
        
        $this->closeModal();
        $this->resetInputFields();
    }

    public function delete($id)
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();
        session()->flash('success', 'Branch deleted successfully.');
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
