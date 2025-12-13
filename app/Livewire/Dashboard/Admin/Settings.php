<?php

namespace App\Livewire\Dashboard\Admin;

use App\Models\Setting;
use App\Models\UserRole;
use App\Models\Language; // Assuming this exists
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class Settings extends Component
{
    use WithFileUploads;

    public $activeTab = 'general';

    // General
    public $app_url;
    public $app_name;
    public $app_https;

    // SEO
    public $meta_home_title;
    public $meta_keywords;
    public $meta_description;

    // Appearance
    public $app_icon; // File
    public $app_background; // File
    public $current_app_icon;
    public $current_app_background;

    // Localization
    public $app_timezone;
    public $app_locale;
    public $app_date_locale;
    public $app_date_format;
    public $languages = [];

    // Authentication
    public $app_user_registration;
    public $app_default_role;
    public $roles = [];

    // Outgoing Mail
    public $mail_from_address;
    public $mail_from_name;
    public $mail_mailer;
    public $mail_encryption;
    public $mail_host;
    public $mail_password;
    public $mail_port;
    public $mail_username;
    public $mailgun_domain;
    public $mailgun_secret;
    public $mailgun_endpoint;

    // Logging
    public $sentry_dsn;

    // Captcha
    public $recaptcha_enabled;
    public $recaptcha_public;
    public $recaptcha_private;

    public function mount()
    {
        if (Auth::user()->role_id !== 1) {
            abort(403, __('Unauthorized'));
        }

        $this->loadSettings();
    }

    public function loadSettings()
    {
        // General
        $this->app_url = Setting::getDecoded('app_url');
        $this->app_name = Setting::getDecoded('app_name');
        $this->app_https = (bool) Setting::getDecoded('app_https');

        // SEO
        $this->meta_home_title = Setting::getDecoded('meta_home_title');
        $this->meta_keywords = Setting::getDecoded('meta_keywords');
        $this->meta_description = Setting::getDecoded('meta_description');

        // Appearance
        $this->current_app_icon = Setting::get('app_icon');
        if ($this->current_app_icon && !str_starts_with($this->current_app_icon, 'http')) {
            $this->current_app_icon = \Storage::url($this->current_app_icon);
        }
        
        $this->current_app_background = Setting::get('app_background');
        if ($this->current_app_background && !str_starts_with($this->current_app_background, 'http')) {
             $this->current_app_background = \Storage::url($this->current_app_background);
        }

        // Localization
        $this->app_timezone = Setting::getDecoded('app_timezone');
        $this->app_locale = Setting::getDecoded('app_locale');
        $this->app_date_locale = Setting::getDecoded('app_date_locale');
        $this->app_date_format = Setting::getDecoded('app_date_format');
        $this->languages = Language::all();

        // Authentication
        $this->app_user_registration = (bool) Setting::getDecoded('app_user_registration');
        $this->app_default_role = Setting::getDecoded('app_default_role');
        $this->roles = UserRole::all();

        // Mail
        $this->mail_from_address = Setting::getDecoded('mail_from_address');
        $this->mail_from_name = Setting::getDecoded('mail_from_name');
        $this->mail_mailer = Setting::getDecoded('mail_mailer');
        $this->mail_encryption = Setting::getDecoded('mail_encryption');
        $this->mail_host = Setting::getDecoded('mail_host');
        $this->mail_password = Setting::getDecoded('mail_password');
        $this->mail_port = Setting::getDecoded('mail_port');
        $this->mail_username = Setting::getDecoded('mail_username');
        $this->mailgun_domain = Setting::getDecoded('mailgun_domain');
        $this->mailgun_secret = Setting::getDecoded('mailgun_secret');
        $this->mailgun_endpoint = Setting::getDecoded('mailgun_endpoint');

        // Logging
        $this->sentry_dsn = Setting::getDecoded('sentry_dsn');

        // Captcha
        $this->recaptcha_enabled = (bool) Setting::getDecoded('recaptcha_enabled');
        $this->recaptcha_public = Setting::getDecoded('recaptcha_public');
        $this->recaptcha_private = Setting::getDecoded('recaptcha_private');
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function saveGeneral()
    {
        $this->updateSetting('app_url', $this->app_url);
        $this->updateSetting('app_name', $this->app_name);
        $this->updateSetting('app_https', $this->app_https);
        session()->flash('success', 'General settings saved.');
    }

    public function saveSeo()
    {
        $this->updateSetting('meta_home_title', $this->meta_home_title);
        $this->updateSetting('meta_keywords', $this->meta_keywords);
        $this->updateSetting('meta_description', $this->meta_description);
        session()->flash('success', 'SEO settings saved.');
    }

    public function saveAppearance()
    {
        if ($this->app_icon) {
             $path = $this->app_icon->store('appearance/icon', 'public');
             $this->updateSetting('app_icon', $path);
             // Refresh preview
             $this->current_app_icon = \Storage::url($path);
        }
        
        if ($this->app_background) {
             $path = $this->app_background->store('appearance/background', 'public');
             $this->updateSetting('app_background', $path);
             $this->current_app_background = \Storage::url($path);
        }

        session()->flash('success', 'Appearance settings saved.');
    }

    public function saveLocalization()
    {
        $this->updateSetting('app_timezone', $this->app_timezone);
        $this->updateSetting('app_locale', $this->app_locale);
        $this->updateSetting('app_date_locale', $this->app_date_locale);
        $this->updateSetting('app_date_format', $this->app_date_format);
        session()->flash('success', 'Localization settings saved.');
    }

    public function saveAuthentication()
    {
        $this->updateSetting('app_user_registration', $this->app_user_registration);
        $this->updateSetting('app_default_role', $this->app_default_role);
        session()->flash('success', 'Authentication settings saved.');
    }

    public function saveMail()
    {
        $this->updateSetting('mail_from_address', $this->mail_from_address);
        $this->updateSetting('mail_from_name', $this->mail_from_name);
        $this->updateSetting('mail_mailer', $this->mail_mailer);
        $this->updateSetting('mail_encryption', $this->mail_encryption);
        $this->updateSetting('mail_host', $this->mail_host);
        $this->updateSetting('mail_password', $this->mail_password);
        $this->updateSetting('mail_port', $this->mail_port);
        $this->updateSetting('mail_username', $this->mail_username);
        $this->updateSetting('mailgun_domain', $this->mailgun_domain);
        $this->updateSetting('mailgun_secret', $this->mailgun_secret);
        $this->updateSetting('mailgun_endpoint', $this->mailgun_endpoint);
        session()->flash('success', 'Mail settings saved.');
    }

    public function saveLogging()
    {
        $this->updateSetting('sentry_dsn', $this->sentry_dsn);
        session()->flash('success', 'Logging settings saved.');
    }

    public function saveCaptcha()
    {
        $this->updateSetting('recaptcha_enabled', $this->recaptcha_enabled);
        $this->updateSetting('recaptcha_public', $this->recaptcha_public);
        $this->updateSetting('recaptcha_private', $this->recaptcha_private);
        session()->flash('success', 'Captcha settings saved.');
    }

    private function updateSetting($key, $value)
    {
        $setting = Setting::find($key);
        if ($setting) {
            $setting->value = $value;
            $setting->save();
        }
    }

    public function render()
    {
        return view('livewire.dashboard.admin.settings')->layout('layouts.dashboard');
    }
}
