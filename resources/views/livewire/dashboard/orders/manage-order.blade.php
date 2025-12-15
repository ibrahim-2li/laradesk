<div>
    <div class="mt-20 mb-8 flex flex-col md:flex-row md:justify-between md:items-start gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span
                    class="px-2.5 py-1 rounded-[4px] text-xs font-mono text-gray-600 bg-gray-100 border border-gray-200">#{{ $order->id }}</span>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ $order->subject }}</h1>
                <span
                    class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ optional($order->orderStatus)->name == 'Open' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-gray-100 text-gray-700 border border-gray-200' }}">
                    {{ $order->orderStatus->name ?? $order->orders_status_id }}
                </span>
            </div>
            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                <span class="flex items-center gap-2">
                    <img class="h-6 w-6 rounded-full border border-gray-200"
                        src="{{ $order->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($order->user->name) }}"
                        alt="">
                    <span class="font-medium text-gray-700">{{ $order->user->name }}</span>
                </span>
                <span class="text-gray-300">&bull;</span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ $order->created_at->format('M d, Y H:i') }}
                </span>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard.orders.list') }}"
                class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-2 px-4 rounded-lg text-sm transition-colors shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('Back to Orders') }}
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Conversation Area -->
        <div class="lg:col-span-3 space-y-8">
            <!-- Order Items -->
            <div class="bg-white rounded-[4px] shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">{{ __('Order Items') }}</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-50">
                        <thead>
                            <tr class="bg-white">
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                                    {{ __('Item') }}</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                                    {{ __('Brand') }}</th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">
                                    {{ __('Quantity') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($order->items as $item)
                                <tr class="hover:bg-gray-50/50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-700">
                                        {{ $item->stock?->name ?? __('Unknown Item') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->stock?->brands?->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right font-mono">
                                        {{ $item->quantity }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3"
                                        class="px-6 py-6 whitespace-nowrap text-sm text-gray-500 text-center italic">
                                        {{ __('No items found in this order.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-[4px] shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-6">{{ __('Conversation') }}</h3>
                <div class="space-y-6">
                    @foreach ($replies as $reply)
                        <div class="flex {{ $reply->user_id === $order->user_id ? 'justify-start' : 'justify-end' }}">
                            <div
                                class="flex flex-col {{ $reply->user_id === $order->user_id ? 'items-start' : 'items-end' }} max-w-[85%]">
                                <div class="flex items-center gap-2 mb-1 px-1">
                                    <span class="text-xs font-semibold text-gray-500">{{ $reply->user->name }}</span>
                                    @if ($reply->user_id !== $order->user_id)
                                        <span
                                            class="text-[10px] uppercase font-bold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded border border-blue-100">{{ __('Staff') }}</span>
                                    @endif
                                    <span class="text-xs text-gray-300">&bull;</span>
                                    <span
                                        class="text-xs text-gray-400">{{ $reply->created_at->format('M d, g:i a') }}</span>
                                </div>
                                <div
                                    class="px-5 py-3.5 rounded-2xl shadow-sm text-sm border {{ $reply->user_id === $order->user_id ? 'bg-gray-50 text-gray-800 rounded-tl-none border-gray-200' : 'bg-blue-600 text-white rounded-tr-none border-blue-600' }}">
                                    <div
                                        class="prose prose-sm prose-invert {{ $reply->user_id === $order->user_id ? 'text-gray-700' : 'text-blue-50' }}">
                                        {!! nl2br(e($reply->body)) !!}
                                    </div>

                                    @if ($reply->orderAttachments->count() > 0)
                                        <div
                                            class="mt-3 pt-3 border-t {{ $reply->user_id === $order->user_id ? 'border-gray-200' : 'border-blue-500/30' }}">
                                            <div class="flex flex-wrap gap-2">
                                                @foreach ($reply->orderAttachments as $attachment)
                                                    <a href="{{ $attachment->url() }}" target="_blank"
                                                        class="group relative block overflow-hidden rounded-lg border {{ $reply->user_id === $order->user_id ? 'border-gray-200' : 'border-blue-500' }}">
                                                        <img src="{{ $attachment->url() }}"
                                                            alt="{{ $attachment->name }}"
                                                            class="h-16 w-16 object-cover object-center transition-opacity group-hover:opacity-90 sm:h-20 sm:w-20">
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Reply Form -->
            <div class="bg-white rounded-[4px] shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Post a Reply') }}</h3>
                <form wire:submit.prevent="reply">
                    <div class="mb-4">
                        <textarea wire:model="body" rows="4"
                            class="block w-full rounded-lg border-gray-200 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm placeholder-gray-400 transition-shadow resize-none"
                            placeholder="{{ __('Type your reply here...') }}" required></textarea>
                        @error('body')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label
                            class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">{{ __('Attachments (Images only)') }}</label>
                        <input type="file" wire:model="attachments" multiple accept="image/*"
                            class="block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-xs file:font-semibold
                            file:bg-blue-50 file:text-blue-700
                            hover:file:bg-blue-100
                            transition-colors
                        " />
                        @error('attachments.*')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror

                        <!-- Preview (Optional basic feedback) -->
                        @if ($attachments)
                            <div class="mt-2 text-xs text-gray-500">
                                {{ count($attachments) }} {{ __('file(s) selected') }}
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-between gap-4 border-t border-gray-100 pt-4">
                        <div class="flex-1 max-w-xs">
                            <label
                                class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">{{ __('Update Status') }}</label>
                            <select wire:model="orders_status_id"
                                class="block w-full rounded-lg border-gray-200 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @foreach ($statuses as $status)
                                    <option value="{{ $status->id }}">{{ $status->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit"
                            class="px-5 py-2.5 bg-blue-600 border border-transparent rounded-lg text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out shadow-sm self-end flex items-center gap-2"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove>{{ __('Send Reply') }}</span>
                            <span wire:loading>{{ __('Sending...') }}</span>
                            <svg wire:loading.remove class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-[4px] shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider">{{ __('Order Details') }}
                    </h3>
                </div>
                <div class="p-4 space-y-5">
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">
                            {{ __('Department') }}</dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ $order->branches->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">
                            {{ __('Priority') }}</dt>
                        <dd class="text-sm font-medium text-gray-900 flex items-center gap-2">
                            @if (isset($order->priority->color))
                                <span class="w-2.5 h-2.5 rounded-full ring-1 ring-offset-1 ring-gray-100"
                                    style="background-color: {{ $order->priority->color }}"></span>
                            @endif
                            <span class="font-semibold">{{ $order->priority->name ?? '-' }}</span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">
                            {{ __('Assigned Agent') }}</dt>
                        <dd class="text-sm font-medium text-gray-900 flex items-center gap-2">
                            @if ($order->agent)
                                <img class="h-6 w-6 rounded-full border border-gray-100"
                                    src="{{ $order->agent->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($order->agent->name) }}"
                                    alt="">
                                <span class="font-semibold">{{ $order->agent->name }}</span>
                            @else
                                <span class="text-gray-400 italic text-sm">{{ __('Unassigned') }}</span>
                            @endif
                        </dd>
                    </div>
                    <div class="pt-4 border-t border-gray-100">
                        <div class="text-xs text-gray-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ __('Last Updated:') }} <span
                                class="text-gray-500 font-medium">{{ $order->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <button wire:click="delete" wire:confirm="{{ __('Are you sure you want to delete this order?') }}"
                class="w-full py-2.5 text-center text-sm text-red-600 hover:text-red-700 font-medium bg-red-50 hover:bg-red-100 rounded-lg transition duration-150 border border-red-100 shadow-sm flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                {{ __('Delete Order') }}
            </button>
        </div>
    </div>
</div>
