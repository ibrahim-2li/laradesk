<div class="w-full">
    <div class="text-center lg:text-start mb-10">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ __('Welcome back! Login to your account') }}</h2>
        <p class="text-gray-500 text-sm">
            {{ __('Welcome back! Log in to your account to get access to your dashboard.') }}</p>
    </div>

    <form wire:submit.prevent="login" class="space-y-6">
        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email Address') }} <span
                    class="text-red-500">*</span></label>
            <div class="relative">
                <div
                    class="absolute inset-y-0 left-0 pl-3 rtl:pl-0 rtl:right-0 rtl:pr-3 flex items-center pointer-events-none text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <input wire:model="email" id="email" type="email" required autofocus
                    class="block w-full pl-10 rtl:pl-3 rtl:pr-10 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-colors sm:text-sm"
                    placeholder="{{ __('Email Address') }}">
            </div>
            @error('email')
                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1">
                <label for="password" class="block text-sm font-medium text-gray-700">{{ __('Password') }} <span
                        class="text-red-500">*</span></label>
            </div>
            <div class="relative">
                <div
                    class="absolute inset-y-0 left-0 pl-3 rtl:pl-0 rtl:right-0 rtl:pr-3 flex items-center text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <input wire:model="password" id="password" type="password" required
                    class="block w-full pl-10 rtl:pl-3 rtl:pr-10 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-colors sm:text-sm"
                    placeholder="{{ __('Password') }}">
            </div>
            @error('password')
                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
            @enderror

            <div class="mt-2 text-end">
                <a href="{{ route('password.request') }}"
                    class="text-sm font-medium text-brand-600 hover:text-brand-500">{{ __('Forgot your password?') }}</a>
            </div>
        </div>

        <!-- Submit -->
        <button type="submit"
            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-lg font-bold text-white bg-brand-600 hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-all transform hover:scale-[1.01]">
            {{ __('Sign In') }}
        </button>

        <!-- Register Link -->
        <div class="text-center mt-6">
            <span class="text-gray-500 text-sm">{{ __('Don\'t have an account?') }}</span>
            <a href="{{ route('register') }}"
                class="text-sm font-bold text-blue-600 hover:text-blue-500 hover:underline">
                {{ __('Create account') }}
            </a>
        </div>
    </form>

    <!-- Social Login Divider -->
    <div class="relative mt-10 mb-8">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-200"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-4 bg-white text-gray-500">{{ __('Or') }}</span>
        </div>
    </div>

    <!-- Social Buttons -->
    <div class="space-y-4">
        <button
            class="w-full flex items-center justify-center gap-3 px-4 py-3 border border-gray-200 rounded-xl shadow-sm bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors">
            <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="h-5 w-5" alt="Google">
            {{ __('Sign in with Google') }}
        </button>
        <button
            class="w-full flex items-center justify-center gap-3 px-4 py-3 border border-gray-200 rounded-xl shadow-sm bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors">
            <img src="https://www.svgrepo.com/show/475647/facebook-color.svg" class="h-5 w-5" alt="Facebook">
            {{ __('Sign in with Facebook') }}
        </button>
        <button
            class="w-full flex items-center justify-center gap-3 px-4 py-3 border border-gray-200 rounded-xl shadow-sm bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors">
            <svg class="h-5 w-5 text-gray-900" fill="currentColor" viewBox="0 0 24 24">
                <path
                    d="M16.365 1.43c0 1.14-.493 2.27-1.177 3.08-.684.816-1.93 1.44-3.13 1.44-1.2 0-2.39-.553-3.106-1.44-.717-.89-1.07-2.02-1.07-3.08 0-1.12.51-2.3 1.177-3.12.667-.82 1.93-1.44 3.13-1.44s2.39.62 3.106 1.49c.697.87 1.07 1.97 1.07 3.07zM17.43 5.4c.55 0 1.08.13 1.58.37 1.17.57 2.14 1.54 2.7 2.7C22.28 9.64 22.28 10.97 21.71 12.14c-.57 1.17-1.54 2.14-2.7 2.7-.44.22-.92.37-1.41.44-.68.1-1.38-.03-2.03-.37-.65-.34-1.22-.85-1.63-1.46-.41-.61-.64-1.34-.64-2.09 0-2.21 1.79-4 4-4 .05 0 .1 0 .15.01-.22-.38-.52-.71-.88-.97-.36-.26-.76-.46-1.18-.59-.42-.13-.86-.2-1.3-.2H17.43zM4 12c0 4.418 3.582 8 8 8s8-3.582 8-8h-2c0 3.314-2.686 6-6 6s-6-2.686-6-6H4zm8-10c-3.19 0-6.02 1.53-7.75 3.91l1.71 1.05C7.43 5.16 9.58 4 12 4V2z" />
            </svg>
            {{ __('Sign in with Apple') }}
        </button>
    </div>
</div>
