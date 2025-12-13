<form wire:submit.prevent="resetPassword">
    <input type="hidden" wire:model="token">

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

    <div class="mb-4 relative rounded-md shadow-sm">
        <label class="block text-sm font-medium leading-5 text-gray-700"
            for="password_confirmation">{{ __('Confirm Password') }}</label>
        <input wire:model="password_confirmation" id="password_confirmation"
            class="form-input block w-full mt-1 sm:text-sm sm:leading-5 border-gray-300 rounded-md focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
            required type="password" />
    </div>

    <div class="mb-4 text-right">
        <button type="submit"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-8 rounded focus:outline-none focus:shadow-outline">
            {{ __('Reset Password') }}
        </button>
    </div>
</form>
