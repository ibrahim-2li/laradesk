<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ in_array(app()->getLocale(), ['ar', 'he', 'fa']) ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-sans antialiased text-gray-900 bg-gray-100 relative min-h-screen bg-cover bg-center"
    style="background-image: url('{{ \App\Support\Base::background() }}');">
    <div class="absolute top-4 right-4 flex gap-2">
        <a href="{{ route('locale.change', 'en') }}"
            class="text-xs font-semibold {{ app()->getLocale() == 'en' ? 'text-blue-600' : 'text-gray-400 hover:text-gray-600' }}">EN</a>
        <span class="text-gray-300">|</span>
        <a href="{{ route('locale.change', 'ar') }}"
            class="text-xs font-semibold {{ app()->getLocale() == 'ar' ? 'text-blue-600' : 'text-gray-400 hover:text-gray-600' }}">AR</a>
    </div>


    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">


        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div class="mb-8 flex justify-center items-center">
                <a href="/">
                    <x-logo textClass="text-gray-700" />
                </a>
            </div>
            {{ $slot }}
        </div>
    </div>

    @livewireScripts
</body>

</html>
