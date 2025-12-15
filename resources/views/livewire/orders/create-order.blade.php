<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ __('Create New Order') }}</h1>
        <p class="text-sm text-gray-500 mt-1">{{ __('Place a new order request.') }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <form wire:submit.prevent="store" class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Subject -->
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Subject') }}</label>
                    <input wire:model="subject" type="text"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm placeholder-gray-400"
                        placeholder="{{ __('Order title') }}" required>
                    @error('subject')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Branch -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Branch') }}</label>
                    <select wire:model="branches_id"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        required>
                        <option value="">{{ __('Select Branch') }}</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    @error('branches_id')
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

                <!-- Order Items -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex justify-between items-center mb-4">
                        <label class="block text-sm font-semibold text-gray-700">{{ __('Order Items') }}</label>
                        <button type="button" wire:click="addItem"
                            class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                            <span class="text-lg">+</span> {{ __('Add Item') }}
                        </button>
                    </div>

                    @if (count($items) > 0)
                        <div class="space-y-3">
                            @foreach ($items as $index => $item)
                                <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg border border-gray-100">
                                    <div class="flex-1">
                                        <label
                                            class="block text-xs font-medium text-gray-500 mb-1">{{ __('Item') }}</label>
                                        <select wire:model="items.{{ $index }}.stock_id"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                            <option value="">{{ __('Select Item') }}</option>
                                            @foreach ($availableStocks as $stock)
                                                <option value="{{ $stock->id }}">
                                                    {{ $stock->name }}
                                                    @if ($stock->brands)
                                                        ({{ $stock->brands->name }})
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('items.' . $index . '.stock_id')
                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="w-32">
                                        <label
                                            class="block text-xs font-medium text-gray-500 mb-1">{{ __('Quantity') }}</label>
                                        <input type="number" wire:model="items.{{ $index }}.quantity"
                                            min="1"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        @error('items.' . $index . '.quantity')
                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="pt-6">
                                        <button type="button" wire:click="removeItem({{ $index }})"
                                            class="text-red-500 hover:text-red-700">
                                            <span class="sr-only">{{ __('Remove') }}</span>
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div
                            class="text-center italic text-sm text-gray-400 py-4 bg-gray-50 rounded-lg border border-dashed border-gray-200">
                            {{ __('No items added to this order yet.') }}
                        </div>
                    @endif
                </div>

                <!-- Message -->
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Order Details') }}</label>
                    <textarea wire:model="message" rows="6"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm placeholder-gray-400"
                        placeholder="{{ __('Provide details about your order...') }}" required></textarea>
                    @error('message')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('orders.list') }}"
                    class="px-5 py-2.5 bg-white border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out shadow-sm">
                    {{ __('Cancel') }}
                </a>
                <button type="submit"
                    class="px-5 py-2.5 bg-blue-600 border border-transparent rounded-lg text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out shadow-sm">
                    {{ __('Submit Order') }}
                </button>
            </div>
        </form>
    </div>
</div>
