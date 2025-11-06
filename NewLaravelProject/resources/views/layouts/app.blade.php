<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php
            $meta = $meta ?? \App\Services\SeoService::generateMetaTags([]);
        @endphp

        <!-- Primary Meta Tags -->
        <title>{{ $meta['title'] ?? config('app.name', 'FestiTowns') }}</title>
        <meta name="title" content="{{ $meta['title'] ?? config('app.name', 'FestiTowns') }}">
        <meta name="description" content="{{ $meta['description'] ?? 'Descubre las mejores festividades y eventos tradicionales de España' }}">
        <meta name="keywords" content="{{ $meta['keywords'] ?? 'festividades españa, eventos tradicionales, fiestas populares' }}">
        <meta name="author" content="FestiTowns">
        <meta name="robots" content="index, follow">
        <meta name="language" content="Spanish">
        <meta name="revisit-after" content="7 days">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="{{ $meta['type'] ?? 'website' }}">
        <meta property="og:url" content="{{ $meta['url'] ?? url()->current() }}">
        <meta property="og:title" content="{{ $meta['title'] ?? config('app.name', 'FestiTowns') }}">
        <meta property="og:description" content="{{ $meta['description'] ?? 'Descubre las mejores festividades y eventos tradicionales de España' }}">
        <meta property="og:image" content="{{ $meta['image'] ?? asset('favicon.ico') }}">
        <meta property="og:locale" content="{{ $meta['locale'] ?? 'es_ES' }}">
        <meta property="og:site_name" content="{{ config('app.name', 'FestiTowns') }}">

        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:url" content="{{ $meta['url'] ?? url()->current() }}">
        <meta name="twitter:title" content="{{ $meta['title'] ?? config('app.name', 'FestiTowns') }}">
        <meta name="twitter:description" content="{{ $meta['description'] ?? 'Descubre las mejores festividades y eventos tradicionales de España' }}">
        <meta name="twitter:image" content="{{ $meta['image'] ?? asset('favicon.ico') }}">

        <!-- Canonical URL -->
        <link rel="canonical" href="{{ $meta['url'] ?? url()->current() }}">

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

        <!-- Schema.org JSON-LD -->
        @if(isset($schema))
            <script type="application/ld+json">
                {!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
            </script>
        @endif

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <div class="bg-light py-4">
                <div class="container">
                    {{ $header }}
                </div>
            </div>
        @endisset

        <!-- Page Content -->
        <main class="py-4">
            {{ $slot }}
        </main>
    </body>
</html>
