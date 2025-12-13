@props(['divPadding' => 'px-4', 'textClass' => 'text-gray-200'])

<div class="{{ $divPadding }} flex items-center">
    <img src="{{ \App\Support\Base::icon() }}" alt="icon" class="h-8 w-auto mr-4" />
    <div class="{{ $textClass }} inline-block text-xl font-semibold uppercase">{{ config('app.name') }}</div>
</div>
