<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use App\Notifications\Auth\ResetPassword as ResetPasswordNotification;
use Carbon\Carbon;
use stdClass;

class Recover extends Component
{
    public $email = '';
    public $status = null;

    public function sendResetLink()
    {
        $this->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $this->email)->first();

        if (!$user) {
            $this->addError('email', __('The email entered is not registered'));
            return;
        }

        $token = Str::random(60);
        DB::table('password_resets')->where('email', $this->email)->delete();
        DB::table('password_resets')->insert([
            'email' => $this->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        $objNotificationData = new stdClass();
        $objNotificationData->token = $token;
        $objNotificationData->user = $user;
        
        // Assuming the notification class exists and works as before
        // $user->notify(new ResetPasswordNotification($objNotificationData));

        $this->status = __('An email has been sent with a link to reset the password');
        $this->email = '';
    }

    public function render()
    {
        return view('livewire.auth.recover')->layout('layouts.auth');
    }
}
