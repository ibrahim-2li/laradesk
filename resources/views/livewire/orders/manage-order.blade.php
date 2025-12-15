<div>
    <div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-start gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span
                    class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-200">#{{ $order->id }}</span>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ $order->subject }}</h1>
            </div>
            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                <span class="flex items-center gap-1">
                    <span
                        class="w-2 h-2 rounded-full {{ optional($order->orderStatus)->name == 'Open' ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                    {{ $order->orderStatus->name ?? '-' }}
                </span>
                <span class="flex items-center gap-1 border-l border-gray-300 pl-4">
                    {{ __('Priority:') }} <span
                        class="font-medium text-gray-700">{{ $order->priority->name ?? '-' }}</span>
                </span>
                <span class="flex items-center gap-1 border-l border-gray-300 pl-4">
                    {{ __('Branch:') }} <span
                        class="font-medium text-gray-700">{{ $order->branches->name ?? '-' }}</span>
                </span>
            </div>
        </div>
        <a href="{{ route('orders.list') }}"
            class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center gap-1 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            {{ __('Back to Orders') }}
        </a>
    </div>

    <!-- Replies -->
    <div class="space-y-6 mb-8">
        @foreach ($replies as $reply)
            <div class="flex {{ $reply->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                <div
                    class="flex flex-col {{ $reply->user_id === auth()->id() ? 'items-end' : 'items-start' }} max-w-[85%] md:max-w-[70%]">
                    <div
                        class="px-5 py-3.5 rounded-2xl shadow-sm text-sm border {{ $reply->user_id === auth()->id() ? 'bg-blue-600 text-white rounded-tr-none border-blue-600' : 'bg-white text-gray-800 rounded-tl-none border-gray-100' }}">
                        <div
                            class="prose prose-sm prose-invert {{ $reply->user_id === auth()->id() ? 'text-blue-50' : 'text-gray-700' }}">
                            {!! nl2br(e($reply->body)) !!}
                        </div>
                    </div>
                    <div class="mt-1 flex items-center gap-2 text-xs text-gray-400">
                        <span
                            class="font-medium {{ $reply->user_id === auth()->id() ? 'text-blue-600' : 'text-gray-600' }}">{{ $reply->user->name }}</span>
                        <span>&bull;</span>
                        <span>{{ $reply->created_at->format('M d, g:i a') }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Reply Area -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Post a Reply') }}</h3>

        @if (session()->has('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form wire:submit.prevent="postReply">
            <div class="mb-4">
                <textarea wire:model="replyMessage" rows="4"
                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm placeholder-gray-400"
                    placeholder="{{ __('Type your reply here...') }}" required></textarea>
                @error('replyMessage')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>
            <div class="flex justify-end">
                <button type="submit"
                    class="px-5 py-2.5 bg-blue-600 border border-transparent rounded-lg text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out shadow-sm">
                    {{ __('Send Reply') }}
                </button>
            </div>
        </form>
    </div>
</div>
