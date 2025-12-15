<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ in_array(app()->getLocale(), ['ar', 'he', 'fa']) ? 'rtl' : 'ltr' }}" class="h-full bg-gray-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&display=swap" rel="stylesheet">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @livewireStyles

    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Almarai', sans-serif;
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
                        class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard.home') ? 'bg-brand-50 text-brand-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('dashboard.home') ? 'text-brand-500' : 'text-gray-400 group-hover:text-gray-500' }}"
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
                            class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard.tickets.list') ? 'bg-brand-50 text-brand-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('dashboard.tickets.list') ? 'text-brand-500' : 'text-gray-400 group-hover:text-gray-500' }}"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                            {{ __('Tickets') }}
                        </a>

                        <a href="{{ route('dashboard.orders.list') }}"
                            class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard.orders.list') ? 'bg-brand-50 text-brand-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('dashboard.orders.list') ? 'text-brand-500' : 'text-gray-400 group-hover:text-gray-500' }}"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            {{ __('Orders') }}
                        </a>

                        <a href="{{ route('dashboard.canned-replies.list') }}"
                            class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard.canned-replies.list') ? 'bg-brand-50 text-brand-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('dashboard.canned-replies.list') ? 'text-brand-500' : 'text-gray-400 group-hover:text-gray-500' }}"
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
                            class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('tickets.list') ? 'bg-brand-50 text-brand-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('tickets.list') ? 'text-brand-500' : 'text-gray-400 group-hover:text-gray-500' }}"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                            {{ __('My Tickets') }}
                        </a>
                        <a href="{{ route('orders.list') }}"
                            class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('orders.list') ? 'bg-brand-50 text-brand-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('orders.list') ? 'text-brand-500' : 'text-gray-400 group-hover:text-gray-500' }}"
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
                            class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.users.list') ? 'bg-brand-50 text-brand-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('admin.users.list') ? 'text-brand-500' : 'text-gray-400 group-hover:text-gray-500' }}"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span class="truncate">{{ __('Users') }}</span>
                        </a>

                        <a href="{{ route('admin.user-roles.list') }}"
                            class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.user-roles.list') ? 'bg-brand-50 text-brand-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('admin.user-roles.list') ? 'text-brand-500' : 'text-gray-400 group-hover:text-gray-500' }}"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11.536 19.464a4.919 4.919 0 01-1.414.949l-.01.01a5.006 5.006 0 01-2.484.606 5.003 5.003 0 01-3.665-8.337l1.066-1.066a2 2 0 012.828 0L9.414 13.07a2 2 0 010 2.829 2 2 0 01-2.829 0l-1.066-1.067a5.006 5.006 0 015.753-7.513 5.003 5.003 0 016.326 2.083z" />
                            </svg>
                            <span class="truncate">{{ __('User Roles') }}</span>
                        </a>

                        <a href="{{ route('admin.departments.list') }}"
                            class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.departments.list') ? 'bg-brand-50 text-brand-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('admin.departments.list') ? 'text-brand-500' : 'text-gray-400 group-hover:text-gray-500' }}"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span class="truncate">{{ __('Departments') }}</span>
                        </a>

                        <a href="{{ route('admin.branches.list') }}"
                            class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.branches.list') ? 'bg-brand-50 text-brand-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('admin.branches.list') ? 'text-brand-500' : 'text-gray-400 group-hover:text-gray-500' }}"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="truncate">{{ __('Branches') }}</span>
                        </a>
                        <a href="{{ route('admin.stocks.list') }}"
                            class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.stocks.list') ? 'bg-brand-50 text-brand-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('admin.stocks.list') ? 'text-brand-500' : 'text-gray-400 group-hover:text-gray-500' }}"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <span class="truncate">{{ __('Stock') }}</span>
                        </a>

                        <a href="{{ route('admin.settings') }}"
                            class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.settings') ? 'bg-brand-50 text-brand-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('admin.settings') ? 'text-brand-500' : 'text-gray-400 group-hover:text-gray-500' }}"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="truncate">{{ __('Settings') }}</span>
                        </a>

                        <div x-data="{ expanded: false }" class="mt-2">
                            <button @click="expanded = !expanded"
                                class="flex items-center w-full px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 focus:outline-none transition-colors duration-200">
                                <svg class="mr-3 h-5 w-5 flex-shrink-0 text-gray-400 group-hover:text-gray-500"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                </svg>
                                <span class="flex-1 text-left">{{ __('System Attributes') }}</span>
                                <svg class="w-4 h-4 text-gray-400 transition-transform transform"
                                    :class="{ 'rotate-180': expanded }" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div x-show="expanded" x-collapse class="pl-4 mt-1 space-y-1">
                                <a href="{{ route('admin.labels.list') }}"
                                    class="group flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900 transition-colors duration-200">
                                    <svg class="mr-3 h-4 w-4 text-gray-400 group-hover:text-gray-500"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    {{ __('Labels') }}
                                </a>
                                <a href="{{ route('admin.statuses.list') }}"
                                    class="group flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900 transition-colors duration-200">
                                    <svg class="mr-3 h-4 w-4 text-gray-400 group-hover:text-gray-500"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ __('Statuses') }}
                                </a>
                                <a href="{{ route('admin.priorities.list') }}"
                                    class="group flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900 transition-colors duration-200">
                                    <svg class="mr-3 h-4 w-4 text-gray-400 group-hover:text-gray-500"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
                                    </svg>
                                    {{ __('Priorities') }}
                                </a>
                                <a href="{{ route('admin.languages.list') }}"
                                    class="group flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900 transition-colors duration-200">
                                    <svg class="mr-3 h-4 w-4 text-gray-400 group-hover:text-gray-500"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                                    </svg>
                                    {{ __('Languages') }}
                                </a>

                                <a href="{{ route('admin.brands.list') }}"
                                    class="group flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900 transition-colors duration-200">
                                    <svg class="mr-3 h-4 w-4 text-gray-400 group-hover:text-gray-500"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                    </svg>
                                    {{ __('Brands') }}
                                </a>
                            </div>
                        </div>

                    </div>
                @endif
            </nav>

            <!-- Language Switcher -->
            <div class="px-6 py-4 border-t border-gray-100 flex gap-2">
                <a href="{{ route('locale.change', 'en') }}"
                    class="text-xs font-semibold {{ app()->getLocale() == 'en' ? 'text-brand-600' : 'text-gray-400 hover:text-gray-600' }}">EN</a>
                <span class="text-gray-200">|</span>
                <a href="{{ route('locale.change', 'es') }}"
                    class="text-xs font-semibold {{ app()->getLocale() == 'es' ? 'text-brand-600' : 'text-gray-400 hover:text-gray-600' }}">ES</a>
                <span class="text-gray-200">|</span>
                <a href="{{ route('locale.change', 'fr') }}"
                    class="text-xs font-semibold {{ app()->getLocale() == 'fr' ? 'text-brand-600' : 'text-gray-400 hover:text-gray-600' }}">FR</a>
                <span class="text-gray-200">|</span>
                <a href="{{ route('locale.change', 'de') }}"
                    class="text-xs font-semibold {{ app()->getLocale() == 'de' ? 'text-brand-600' : 'text-gray-400 hover:text-gray-600' }}">DE</a>
                <span class="text-gray-200">|</span>
                <a href="{{ route('locale.change', 'ar') }}"
                    class="text-xs font-semibold {{ app()->getLocale() == 'ar' ? 'text-brand-600' : 'text-gray-400 hover:text-gray-600' }}">AR</a>
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
                        <div class="mb-3">
                            <a href="{{ route('profile') }}"
                                class="text-sm font-medium text-gray-700 hover:text-brand-600 transition flex items-center gap-2 mb-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ __('My Profile') }}
                            </a>
                        </div>
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

    @stack('scripts')
    @livewireScripts
</body>

</html>
