<div>
    <div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-start gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span
                    class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-200">#{{ $order->id }}</span>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ $order->subject }}</h1>
            </div>
            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                <span class="flex items-center gap-2">
                    <img class="h-5 w-5 rounded-full"
                        src="{{ $order->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($order->user->name) }}"
                        alt="">
                    <span>{{ $order->user->name }}</span>
                </span>
                <span class="text-gray-300">&bull;</span>
                <span>{{ $order->created_at->format('M d, Y H:i') }}</span>
            </div>
        </div>
        <div class="flex items-center gap-3">
            class="px-3 py-1 rounded-full text-sm font-semibold {{ optional($order->orderStatus)->name == 'Open' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-gray-100 text-gray-800 border border-gray-200' }}">
            {{ $order->orderStatus->name ?? $order->orders_status_id }}
            </span>
            <a href="{{ route('dashboard.orders.list') }}"
                class="text-sm font-medium text-gray-500 hover:text-gray-700 transition">
                {{ __('Close') }}
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Conversation Area -->
        <div class="lg:col-span-3 space-y-8">
            <!-- Order Items -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-sm font-bold text-gray-900">{{ __('Order Items') }}</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Item') }}</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Brand') }}</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Quantity') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($order->items as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $item->stock?->name ?? __('Unknown Item') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->stock?->brands?->name ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->quantity }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3"
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        {{ __('No items found in this order.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="space-y-6">
                @foreach ($replies as $reply)
                    <div class="flex {{ $reply->user_id === $order->user_id ? 'justify-start' : 'justify-end' }}">
                        <div
                            class="flex flex-col {{ $reply->user_id === $order->user_id ? 'items-start' : 'items-end' }} max-w-[85%]">
                            <div class="flex items-center gap-2 mb-1 px-1">
                                <span class="text-xs font-medium text-gray-500">{{ $reply->user->name }}</span>
                                @if ($reply->user_id !== $order->user_id)
                                    <span
                                        class="text-[10px] uppercase font-bold text-blue-600 bg-blue-50 px-1.5 rounded">{{ __('Staff') }}</span>
                                @endif
                            </div>
                            <div
                                class="px-5 py-3.5 rounded-2xl shadow-sm text-sm border {{ $reply->user_id === $order->user_id ? 'bg-white text-gray-800 rounded-tl-none border-gray-100' : 'bg-blue-600 text-white rounded-tr-none border-blue-600' }}">
                                <div
                                    class="prose prose-sm prose-invert {{ $reply->user_id === $order->user_id ? 'text-gray-700' : 'text-blue-50' }}">
                                    {!! nl2br(e($reply->body)) !!}
                                </div>
                            </div>
                            <div class="mt-1 px-1 text-xs text-gray-400">
                                {{ $reply->created_at->format('M d, g:i a') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Reply Form -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Post a Reply') }}</h3>
                <form wire:submit.prevent="reply">
                    <div class="mb-4">
                        <textarea wire:model="body" rows="4"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm placeholder-gray-400"
                            placeholder="{{ __('Type your reply here...') }}" required></textarea>
                        @error('body')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between gap-4 border-t border-gray-100 pt-4">
                        <div class="flex-1 max-w-xs">
                            <label
                                class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">{{ __('Update Status') }}</label>
                            <select wire:model="orders_status_id"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @foreach ($statuses as $status)
                                    <option value="{{ $status->id }}">{{ $status->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit"
                            class="px-5 py-2.5 bg-blue-600 border border-transparent rounded-lg text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out shadow-sm self-end">
                            {{ __('Send Reply') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-sm font-bold text-gray-900">{{ __('Order Details') }}</h3>
                </div>
                <div class="p-4 space-y-4">
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">
                            {{ __('Department') }}</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $order->branches->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">
                            {{ __('Priority') }}</dt>
                        <dd class="text-sm font-medium text-gray-900 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                            {{ $order->priority->name ?? '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">
                            {{ __('Assigned Agent') }}</dt>
                        <dd class="text-sm font-medium text-gray-900 flex items-center gap-2">
                            @if ($order->agent)
                                <img class="h-5 w-5 rounded-full"
                                    src="{{ $order->agent->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($order->agent->name) }}"
                                    alt="">
                                {{ $order->agent->name }}
                            @else
                                <span class="text-gray-400 italic">{{ __('Unassigned') }}</span>
                            @endif
                        </dd>
                    </div>
                    <div class="pt-4 border-t border-gray-100">
                        <div class="text-xs text-gray-400">
                            {{ __('Last Updated:') }} <br>
                            {{ $order->updated_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            </div>

            <a href="#"
                class="block w-full py-2 text-center text-sm text-red-600 hover:text-red-700 font-medium bg-red-50 hover:bg-red-100 rounded-lg transition duration-150">
                {{ __('Delete Order') }}
            </a>
        </div>
    </div>
</div>
