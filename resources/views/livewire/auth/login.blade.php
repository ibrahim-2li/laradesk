<form wire:submit.prevent="login">
    <div class="mb-4 relative rounded-md shadow-sm">
        <label class="block text-sm font-medium leading-5 text-gray-700" for="email">{{ __('Email') }}</label>
        <input wire:model="email" id="email"
            class="form-input block w-full mt-1 sm:text-sm sm:leading-5 border-gray-300 rounded-md focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
            required type="email" autofocus />
        @error('email')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror
    </div>

    <div class="mb-4 relative rounded-md shadow-sm">
        <label class="block text-sm font-medium leading-5 text-gray-700" for="password">{{ __('Password') }}</label>
        <input wire:model="password" id="password"
            class="form-input block w-full mt-1 sm:text-sm sm:leading-5 border-gray-300 rounded-md focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
            required type="password" />
        @error('password')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror
    </div>

    <div class="block mt-4 mb-4">
        <label for="remember_me" class="inline-flex items-center">
            <input wire:model="remember" id="remember_me" type="checkbox"
                class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
            <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
        </label>
    </div>

    <div class="mb-4 text-right">
        <button type="submit"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-8 rounded focus:outline-none focus:shadow-outline">
            {{ __('Sign In') }}
        </button>
    </div>

    <p class="text-gray-700 text-sm">
        {{ __('Don\'t have an account?') }}
        <a class="align-baseline font-bold text-blue-500 hover:text-blue-800" href="{{ route('register') }}">
            {{ __('Create account') }}
        </a>
    </p>

    <p class="text-gray-700 text-sm">
        {{ __('You can not access your account?') }}
        <a class="align-baseline font-bold text-blue-500 hover:text-blue-800" href="{{ route('password.request') }}">
            {{ __('Recover account') }}
        </a>
    </p>
</form>
