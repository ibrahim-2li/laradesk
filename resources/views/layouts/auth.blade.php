<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ in_array(app()->getLocale(), ['ar', 'he', 'fa']) ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-sans antialiased text-gray-900 bg-white">
    <div class="min-h-screen flex w-full">
        <!-- Brand Section -->
        <div
            class="hidden lg:flex w-1/2 bg-brand-600 relative overflow-hidden items-center justify-center text-white p-12">
            <!-- Background Image -->
            <div class="absolute inset-0 bg-cover bg-center z-0"
                style="background-image: url('{{ \App\Support\Base::background() }}');">
            </div>
            <!-- Blue Overlay -->
            <div class="absolute inset-0 bg-gradient-to-br from-brand-600/5 to-blue-800/5 z-10 mix-blend-multiply">
            </div>
            <div class="absolute inset-0 bg-brand-600/60 z-10"></div>

            <div class="relative z-20 text-center max-w-xl">
                <h1 class="text-6xl font-extrabold mb-8 tracking-tight">{{ __('Sawaed Al-Riyadh') }}</h1>
                <p class="text-2xl font-light opacity-90 leading-relaxed">
                    {{ __('Integrated platform connecting commercial brands with professional content creators to execute effective marketing campaigns.') }}
                </p>
            </div>

            <!-- Decorative Blobs -->
            <div
                class="absolute top-0 left-0 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-blue-400 rounded-full mix-blend-screen filter blur-3xl opacity-30 z-10">
            </div>
            <div
                class="absolute bottom-0 right-0 translate-x-1/2 translate-y-1/2 w-[500px] h-[500px] bg-purple-500 rounded-full mix-blend-screen filter blur-3xl opacity-30 z-10">
            </div>
        </div>

        <!-- Form Section -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-6 sm:p-12 lg:p-24 relative bg-white">
            <!-- Language Switcher -->
            <div class="absolute top-6 right-6 lg:top-10 lg:right-12 flex gap-4 text-sm font-bold z-30">
                <a href="{{ route('locale.change', 'en') }}"
                    class="{{ app()->getLocale() == 'en' ? 'text-brand-600 border-b-2 border-brand-600 pb-0.5' : 'text-gray-400 hover:text-gray-600 transition-colors' }}">English</a>
                <span class="text-gray-200 font-light">|</span>
                <a href="{{ route('locale.change', 'ar') }}"
                    class="{{ app()->getLocale() == 'ar' ? 'text-brand-600 border-b-2 border-brand-600 pb-0.5' : 'text-gray-400 hover:text-gray-600 transition-colors' }}">العربية</a>
            </div>

            <div class="w-full max-w-[420px]">
                <!-- Logo Mobile only -->
                <div class="lg:hidden mb-12 text-center">
                    <h2 class="text-3xl font-extrabold text-brand-600">{{ __('Sawaed Al-Riyadh') }}</h2>
                </div>

                {{ $slot }}
            </div>

            <!-- Footer Links -->
            <div class="absolute bottom-6 w-full text-center lg:text-start lg:px-24">
                <!-- Optional footer content -->
            </div>
        </div>
    </div>

    @livewireScripts
</body>

</html>
