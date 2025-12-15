<div>
    <div class="bg-white rounded-[4px] shadow-sm border border-gray-100 mt-20">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-700">{{ __('Canned Replies') }}</h2>

            <div class="flex gap-4">
                <div class="relative">
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="{{ __('Search...') }}"
                        class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-300 w-64 transition-all" />
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <button wire:click="create"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg text-sm transition-colors shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('New') }}
                </button>
            </div>
        </div>

        @if (session()->has('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 m-4" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">
                            {{ __('Name') }}</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">
                            {{ __('Visibility') }}</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">
                            {{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($cannedReplies as $reply)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-600">{{ $reply->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if ($reply->shared)
                                    <span
                                        class="bg-green-100 text-green-700 py-1 px-3 rounded-[4px] text-xs font-medium border border-green-200">
                                        {{ __('Shared') }}
                                    </span>
                                @else
                                    <span
                                        class="bg-gray-100 text-gray-700 py-1 px-3 rounded-[4px] text-xs font-medium border border-gray-200">
                                        {{ __('Private') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                @if ($reply->user_id === auth()->id())
                                    <div class="relative inline-block text-left" x-data="{ open: false }">
                                        <button @click="open = !open" @click.away="open = false"
                                            class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path
                                                    d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                            </svg>
                                        </button>
                                        <div x-show="open"
                                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 ring-1 ring-black ring-opacity-5 origin-top-right text-left"
                                            style="display: none;">
                                            <div class="py-1">
                                                <button wire:click="edit({{ $reply->id }})" @click="open = false"
                                                    class="group flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    <svg class="mr-3 h-4 w-4 text-gray-400 group-hover:text-blue-500"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    {{ __('Edit') }}
                                                </button>
                                                <button wire:click="delete({{ $reply->id }})"
                                                    wire:confirm="{{ __('Are you sure?') }}" @click="open = false"
                                                    class="group flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                                    <svg class="mr-3 h-4 w-4 text-red-400 group-hover:text-red-500"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    {{ __('Delete') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                        </path>
                                    </svg>
                                    <p>{{ __('No canned replies found') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100">
            {{ $cannedReplies->links() }}
        </div>
    </div>

    <!-- Modal Form -->
    @if ($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ $editingId ? __('Edit Canned Reply') : __('Create New Canned Reply') }}
                    </h3>
                </div>
                <form wire:submit.prevent="store">
                    <div class="px-6 py-4 space-y-4">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Name') }}</label>
                            <input wire:model="name" type="text"
                                class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm" required
                                placeholder="{{ __('e.g., Reset Password Instructions') }}">
                            @error('name')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Body -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Body') }}</label>
                            <textarea wire:model="body" rows="6" class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                required placeholder="{{ __('Enter the reply content...') }}"></textarea>
                            @error('body')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Shared -->
                        <div class="flex items-center">
                            <input wire:model="shared" type="checkbox"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer">
                            <label
                                class="ml-2 block text-sm text-gray-900 cursor-pointer">{{ __('Share with other agents') }}</label>
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
