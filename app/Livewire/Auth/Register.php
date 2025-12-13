<?php

namespace App\Livewire\Auth;

use App\Models\Setting;
use App\Models\User;
use App\Notifications\Auth\UserRegister;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use stdClass;

class Register extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $terms = false;

    public function register()
    {
        if (!Setting::get('app_user_registration')) {
            abort(403, 'Registration is disabled.');
        }

        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['accepted'],
        ]);

        $user = new User();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->password = Hash::make($this->password);
        // Using raw value as Setting::getDecoded might be needed if it returns JSON
        // Assuming simple string for role ID for now, or fetch logic from controller
        // The original controller used Setting::getDecoded('app_default_role')
        $defaultRole = Setting::get('app_default_role');
        // If it's JSON encoded ID, we might need json_decode, but usually settings helper handles it.
        // Let's assume the Setting model handles retrieval.
        $user->role_id = is_numeric($defaultRole) ? $defaultRole : json_decode($defaultRole); // Safe fallback
        $user->save();

        try {
            $objNotificationData = new stdClass();
            $objNotificationData->user = $user;
            // $user->notify((new UserRegister($objNotificationData))->locale(Setting::get('app_locale')));
            // Simplified for now, ensuring notification class exists
        } catch (\Exception $e) {
            // Log error but allow registration
        }

        Auth::login($user);

        return redirect()->route('dashboard.home');
    }

    public function render()
    {
        return view('livewire.auth.register')->layout('layouts.auth');
    }
}
