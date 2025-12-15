@component('layouts.auth')
    <div class="text-center">
        <h1 class="text-9xl font-bold text-gray-200">404</h1>
        <h2 class="text-2xl font-bold text-gray-800 mt-4">{{ __('Whoops! Looks like you got lost') }}</h2>
        <p class="text-gray-600 mt-2">{{ __('We couldn\'t find what you were looking for') }}</p>

        <div class="mt-8">
            <a href="{{ route('dashboard.home') }}" class="text-blue-600 hover:text-blue-800 font-medium hover:underline">
                &larr; {{ __('Go home') }}
            </a>
        </div>
    </div>
@endcomponent
