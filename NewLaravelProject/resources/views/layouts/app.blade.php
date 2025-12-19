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
        <title>{{ $meta['title'] ?? config('app.name', 'El Alma de las Fiestas') }}</title>
        <meta name="title" content="{{ $meta['title'] ?? config('app.name', 'El Alma de las Fiestas') }}">
        <meta name="description" content="{{ $meta['description'] ?? 'Descubre las mejores festividades y eventos tradicionales de España' }}">
        <meta name="keywords" content="{{ $meta['keywords'] ?? 'festividades españa, eventos tradicionales, fiestas populares' }}">
        <meta name="author" content="El Alma de las Fiestas">
        <meta name="robots" content="index, follow">
        <meta name="language" content="Spanish">
        <meta name="revisit-after" content="7 days">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="{{ $meta['type'] ?? 'website' }}">
        <meta property="og:url" content="{{ $meta['url'] ?? url()->current() }}">
        <meta property="og:title" content="{{ $meta['title'] ?? config('app.name', 'El Alma de las Fiestas') }}">
        <meta property="og:description" content="{{ $meta['description'] ?? 'Descubre las mejores festividades y eventos tradicionales de España' }}">
        <meta property="og:image" content="{{ $meta['image'] ?? asset('favicon.ico') }}">
        <meta property="og:locale" content="{{ $meta['locale'] ?? 'es_ES' }}">
        <meta property="og:site_name" content="{{ config('app.name', 'El Alma de las Fiestas') }}">

        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:url" content="{{ $meta['url'] ?? url()->current() }}">
        <meta name="twitter:title" content="{{ $meta['title'] ?? config('app.name', 'El Alma de las Fiestas') }}">
        <meta name="twitter:description" content="{{ $meta['description'] ?? 'Descubre las mejores festividades y eventos tradicionales de España' }}">
        <meta name="twitter:image" content="{{ $meta['image'] ?? asset('favicon.ico') }}">

        <!-- Canonical URL -->
        <link rel="canonical" href="{{ $meta['url'] ?? url()->current() }}">

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Lobster&display=swap" rel="stylesheet">
        
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

        <!-- Schema.org JSON-LD -->
        @if(isset($schema))
            <script type="application/ld+json">
                {!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
            </script>
        @endif

        <!-- Scripts -->
        @php
            $manifestPath = public_path('build/manifest.json');
            if (file_exists($manifestPath)) {
                $manifest = json_decode(file_get_contents($manifestPath), true);
                $cssFile = $manifest['resources/css/app.css']['file'] ?? null;
                $jsFile = $manifest['resources/js/app.js']['file'] ?? null;
            }
        @endphp
        
        @if(isset($cssFile))
            <link rel="stylesheet" href="{{ asset('build/' . $cssFile) }}">
        @else
            @vite(['resources/css/app.css'])
        @endif
        
        @if(isset($jsFile))
            <script type="module" src="{{ asset('build/' . $jsFile) }}"></script>
        @else
            @vite(['resources/js/app.js'])
        @endif
        
        <!-- Google AdSense Script -->
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5837712015612104" crossorigin="anonymous"></script>
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
        
        <!-- Footer -->
        @include('partials.footer')
        
        <!-- Stack for additional scripts (e.g., Google Maps) -->
        @stack('scripts')
    </body>
</html>
