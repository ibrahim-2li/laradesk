<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ in_array(app()->getLocale(), ['ar', 'he', 'fa']) ? 'rtl' : 'ltr' }}" class="h-full bg-gray-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @livewireStyles

    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="h-full font-sans antialiased text-gray-600" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden bg-gray-50">

        <!-- Sidebar -->
        <aside
            class="flex-shrink-0 w-72 bg-white border-r border-gray-100 hidden md:flex flex-col transition-all duration-300">
            <!-- Logo area -->
            <div class="flex items-center justify-center h-20 border-b border-gray-100">
                <a href="{{ route('dashboard.home') }}" class="group flex items-center gap-3 px-6">
                    <x-logo textClass="text-gray-800 tracking-tight" />
                </a>
            </div>

            <!-- Scrollable Nav -->
            <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1">
                <div class="mb-6">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Main</p>
                    <a href="{{ route('dashboard.home') }}"
                        class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard.home') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('dashboard.home') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        {{ __('Dashboard') }}
                    </a>
                </div>

                @if (auth()->user()->userRole && auth()->user()->userRole->checkDashboardAccess())
                    <div class="mb-6">
                        <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Management</p>

                        <a href="{{ route('dashboard.tickets.list') }}"
                            class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard.tickets.list') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('dashboard.tickets.list') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                            {{ __('Tickets') }}
                        </a>

                        <a href="{{ route('dashboard.orders.list') }}"
                            class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard.orders.list') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('dashboard.orders.list') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            {{ __('Orders') }}
                        </a>

                        <a href="{{ route('dashboard.canned-replies.list') }}"
                            class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard.canned-replies.list') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('dashboard.canned-replies.list') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                            {{ __('Canned Replies') }}
                        </a>
                    </div>
                @else
                    <div class="mb-6">
                        <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">My Desk</p>
                        <a href="{{ route('tickets.list') }}"
                            class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('tickets.list') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('tickets.list') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                            {{ __('My Tickets') }}
                        </a>
                        <a href="{{ route('orders.list') }}"
                            class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('orders.list') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('orders.list') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            {{ __('My Orders') }}
                        </a>
                    </div>
                @endif

                @if (auth()->user() && auth()->user()->role_id === 1)
                    <div>
                        <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                            {{ __('Admin') }}</p>

                        <a href="{{ route('admin.users.list') }}"
                            class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.users.list') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span class="truncate">{{ __('Users') }}</span>
                        </a>

                        <a href="{{ route('admin.user-roles.list') }}"
                            class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.user-roles.list') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span class="truncate">{{ __('User Roles') }}</span>
                        </a>

                        <a href="{{ route('admin.departments.list') }}"
                            class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.departments.list') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span class="truncate">{{ __('Departments') }}</span>
                        </a>

                        <a href="{{ route('admin.branches.list') }}"
                            class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.branches.list') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span class="truncate">{{ __('Branches') }}</span>
                        </a>

                        <a href="{{ route('admin.settings') }}"
                            class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.settings') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span class="truncate">{{ __('Settings') }}</span>
                        </a>

                        <div x-data="{ expanded: false }" class="mt-2">
                            <button @click="expanded = !expanded"
                                class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 focus:outline-none">
                                <span>{{ __('More...') }}</span>
                                <svg class="w-4 h-4 transition-transform transform" :class="{ 'rotate-180': expanded }"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div x-show="expanded" x-collapse class="pl-4 mt-1 space-y-1">
                                <a href="{{ route('admin.labels.list') }}"
                                    class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">{{ __('Labels') }}</a>
                                <a href="{{ route('admin.statuses.list') }}"
                                    class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">{{ __('Statuses') }}</a>
                                <a href="{{ route('admin.priorities.list') }}"
                                    class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">{{ __('Priorities') }}</a>
                                <a href="{{ route('admin.languages.list') }}"
                                    class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">{{ __('Languages') }}</a>
                                <a href="{{ route('admin.stocks.list') }}"
                                    class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">{{ __('Stock') }}</a>
                                <a href="{{ route('admin.brands.list') }}"
                                    class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">{{ __('Brands') }}</a>
                            </div>
                        </div>

                    </div>
                @endif
            </nav>

            <!-- Language Switcher -->
            <div class="px-6 py-4 border-t border-gray-100 flex gap-2">
                <a href="{{ route('locale.change', 'en') }}"
                    class="text-xs font-semibold {{ app()->getLocale() == 'en' ? 'text-blue-600' : 'text-gray-400 hover:text-gray-600' }}">EN</a>
                <span class="text-gray-200">|</span>
                <a href="{{ route('locale.change', 'es') }}"
                    class="text-xs font-semibold {{ app()->getLocale() == 'es' ? 'text-blue-600' : 'text-gray-400 hover:text-gray-600' }}">ES</a>
                <span class="text-gray-200">|</span>
                <a href="{{ route('locale.change', 'fr') }}"
                    class="text-xs font-semibold {{ app()->getLocale() == 'fr' ? 'text-blue-600' : 'text-gray-400 hover:text-gray-600' }}">FR</a>
                <span class="text-gray-200">|</span>
                <a href="{{ route('locale.change', 'de') }}"
                    class="text-xs font-semibold {{ app()->getLocale() == 'de' ? 'text-blue-600' : 'text-gray-400 hover:text-gray-600' }}">DE</a>
                <span class="text-gray-200">|</span>
                <a href="{{ route('locale.change', 'ar') }}"
                    class="text-xs font-semibold {{ app()->getLocale() == 'ar' ? 'text-blue-600' : 'text-gray-400 hover:text-gray-600' }}">AR</a>
            </div>

            <!-- User Menu -->
            <div class="border-t border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <img class="h-9 w-9 rounded-full object-cover border border-gray-200"
                            src="{{ auth()->user()->getAvatar() }}" alt="{{ auth()->user()->name }}">
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</p>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="text-xs font-medium text-gray-500 hover:text-gray-700 transition">
                                {{ __('Sign out') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden relative">

            <!-- Mobile Header -->
            <header class="flex items-center justify-between p-4 bg-white border-b border-gray-200 md:hidden z-20">
                <a href="{{ route('dashboard.home') }}">
                    <x-logo textClass="text-gray-800" />
                </a>
                <button @click="sidebarOpen = !sidebarOpen"
                    class="text-gray-500 hover:text-gray-700 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </header>

            <!-- Mobile Sidebar Overlay -->
            <div x-show="sidebarOpen" class="fixed inset-0 z-30 flex md:hidden" style="display: none;">
                <div @click="sidebarOpen = false" x-show="sidebarOpen"
                    x-transition:enter="transition-opacity ease-linear duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity ease-linear duration-300"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-gray-600 bg-opacity-75" aria-hidden="true"></div>

                <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform"
                    x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                    x-transition:leave="transition ease-in-out duration-300 transform"
                    x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
                    class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
                    <div class="px-6 py-4 flex items-center justify-between h-16 border-b border-gray-100">
                        <x-logo textClass="text-gray-800" />
                        <button @click="sidebarOpen = false" class="text-gray-500">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <!-- Reuse desktop nav structure broadly -->
                    <div class="flex-1 overflow-y-auto p-4 space-y-2">
                        <a href="{{ route('dashboard.home') }}"
                            class="block px-4 py-2 text-base font-medium text-gray-700 rounded-lg hover:bg-gray-50">{{ __('Dashboard') }}</a>
                        @if (auth()->user()->userRole && auth()->user()->userRole->checkDashboardAccess())
                            <a href="{{ route('dashboard.tickets.list') }}"
                                class="block px-4 py-2 text-base font-medium text-gray-700 rounded-lg hover:bg-gray-50">{{ __('Tickets') }}</a>
                            <a href="{{ route('dashboard.orders.list') }}"
                                class="block px-4 py-2 text-base font-medium text-gray-700 rounded-lg hover:bg-gray-50">{{ __('Orders') }}</a>
                        @else
                            <a href="{{ route('tickets.list') }}"
                                class="block px-4 py-2 text-base font-medium text-gray-700 rounded-lg hover:bg-gray-50">{{ __('My Tickets') }}</a>
                            <a href="{{ route('orders.list') }}"
                                class="block px-4 py-2 text-base font-medium text-gray-700 rounded-lg hover:bg-gray-50">{{ __('My Orders') }}</a>
                        @endif

                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg font-medium">
                                    {{ __('Sign out') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
                <div class="container mx-auto px-6 py-8">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    @livewireScripts
</body>

</html>
