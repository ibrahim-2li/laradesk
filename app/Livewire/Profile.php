<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class Profile extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $avatar; // For upload
    public $existingAvatar; // For display
    public $password;
    public $password_confirmation;

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->existingAvatar = $user->getAvatar();
    }

    public function save()
    {
        $user = auth()->user();

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'avatar' => 'nullable|image|max:2048', // 2MB max
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $this->name;
        $user->email = $this->email;

        if ($this->avatar) {
            $user->avatar = $this->avatar->store('avatars', 'public');
            $this->existingAvatar = $user->getAvatar(); // Update preview
        }

        if ($this->password) {
            $user->password = Hash::make($this->password);
        }

        $user->save();

        // Clear sensitive fields
        $this->password = '';
        $this->password_confirmation = '';
        $this->avatar = null;

        session()->flash('success', __('Profile updated successfully.'));
    }

    public function render()
    {
        return view('livewire.profile')->layout('layouts.dashboard');
    }
}
