<div class="mt-20">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Header -->
            <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">{{ __('My Profile') }}</h2>
            </div>

            <div class="p-8">
                @if (session()->has('success'))
                    <div
                        class="mb-6 p-4 rounded-lg bg-green-50 text-green-700 border border-green-200 flex items-center gap-3">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                <form wire:submit.prevent="save">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Left Column: Avatar -->
                        <div class="md:col-span-1 flex flex-col items-center">
                            <div class="relative group">
                                <div
                                    class="w-32 h-32 rounded-full overflow-hidden border-4 border-gray-50 shadow-inner">
                                    @if ($avatar)
                                        <img src="{{ $avatar->temporaryUrl() }}" class="w-full h-full object-cover">
                                    @else
                                        <img src="{{ $existingAvatar }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <label for="avatar-upload"
                                    class="absolute bottom-0 right-0 bg-brand-600 text-white p-2 rounded-full shadow-lg cursor-pointer hover:bg-brand-700 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <input wire:model="avatar" id="avatar-upload" type="file" class="hidden"
                                        accept="image/*">
                                </label>
                            </div>
                            <p class="mt-4 text-sm text-gray-500 text-center">{{ __('Click camera icon to change') }}
                            </p>
                            @error('avatar')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Right Column: Form Fields -->
                        <div class="md:col-span-2 space-y-6">
                            <!-- Basic Info -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2">
                                    {{ __('Basic Information') }}</h3>
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('Full Name') }}</label>
                                        <input wire:model="name" type="text"
                                            class="w-full rounded-lg border-gray-200 focus:border-brand-500 focus:ring-brand-500 transition-colors">
                                        @error('name')
                                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email Address') }}</label>
                                        <input wire:model="email" type="email"
                                            class="w-full rounded-lg border-gray-200 focus:border-brand-500 focus:ring-brand-500 transition-colors">
                                        @error('email')
                                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Password Change -->
                            <div class="pt-4">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2">
                                    {{ __('Change Password') }}</h3>
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('New Password') }}
                                            <span
                                                class="text-gray-400 font-normal">({{ __('Optional') }})</span></label>
                                        <input wire:model="password" type="password"
                                            class="w-full rounded-lg border-gray-200 focus:border-brand-500 focus:ring-brand-500 transition-colors">
                                        @error('password')
                                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('Confirm New Password') }}</label>
                                        <input wire:model="password_confirmation" type="password"
                                            class="w-full rounded-lg border-gray-200 focus:border-brand-500 focus:ring-brand-500 transition-colors">
                                    </div>
                                </div>
                            </div>

                            <div class="pt-6 flex justify-end">
                                <button type="submit"
                                    class="bg-brand-600 text-white px-6 py-2.5 rounded-lg font-semibold shadow-sm hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 transition-all flex items-center gap-2"
                                    wire:loading.attr="disabled">
                                    <span wire:loading.remove>{{ __('Save Changes') }}</span>
                                    <span wire:loading class="flex items-center gap-2">
                                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        {{ __('Saving...') }}
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
