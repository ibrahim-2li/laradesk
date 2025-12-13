<?php

namespace App\Livewire\Dashboard\Admin\UserRoles;

use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ListUserRoles extends Component
{
    use WithPagination;

    public $search = '';
    
    // Modal State
    public $isModalOpen = false;
    public $editingId = null;
    
    // Form Fields
    public $name = '';
    public $dashboard_access = false;
    public $permissions = [];

    const AVAILABLE_PERMISSIONS = [
        'tickets' => 'Tickets',
        'orders' => 'Orders',
        'canned_replies' => 'Canned Replies',
        'users' => 'Users',
        'roles' => 'User Roles',
        'departments' => 'Departments',
        'branches' => 'Branches',
        'labels' => 'Labels',
        'statuses' => 'Statuses',
        'priorities' => 'Priorities',
        'languages' => 'Languages',
        'stock' => 'Stock',
        'brands' => 'Brands',
        'settings' => 'Settings',
    ];

    protected function rules() 
    {
        return [
            'name' => 'required|min:2|unique:user_roles,name,' . $this->editingId,
            'dashboard_access' => 'boolean',
            'permissions' => 'array',
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
        $query = UserRole::query()->withCount('users');

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $roles = $query->orderBy('id', 'asc')->paginate(10);

        return view('livewire.dashboard.admin.user-roles.list-user-roles', [
            'roles' => $roles
        ])->layout('layouts.dashboard');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $role = UserRole::findOrFail($id);
        
        $this->editingId = $id;
        $this->name = $role->name;
        $this->dashboard_access = (bool) $role->dashboard_access;
        $this->permissions = $role->permissions ?? [];
        
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'dashboard_access' => $this->dashboard_access,
            'permissions' => $this->permissions,
        ];

        if (!$this->editingId) {
             $data['type'] = 0;
        }

        UserRole::updateOrCreate(['id' => $this->editingId], $data);

        session()->flash('success', $this->editingId ? 'Role updated successfully.' : 'Role created successfully.');
        
        $this->closeModal();
        $this->resetInputFields();
    }

    public function delete($id)
    {
        $role = UserRole::findOrFail($id);
        
        if ($role->id === 1) {
            session()->flash('error', __('You cannot delete the Super Admin role.'));
            return;
        }

        if ($role->users()->count() > 0) {
            session()->flash('error', __('Cannot delete role because it has assigned users.'));
            return;
        }

        $role->delete();
        session()->flash('success', 'Role deleted successfully.');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->dashboard_access = false;
        $this->permissions = [];
        $this->editingId = null;
    }
}
