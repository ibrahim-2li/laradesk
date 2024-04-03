@extends('installer::install')

@section('step')
    <div class="bg-green-100 border-l-4 border-green-500 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm leading-5 text-green-700">
                    The application has been installed successfully
                </p>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div>
            <p class="pb-3 text-gray-800">
                <b>Website address</b>
                <br>
                Your website is located at this URL
            </p>
            <p class="pb-3">
                <a class="text-blue-500 hover:underline" href="{{ $path }}">{{ $path }}</a>
            </p>
        </div>
        <div>
            <p class="pb-3 text-gray-800">
                <b>Administration Area</b>
                <br>
                Use the following link to log into the administration area:
            </p>
            <p class="pb-3">
                <a class="text-blue-500 hover:underline" href="{{ $path }}/auth/login">{{ $path }}/auth/login</a>
            </p>
            <p class="pb-3 text-gray-800">
                Email: <b>admin@admin.com</b>
                <br>
                Password: <b>12345678</b>
            </p>
        </div>
    </div>
    <p class="pb-3 text-gray-800">
        <b>Support and questions</b>
        <br>
        If you need support, please send me an email using the contact form on my envato user page. I usually respond to support requests within 24 hours so please feel free to contact me with
        problems of any kind or even simple questions, I don’t mind responding.
        <br>
        <a class="text-blue-500 hover:underline" href="https://codecanyon.net/user/dacoto" target="_blank" rel="nofollow">https://codecanyon.net/user/dacoto</a>
    </p>
@endsection
