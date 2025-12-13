<form wire:submit.prevent="sendResetLink">
    @if ($status)
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ $status }}
        </div>
    @endif

    <div class="mb-4 relative rounded-md shadow-sm">
        <label class="block text-sm font-medium leading-5 text-gray-700" for="email">{{ __('Email') }}</label>
        <input wire:model="email" id="email"
            class="form-input block w-full mt-1 sm:text-sm sm:leading-5 border-gray-300 rounded-md focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
            required type="email" autofocus placeholder="{{ __('Enter your email address') }}" />
        @error('email')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror
    </div>

    <div class="mb-4 text-right">
        <button type="submit"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-8 rounded focus:outline-none focus:shadow-outline">
            {{ __('Recover account') }}
        </button>
    </div>

    <p class="text-gray-700 text-sm">
        {{ __('Remember your password?') }}
        <a class="align-baseline font-bold text-blue-500 hover:text-blue-800" href="{{ route('login') }}">
            {{ __('Sign In') }}
        </a>
    </p>
</form>
