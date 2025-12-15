<div>
    <div class="bg-white rounded-[4px] shadow-sm border border-gray-100 mt-20">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-700">{{ __('Orders') }}</h2>

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
                <a href="{{ route('dashboard.orders.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg text-sm transition-colors shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('New') }}
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider cursor-pointer hover:text-gray-600 group"
                            wire:click="sortBy('subject')">
                            {{ __('Subject') }}
                            <span
                                class="inline-block ml-1 opacity-0 group-hover:opacity-100 transition-opacity">↓</span>
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider cursor-pointer hover:text-gray-600 group"
                            wire:click="sortBy('id')">
                            {{ __('ID') }}
                            <span
                                class="inline-block ml-1 opacity-0 group-hover:opacity-100 transition-opacity">↓</span>
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">
                            {{ __('Status') }}</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">
                            {{ __('Priority') }}</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider cursor-pointer hover:text-gray-600 group"
                            wire:click="sortBy('created_at')">
                            {{ __('Created') }}
                            <span
                                class="inline-block ml-1 opacity-0 group-hover:opacity-100 transition-opacity">↓</span>
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">
                            {{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span
                                        class="text-sm font-semibold text-gray-700 line-clamp-1 group-hover:text-blue-600 transition-colors">{{ $order->subject }}</span>
                                    <span
                                        class="text-xs text-gray-500">{{ $order->user->name ?? __('Unknown User') }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="text-xs font-mono text-gray-500 bg-gray-100 px-2 py-1 rounded">#{{ $order->id }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="bg-blue-50 text-blue-700 py-1 px-3 rounded-[4px] text-xs font-medium border border-blue-100">
                                    {{ $order->orderStatus->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    @if (isset($order->priority->color))
                                        <div class="h-2 w-2 rounded-full"
                                            style="background-color: {{ $order->priority->color }}"></div>
                                    @endif
                                    <span class="text-sm text-gray-600">{{ $order->priority->name ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $order->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <a href="{{ route('dashboard.orders.manage', $order) }}"
                                    class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors">
                                    {{ __('Manage') }}
                                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                    <p>{{ __('No orders found') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100">
            {{ $orders->links() }}
        </div>
    </div>
</div>
