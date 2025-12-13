<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ __('Create New Ticket') }}</h1>
        <p class="text-sm text-gray-500 mt-1">
            {{ __('We are here to help! Submit a ticket and we will get back to you.') }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <form wire:submit.prevent="store" class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Subject -->
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Subject') }}</label>
                    <input wire:model="subject" type="text"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm placeholder-gray-400"
                        placeholder="{{ __('Brief summary of your issue') }}" required>
                    @error('subject')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Department -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Department') }}</label>
                    <select wire:model="department_id"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        required>
                        <option value="">{{ __('Select Department') }}</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Priority -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Priority') }}</label>
                    <select wire:model="priority_id"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        required>
                        @foreach ($priorities as $priority)
                            <option value="{{ $priority->id }}">{{ $priority->name }}</option>
                        @endforeach
                    </select>
                    @error('priority_id')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Message -->
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Message') }}</label>
                    <textarea wire:model="message" rows="6"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm placeholder-gray-400"
                        placeholder="{{ __('Please describe the issue in detail.') }}" required></textarea>
                    @error('message')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('tickets.list') }}"
                    class="px-5 py-2.5 bg-white border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out shadow-sm">
                    {{ __('Cancel') }}
                </a>
                <button type="submit"
                    class="px-5 py-2.5 bg-blue-600 border border-transparent rounded-lg text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out shadow-sm">
                    {{ __('Submit Ticket') }}
                </button>
            </div>
        </form>
    </div>
</div>
