<div>
    <h1 class="text-2xl font-bold text-gray-800 mb-6">{{ __('Dashboard') }}</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Open Orders -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="mb-2 text-sm font-medium text-gray-600">{{ __('Open Orders') }}</p>
                    <p class="text-3xl font-semibold text-gray-700">{{ $stats['open_orders'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="mb-2 text-sm font-medium text-gray-600">{{ __('Pending Orders') }}</p>
                    <p class="text-3xl font-semibold text-gray-700">{{ $stats['pending_orders'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Sent Orders -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-500">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="mb-2 text-sm font-medium text-gray-600">{{ __('Delivered Orders') }}</p>
                    <p class="text-3xl font-semibold text-gray-700">{{ $stats['sended_orders'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- All Orders -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-gray-100 text-gray-500">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="mb-2 text-sm font-medium text-gray-600">{{ __('All Orders') }}</p>
                    <p class="text-3xl font-semibold text-gray-700">{{ $stats['all_orders'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section (To be implemented using a charting library, placeholder for now) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">{{ __('Registered Users (Last 12 Months)') }}</h3>
            <div class="h-64 flex items-center justify-center bg-gray-50 text-gray-400">
                [Chart Placeholder]
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">{{ __('Orders (Last 12 Months)') }}</h3>
            <div class="h-64 flex items-center justify-center bg-gray-50 text-gray-400">
                [Chart Placeholder]
            </div>
        </div>
    </div>
</div>
