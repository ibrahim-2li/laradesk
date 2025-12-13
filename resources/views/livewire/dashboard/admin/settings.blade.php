<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ __('Settings') }}</h1>
        <p class="text-sm text-gray-500 mt-1">{{ __('Manage your application configuration.') }}</p>
    </div>

    @if (session()->has('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg relative mb-6 shadow-sm flex items-center gap-2"
            role="alert">
            <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd" />
            </svg>
            <span class="block sm:inline font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div
        class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col md:flex-row min-h-[600px]">
        <!-- Sidebar Tabs -->
        <div class="w-full md:w-64 bg-gray-50/50 border-r border-gray-100 flex-shrink-0">
            <nav class="flex flex-col p-4 space-y-1">
                @foreach ([
        'general' => 'General',
        'seo' => 'SEO',
        'appearance' => 'Appearance',
        'localization' => 'Localization',
        'auth' => 'Authentication',
        'mail' => 'Outgoing Mail',
        'logging' => 'Logging',
        'captcha' => 'Captcha',
    ] as $key => $label)
                    <button wire:click="setTab('{{ $key }}')"
                        class="px-4 py-2.5 text-left rounded-lg text-sm font-medium transition-all duration-200 flex items-center justify-between group
                        {{ $activeTab === $key ? 'bg-white text-blue-600 shadow-sm ring-1 ring-gray-200' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        {{ __($label) }}
                        @if ($activeTab === $key)
                            <svg class="w-4 h-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        @endif
                    </button>
                @endforeach
            </nav>
        </div>

        <!-- Content Area -->
        <div class="flex-1 p-8">

            <!-- General Tab -->
            @if ($activeTab === 'general')
                <form wire:submit.prevent="saveGeneral" class="space-y-6 max-w-2xl">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-1">{{ __('General Settings') }}</h2>
                        <p class="text-sm text-gray-500 mb-6">{{ __('Configure basic application information.') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('App URL') }}</label>
                        <input wire:model="app_url" type="text"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('App Name') }}</label>
                        <input wire:model="app_name" type="text"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <div class="flex items-center pt-2">
                        <input wire:model="app_https" id="app_https" type="checkbox"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer">
                        <label for="app_https"
                            class="ml-2 block text-sm text-gray-900 cursor-pointer">{{ __('Force HTTPS') }}</label>
                    </div>
                    <div class="pt-6 border-t border-gray-100">
                        <button type="submit"
                            class="px-5 py-2.5 bg-blue-600 border border-transparent rounded-lg text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 shadow-sm">
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </form>
            @endif

            <!-- SEO Tab -->
            @if ($activeTab === 'seo')
                <form wire:submit.prevent="saveSeo" class="space-y-6 max-w-2xl">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-1">{{ __('SEO Settings') }}</h2>
                        <p class="text-sm text-gray-500 mb-6">{{ __('Optimize your application for search engines.') }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Home Title') }}</label>
                        <input wire:model="meta_home_title" type="text"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Keywords') }}</label>
                        <input wire:model="meta_keywords" type="text"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Description') }}</label>
                        <textarea wire:model="meta_description" rows="4"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                    </div>
                    <div class="pt-6 border-t border-gray-100">
                        <button type="submit"
                            class="px-5 py-2.5 bg-blue-600 border border-transparent rounded-lg text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 shadow-sm">
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </form>
            @endif

            <!-- Appearance Tab -->
            @if ($activeTab === 'appearance')
                <form wire:submit.prevent="saveAppearance" class="space-y-6 max-w-2xl">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-1">{{ __('Appearance Settings') }}</h2>
                        <p class="text-sm text-gray-500 mb-6">{{ __('Customize the look and feel of your app.') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('App Icon') }}</label>
                        <div class="flex items-center gap-4">
                            @if ($current_app_icon)
                                <div class="bg-gray-100 p-2 rounded-lg border border-gray-200">
                                    <img src="{{ $current_app_icon }}" class="h-12 w-auto">
                                </div>
                            @endif
                            <input wire:model="app_icon" type="file"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Login Background') }}</label>
                        <div class="space-y-4">
                            @if ($current_app_background)
                                <div
                                    class="relative w-full h-48 rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                                    <img src="{{ $current_app_background }}" class="w-full h-full object-cover">
                                </div>
                            @endif
                            <input wire:model="app_background" type="file"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-100">
                        <button type="submit"
                            class="px-5 py-2.5 bg-blue-600 border border-transparent rounded-lg text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 shadow-sm">
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </form>
            @endif

            <!-- Localization Tab -->
            @if ($activeTab === 'localization')
                <form wire:submit.prevent="saveLocalization" class="space-y-6 max-w-2xl">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-1">{{ __('Localization Settings') }}</h2>
                        <p class="text-sm text-gray-500 mb-6">{{ __('Set date, time, and language preferences.') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Timezone') }}</label>
                        <select wire:model="app_timezone"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <!-- Ideally populate this dynamically -->
                            <option value="UTC">UTC</option>
                            <option value="Europe/Paris">Europe/Paris</option>
                            <option value="America/New_York">America/New_York</option>
                        </select>
                    </div>
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Default Locale') }}</label>
                        <select wire:model="app_locale"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            @foreach ($languages as $lang)
                                <option value="{{ $lang->code ?? 'en' }}">{{ $lang->name ?? 'English' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Date Locale') }}</label>
                        <input wire:model="app_date_locale" type="text"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Date Format') }}</label>
                        <input wire:model="app_date_format" type="text"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <div class="pt-6 border-t border-gray-100">
                        <button type="submit"
                            class="px-5 py-2.5 bg-blue-600 border border-transparent rounded-lg text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 shadow-sm">
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </form>
            @endif

            <!-- Auth Tab -->
            @if ($activeTab === 'auth')
                <form wire:submit.prevent="saveAuthentication" class="space-y-6 max-w-2xl">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-1">{{ __('Authentication Settings') }}</h2>
                        <p class="text-sm text-gray-500 mb-6">{{ __('Manage user registration and access.') }}</p>
                    </div>
                    <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <input wire:model="app_user_registration" id="app_user_registration" type="checkbox"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer">
                        <label for="app_user_registration"
                            class="ml-3 block text-sm font-medium text-gray-900 cursor-pointer">{{ __('Enable User Registration') }}</label>
                    </div>
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Default User Role') }}</label>
                        <select wire:model="app_default_role"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="pt-6 border-t border-gray-100">
                        <button type="submit"
                            class="px-5 py-2.5 bg-blue-600 border border-transparent rounded-lg text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 shadow-sm">
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </form>
            @endif

            <!-- Mail Tab -->
            @if ($activeTab === 'mail')
                <form wire:submit.prevent="saveMail" class="space-y-6 max-w-4xl">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-1">{{ __('Outgoing Mail Settings') }}</h2>
                        <p class="text-sm text-gray-500 mb-6">{{ __('Configure SMTP and mail services.') }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-700 mb-1">{{ __('From Address') }}</label>
                            <input wire:model="mail_from_address" type="text"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-700 mb-1">{{ __('From Name') }}</label>
                            <input wire:model="mail_from_name" type="text"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Mailer (smtp, mailgun, log)') }}</label>
                            <input wire:model="mail_mailer" type="text"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Host') }}</label>
                            <input wire:model="mail_host" type="text"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Port') }}</label>
                            <input wire:model="mail_port" type="text"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Username') }}</label>
                            <input wire:model="mail_username" type="text"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Password') }}</label>
                            <input wire:model="mail_password" type="password"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Encryption (tls/ssl)') }}</label>
                            <input wire:model="mail_encryption" type="text"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Mailgun (Optional)') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Domain') }}</label>
                                <input wire:model="mailgun_domain" type="text"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Secret') }}</label>
                                <input wire:model="mailgun_secret" type="password"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label
                                    class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Endpoint') }}</label>
                                <input wire:model="mailgun_endpoint" type="text"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-100">
                        <button type="submit"
                            class="px-5 py-2.5 bg-blue-600 border border-transparent rounded-lg text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 shadow-sm">
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </form>
            @endif

            <!-- Logging Tab -->
            @if ($activeTab === 'logging')
                <form wire:submit.prevent="saveLogging" class="space-y-6 max-w-2xl">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-1">{{ __('Logging (Sentry)') }}</h2>
                        <p class="text-sm text-gray-500 mb-6">{{ __('Error tracking and performance monitoring.') }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Sentry DSN') }}</label>
                        <input wire:model="sentry_dsn" type="text"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <div class="pt-6 border-t border-gray-100">
                        <button type="submit"
                            class="px-5 py-2.5 bg-blue-600 border border-transparent rounded-lg text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 shadow-sm">
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </form>
            @endif

            <!-- Captcha Tab -->
            @if ($activeTab === 'captcha')
                <form wire:submit.prevent="saveCaptcha" class="space-y-6 max-w-2xl">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-1">{{ __('ReCaptcha Settings') }}</h2>
                        <p class="text-sm text-gray-500 mb-6">{{ __('Protect your forms from spam.') }}</p>
                    </div>
                    <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <input wire:model="recaptcha_enabled" id="recaptcha_enabled" type="checkbox"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer">
                        <label for="recaptcha_enabled"
                            class="ml-3 block text-sm font-medium text-gray-900 cursor-pointer">{{ __('Enable ReCaptcha') }}</label>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Public Key') }}</label>
                        <input wire:model="recaptcha_public" type="text"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Private Key') }}</label>
                        <input wire:model="recaptcha_private" type="text"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <div class="pt-6 border-t border-gray-100">
                        <button type="submit"
                            class="px-5 py-2.5 bg-blue-600 border border-transparent rounded-lg text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 shadow-sm">
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </form>
            @endif

        </div>
    </div>
</div>
