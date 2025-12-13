<div>
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">{{ __('Stock') }}</h1>
        <button wire:click="create" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
            {{ __('New Stock Item') }}
        </button>
    </div>

    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b border-gray-200">
            <div class="w-full md:w-1/3">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="{{ __('Search stock...') }}"
                    class="form-input rounded-md shadow-sm block w-full" />
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Name') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Quantity') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Brand') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($stocks as $stock)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $stock->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $stock->quantity }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $stock->brands->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button wire:click="edit({{ $stock->id }})"
                                    class="text-blue-600 hover:text-blue-900 mr-3">{{ __('Edit') }}</button>
                                <button wire:click="delete({{ $stock->id }})"
                                    wire:confirm="{{ __('Are you sure you want to delete this item?') }}"
                                    class="text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                {{ __('No stock found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4">
            {{ $stocks->links() }}
        </div>
    </div>

    <!-- Modal Form -->
    @if ($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ $editingId ? __('Edit Stock Item') : __('Create New Stock Item') }}
                    </h3>
                </div>
                <form wire:submit.prevent="store">
                    <div class="px-6 py-4 space-y-4">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Name') }}</label>
                            <input wire:model="name" type="text"
                                class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            @error('name')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Quantity -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Quantity') }}</label>
                            <input wire:model="quantity" type="number"
                                class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm" required
                                min="0">
                            @error('quantity')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Brand -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Brand') }}</label>
                            <select wire:model="brand_id"
                                class="form-select mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">{{ __('Select Brand') }}</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                            @error('brand_id')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                    <div class="px-6 py-4 bg-gray-50 flex justify-end rounded-b-lg">
                        <button type="button" wire:click="closeModal"
                            class="mr-3 px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
                            {{ __('Save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
