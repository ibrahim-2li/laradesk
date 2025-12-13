<?php

namespace App\Livewire\Dashboard\Admin\Users;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class ListUsers extends Component
{
    use WithPagination;

    public $search = '';
    public $roleFilter = '';
    
    // Modal State
    public $isModalOpen = false;
    public $editingId = null;
    
    // Form Fields
    public $name = '';
    public $email = '';
    public $role_id = '';
    public $status = 1;
    public $password = '';
    
    // Dropdown Data
    public $roles = [];

    protected function rules() 
    {
        $rules = [
            'name' => 'required|min:3',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->editingId)],
            'role_id' => 'required|exists:user_roles,id',
            'status' => 'boolean',
        ];

        if (!$this->editingId) {
            $rules['password'] = 'required|min:6';
        } else {
             $rules['password'] = 'nullable|min:6';
        }

        return $rules;
    }

    public function mount()
    {
        if (Auth::user()->role_id !== 1) {
            abort(403, __('Unauthorized'));
        }
        $this->roles = UserRole::all();
    }

    public function render()
    {
        $query = User::query()->with('userRole');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->roleFilter) {
            $query->where('role_id', $this->roleFilter);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.dashboard.admin.users.list-users', [
            'users' => $users
        ])->layout('layouts.dashboard');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === Auth::id()) {
            session()->flash('error', __('You cannot edit your own user from here. Go to your profile.'));
            return;
        }

        $this->editingId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role_id = $user->role_id;
        $this->status = $user->status;
        $this->password = ''; // Don't show password
        
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role_id' => $this->role_id,
            'status' => $this->status,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        User::updateOrCreate(['id' => $this->editingId], $data);

        session()->flash('success', $this->editingId ? 'User updated successfully.' : 'User created successfully.');
        
        $this->closeModal();
        $this->resetInputFields();
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === Auth::id()) {
            session()->flash('error', __('You cannot delete your own user.'));
            return;
        }

        $user->delete();
        session()->flash('success', 'User deleted successfully.');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->email = '';
        $this->role_id = ''; // Default or empty
        $this->status = 1;
        $this->password = '';
        $this->editingId = null;
    }
}
