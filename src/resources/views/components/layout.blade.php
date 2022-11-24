@props(['title', 'description', 'head', 'author', 'copyright', 'publisher', 'viewport', 'ogTitle', 'ogDescription', 'twitterCard', 'themeColor', 'canonical'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="{{ $viewport ?? 'width=device-width, initial-scale=1' }}">
    <meta name="author" content="{{ $author ?? '' }}">
    <meta name="copyright" content="{{ $copyright ?? '' }}">
    <meta name="publisher" content="{{ $publisher ?? '' }}">
    <meta name="title" property="title" content="{{ $title ?? '' }}">
    <meta name="description" content="{{ $description ?? '' }}">
    <meta name="og:title" property="og:title" content="{{ $ogTitle ?? ($title ?? '') }}">
    <meta name="og:description" property="og:description" content="{{ $ogDescription ?? ($description ?? '') }}">
    <meta name="twitter:card" content="{{ $twitterCard ?? ($title ?? '') }}">
    <meta name="color-scheme" content="dark only">
    <meta name="theme-color" content="{{ $themeColor ?? '#ffffff' }}">
    <meta name="canonical" href="{{ $canonical ?? '/' }}">
    <title>{{ $title ?? '' }}</title>
    <link href="{{ mix('/css/site.css') }}" rel="stylesheet">
    <link rel="icon" type="image/png" href="/favicon.png" />
    <livewire:styles />
    @stack('styles')
    @isset($head)
        {!! $head !!}
    @endisset
    @foreach (IyiCode\App\Services\Layout::getHead() as $value)
        {!! $value !!}
    @endforeach
</head>

<body
    {{ $attributes->merge(['class' => ' text-black text-base bg-white antialiased no-scroll-bar scroll-smooth overflow-y-scroll']) }}>
    @livewireScripts
    @stack('scripts')
    {{ $slot }}
    <script src="{{ mix('/js/site.js') }}"></script>
</body>

</html>
