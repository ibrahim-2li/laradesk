<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Attributes\Url;
use Carbon\Carbon;

class Reset extends Component
{
    #[Url]
    public $token = '';

    public $email = '';
    public $password = '';
    public $password_confirmation = '';

    public function mount()
    {
        // Try to pre-fill email if possible from token? 
        // Or usually token is enough, but Laravel's default reset needs email too.
        // We will just ask for email as verification.
    }

    public function resetPassword()
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $tokenData = DB::table('password_resets')->where('token', $this->token)->first();

        if (!$tokenData || $tokenData->email !== $this->email) {
            $this->addError('email', __('The recovery token is incorrect, expired, or email does not match.'));
            return;
        }

        $user = User::where('email', $this->email)->first();
         if (!$user) {
            $this->addError('email', __('The email entered is not registered'));
            return;
        }

        $user->password = Hash::make($this->password);
        if (is_null($user->email_verified_at)) {
            $user->email_verified_at = Carbon::now();
        }
        $user->save();

        DB::table('password_resets')->where('email', $this->email)->delete();

        Auth::login($user);

        return redirect()->route('dashboard.home');
    }

    public function render()
    {
        return view('livewire.auth.reset')->layout('layouts.auth');
    }
}
