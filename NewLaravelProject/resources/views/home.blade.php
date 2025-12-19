<x-app-layout>
    <style>
        /* Remove all padding from main on home page - override Bootstrap py-4 */
        body > main.py-4 {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }
        
        /* Remove any spacing between navbar and main */
        nav.navbar + main {
            margin-top: 0 !important;
        }
        
        /* Hero banner - no margin, adheres to navbar */
        .hero-banner {
            margin: 0 !important;
            padding: 0 !important;
            margin-top: 0 !important;
        }
        
        /* Container fluid - no spacing */
        .container-fluid.px-0 {
            margin: 0 !important;
            padding: 0 !important;
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }
        
        /* Results map section - no spacing */
        .results-map-section {
            margin: 0 !important;
            padding: 0 !important;
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }
        
        /* Map section - no spacing */
        .map-section {
            margin: 0 !important;
            padding: 0 !important;
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }
        
        /* Footer - remove top margin on home page (override Bootstrap mt-5) but add padding-top for spacing */
        body > footer.mt-5,
        body > footer {
            margin-top: 0 !important;
            padding-top: 1rem !important;
        }
        
        /* Ensure no gap between main and footer */
        main + footer {
            margin-top: 0 !important;
        }
        
        /* Remove any whitespace/spacing from navbar */
        nav.navbar {
            margin-bottom: 0 !important;
        }
    </style>
    
    <!-- Hero Section with Integrated Search -->
        <div class="hero-banner">
            <div class="hero-background" style="background-image: url('/storage/hero-1.png');"></div>
            <div class="hero-overlay"></div>
            <div class="hero-content">
    <div class="container">
                    <!-- Hero Tagline -->
                    <div class="hero-tagline text-center mb-4">
                        <h1 class="text-white fw-bold mb-3 hero-title brand-text" style="font-size: 2.5rem; text-shadow: 0 2px 10px rgba(0,0,0,0.3);">Descubre la magia de las festividades españolas</h1>
                        <p class="text-white mb-0 hero-subtitle" style="font-size: 1.125rem; opacity: 0.95;">Encuentra las mejores celebraciones y tradiciones en toda España</p>
        </div>

                    <div class="hero-search-container">
                        <div class="d-flex align-items-center gap-2 flex-wrap justify-content-center">
                            <div class="d-flex align-items-center bg-white rounded-pill shadow-lg flex-grow-1 hero-search-bar">
                        <!-- Search Text Field -->
                                <input type="text" id="map-search-query" class="form-control border-0 shadow-none flex-grow-1 hero-search-input" 
                                       placeholder="Nombre de la festividad">
                                
                                <!-- Province Dropdown -->
                                <select id="map-province-filter" class="form-select border-0 shadow-none hero-search-select">
                                    <option value="">Todas las provincias</option>
                            @foreach(config('provinces.provinces') as $province)
                                <option value="{{ $province }}">{{ $province }}</option>
                            @endforeach
                        </select>
                        
                        <!-- Divider -->
                                <div class="vr mx-1 hero-search-divider"></div>
                        
                        <!-- Search Button -->
                                <button id="map-search-btn" class="btn btn-primary rounded-pill px-3 py-1 fw-semibold hero-search-btn" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                    
                            <!-- Location Button -->
                            <button id="map-near-me-btn" class="btn btn-secondary rounded-pill px-4 py-2 shadow-sm fw-semibold text-nowrap hero-location-btn" type="button" style="background-color: #1FA4A9; color: white;">
                        <i class="bi bi-geo-alt me-2"></i>Cerca de mí
                    </button>
                </div>
            </div>
                </div>
                </div>
            </div>
            
        <div class="container-fluid px-0">
        <!-- Results and Map Section - Unified Layout -->
        <div id="map-search-section" class="results-map-section">
            <!-- Results Header Bar (Fixed at top) -->
            <div class="results-header-bar">
                <h6 class="text-center">
                    Próximas festividades: <span id="map-results-count">0</span>
                </h6>
            </div>
            
            <!-- Map Section (Full height) -->
            <div class="map-section">
                <div id="festivities-map"></div>
                
                <!-- Results Section (Overlay on map) -->
                <div id="map-results-container" class="results-section d-none">
                    <div class="results-scroll-area">
                        <div id="map-results-scroll-container" class="position-relative">
                        <!-- Left scroll button -->
                        <button id="scroll-left-btn" class="scroll-nav-btn scroll-nav-btn-left d-none d-md-block" type="button" aria-label="Scroll izquierda">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <!-- Right scroll button -->
                        <button id="scroll-right-btn" class="scroll-nav-btn scroll-nav-btn-right d-none d-md-block" type="button" aria-label="Scroll derecha">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                            <!-- Scrollable container -->
                            <div id="map-results-scroll" class="horizontal-scroll-wrapper">
                        <!-- Festivities cards will be loaded here -->
                    </div>
                    </div>
                </div>
                
                <div id="map-no-results" class="results-footer d-none">
                    <div class="d-flex align-items-center justify-content-center py-2 px-3">
                        <i class="bi bi-info-circle me-2 text-muted"></i>
                        <small class="text-muted">No se encontraron festividades. Intenta cambiar la provincia o mover el mapa.</small>
                    </div>
                </div>
                <div id="map-loading-more" class="results-footer text-center d-none">
                    <div class="py-2">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Cargando más...</span>
                        </div>
                    </div>
                </div>
                <div id="map-view-all" class="results-footer text-center d-none">
                    <div class="py-2 px-3">
                        <a id="map-view-all-link" href="#" class="text-decoration-none text-primary fw-semibold view-all-link">
                            Ver todas las festividades (<span id="map-total-count">0</span>) →
                        </a>
                    </div>
                </div>
            </div>
        </div>
            </div>
    </div>

    <style>
        /* Hero Banner with Background Image */
        .hero-banner {
            position: relative;
            height: 320px;
            overflow: hidden;
            margin: 0;
            padding: 0;
            background-color: #111827;
        }
        
        .hero-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.9;
        }
        
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(15, 23, 42, 0.1) 0%, rgba(15, 23, 42, 0.85) 65%, rgba(17, 24, 39, 0.95) 100%);
        }
        
        .hero-content {
            position: relative;
            height: 100%;
            display: flex;
            align-items: flex-end;
            z-index: 1;
        }
        
        .hero-search-container {
            width: 100%;
            padding: 0 1.5rem 2rem;
        }
        
        .hero-search-bar {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.98) !important;
            min-width: 300px;
            max-width: 900px;
            padding: 0.5rem 0.75rem;
            border-radius: 50px !important;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }
        
        @media (max-width: 576px) {
            .hero-search-bar {
                min-width: auto;
            }
        }
        
        .hero-title {
            font-size: 2.5rem;
            letter-spacing: 0.5px;
            font-weight: 700;
        }
        
        .hero-subtitle {
            font-size: 1.125rem;
            font-weight: 400;
        }
        
        .hero-search-input {
            background: transparent;
            font-size: 0.875rem;
            padding: 0.25rem 0.75rem;
        }
        
        .hero-search-select {
            background: transparent;
            width: auto;
            min-width: 140px;
            font-size: 0.875rem;
            padding: 0.25rem 0.5rem;
        }
        
        .hero-search-divider {
            height: 20px;
        }
        
        .hero-search-btn,
        .hero-location-btn {
            font-size: 0.875rem;
        }
        
        .view-all-link {
            font-size: 0.8125rem;
        }
        
        /* Results and Map Section - Unified Layout */
        .results-map-section {
            display: flex;
            flex-direction: column;
            height: calc(100vh - 260px - 56px + 60px); /* Full height minus hero (260px), navbar (56px), plus extra space (60px) */
            min-height: 500px;
            background: #ffffff;
            margin: 0;
            padding: 0;
            position: relative;
        }
        
        /* Results Header Bar - Fixed at top */
        .results-header-bar {
            flex: 0 0 auto;
            padding: 0.5rem 1.25rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(235, 235, 235, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 5;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .results-header-bar h6 {
            color: #222 !important;
            font-weight: 600 !important;
            font-size: 0.8125rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin: 0;
            text-align: center;
        }
        
        /* Map Section - Full height */
        .map-section {
            flex: 1 1 auto;
            position: relative;
            min-height: 0;
            background: #ffffff;
            overflow: hidden;
            margin: 0;
            padding: 0;
        }
        
        /* Results Section - Overlay on map */
        .results-section {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            background: transparent;
            padding: 0;
            max-height: 160px;
            overflow: visible;
            z-index: 10;
            pointer-events: none;
        }
        
        .results-section.active {
            pointer-events: auto;
        }
        
        .results-scroll-area {
            padding: 0.75rem 0;
            background: transparent;
            display: flex;
            justify-content: center;
            align-items: center;
            pointer-events: auto;
        }
        
        #festivities-map {
            width: 100%;
            height: 100%;
            border: none;
            margin: 0;
            padding: 0;
        }
        
        /* Ensure body doesn't add extra scroll */
        body {
            overflow-x: hidden;
        }
        
        .results-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            border-top: 1px solid rgba(235, 235, 235, 0.8);
            background: rgba(250, 250, 250, 0.9);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            z-index: 10;
            pointer-events: auto;
        }
        
        .results-footer .py-3 {
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
        }
        
        /* Horizontal Scroll Container */
        #map-results-scroll-container {
            position: relative;
            padding: 0.75rem 1rem;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
            min-width: fit-content;
            max-width: 100%;
            margin: 0 auto;
        }
        
        @media (max-width: 768px) {
            #map-results-scroll-container {
                padding: 0.5rem 0.75rem;
                border-radius: 10px;
                max-width: calc(100% - 1rem);
            }
            
            .results-header {
                padding: 0.75rem 1rem;
            }
            
            .results-scroll-area {
                padding: 0.5rem 0.5rem;
            }
        }
        
        .horizontal-scroll-wrapper {
            display: flex;
            overflow-x: auto;
            overflow-y: hidden;
            scroll-behavior: smooth;
            gap: 1rem;
            padding: 0;
            scrollbar-width: thin;
            -webkit-overflow-scrolling: touch;
            justify-content: center;
            align-items: center;
            min-width: fit-content;
        }
        
        /* Center items when few, left-align when many (handled by JS) */
        .horizontal-scroll-wrapper.centered {
            justify-content: center;
        }
        
        .horizontal-scroll-wrapper.scrollable {
            justify-content: flex-start;
        }
        
        .horizontal-scroll-wrapper::-webkit-scrollbar {
            height: 4px;
        }
        
        .horizontal-scroll-wrapper::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .horizontal-scroll-wrapper::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 2px;
        }
        
        /* Scroll Navigation Buttons */
        .scroll-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 15;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(221, 221, 221, 0.8);
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            transition: all 0.2s ease;
            padding: 0;
            font-size: 0.875rem;
            color: #222;
        }
        
        .scroll-nav-btn:hover:not(:disabled) {
            background: #FEB101;
            border-color: #FEB101;
            color: white;
            box-shadow: 0 4px 12px rgba(254, 177, 1, 0.4);
            transform: translateY(-50%) scale(1.05);
        }
        
        .scroll-nav-btn-left {
            left: 0.5rem;
        }
        
        .scroll-nav-btn-right {
            right: 0.5rem;
        }
        
        .scroll-nav-btn:disabled {
            opacity: 0.25;
            cursor: not-allowed;
            background: #f8f9fa;
        }
        
        /* Compact Cards - Professional Design */
        .horizontal-festivity-card {
            flex: 0 0 auto;
            width: 240px;
            min-width: 240px;
        }
        
        @media (min-width: 576px) {
            .horizontal-festivity-card {
                width: 260px;
            }
        }
        
        @media (min-width: 992px) {
            .horizontal-festivity-card {
                width: 280px;
            }
        }
        
        .festivity-card-compact {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08), 0 1px 3px rgba(0,0,0,0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
            display: flex;
            flex-direction: column;
            text-decoration: none;
            color: inherit;
            position: relative;
        }
        
        .festivity-card-compact::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 16px;
            padding: 1px;
            background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(0,0,0,0.05));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .festivity-card-compact:hover {
            box-shadow: 0 12px 28px rgba(0,0,0,0.15), 0 4px 12px rgba(0,0,0,0.1);
            transform: translateY(-6px) scale(1.02);
            text-decoration: none;
            color: inherit;
        }
        
        .festivity-card-compact:hover::before {
            opacity: 1;
        }
        
        .festivity-card-image-wrapper {
            position: relative;
            width: 100%;
            height: 180px;
            overflow: hidden;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .festivity-card-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .festivity-card-compact:hover .festivity-card-image {
            transform: scale(1.08);
        }
        
        .festivity-card-image-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: linear-gradient(to top, rgba(0,0,0,0.6), transparent);
            pointer-events: none;
        }
        
        .festivity-card-body {
            padding: 1rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            background: white;
        }
        
        .festivity-card-title {
            font-size: 0.98rem;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0 0 0.75rem 0;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            letter-spacing: -0.01em;
        }
        
        .festivity-card-meta {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }
        
        .festivity-card-province-badge {
            display: inline-block;
            padding: 0.25rem 0.625rem;
            background: linear-gradient(135deg, #F2B705 0%, #E5A805 100%);
            color: white;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            width: fit-content;
            box-shadow: 0 2px 4px rgba(242, 183, 5, 0.3);
        }
        
        .festivity-card-date {
            font-size: 0.8rem;
            color: #666;
            margin: 0;
            line-height: 1.4;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }
        
        .festivity-card-date i {
            color: #999;
            font-size: 0.875rem;
        }
        
        .festivity-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: auto;
            padding-top: 0.75rem;
            border-top: 1px solid #f0f0f0;
        }
        
        .festivity-card-rating {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.78rem;
            color: #1a1a1a;
            font-weight: 600;
        }
        
        .festivity-card-rating i {
            color: #F59E0B;
            font-size: 0.875rem;
            filter: drop-shadow(0 1px 2px rgba(245, 158, 11, 0.3));
        }
        
        .festivity-card-action {
            color: #F2B705;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .festivity-card-compact:hover .festivity-card-action {
            opacity: 1;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .hero-banner {
                height: 280px;
            }
            
            .hero-content {
                padding: 1.5rem 0 1rem;
            }
            
            .hero-tagline h2 {
                font-size: 1.25rem !important;
            }
            
            .hero-tagline p {
                font-size: 0.875rem !important;
            }
            
            .hero-search-container {
                padding: 0 1rem;
            }
            
            .hero-search-bar {
                flex-direction: column;
                border-radius: 16px !important;
                gap: 0.5rem;
                padding: 0.5rem !important;
                min-width: auto !important;
                max-width: 100% !important;
            }
            
            .hero-search-bar .vr {
                display: none;
            }
            
            .hero-search-select {
                width: 100% !important;
                min-width: auto !important;
            }
            
            #map-near-me-btn {
                width: 100%;
                margin-top: 0.5rem;
            }
            
            
            .results-map-section {
                height: calc(100vh - 240px - 56px + 60px);
                min-height: 500px;
                padding: 0;
            }
            
            .results-section {
                max-height: 140px;
            }
            
            .results-header-bar {
                padding: 0.5rem 1rem;
            }
            
            .results-header {
                padding: 0.5rem 1rem;
            }
            
            .results-scroll-area {
                padding: 0.375rem 0;
            }
            
            .festivity-card-image-wrapper {
                height: 160px;
            }
            
            .horizontal-festivity-card {
                width: 220px;
            }
        }
        
        @media (max-width: 576px) {
            .hero-banner {
                height: 260px;
            }
            
            .hero-content {
                padding: 1.25rem 0 0.875rem;
            }
            
            .hero-tagline {
                margin-bottom: 1rem !important;
            }
            
            .hero-tagline h2 {
                font-size: 1.125rem !important;
            }
            
            .hero-tagline p {
                font-size: 0.8125rem !important;
            }
            
            .results-map-section {
                height: calc(100vh - 220px - 56px + 60px);
                min-height: 450px;
                padding: 0;
            }
            
            .results-section {
                max-height: 140px;
            }
            
            .scroll-nav-btn {
                width: 32px;
                height: 32px;
                font-size: 0.75rem;
            }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        #map-results-container {
            animation: fadeIn 0.3s ease-in;
        }
    </style>

    <script>
        // Map Search Section - Google Maps Integration with Advanced Search
        (function() {
            const mapElement = document.getElementById('festivities-map');
            const provinceFilter = document.getElementById('map-province-filter');
            const searchQuery = document.getElementById('map-search-query');
            const searchBtn = document.getElementById('map-search-btn');
            const nearMeBtn = document.getElementById('map-near-me-btn');
            const resultsContainer = document.getElementById('map-results-container');
            const resultsCount = document.getElementById('map-results-count');
            const resultsDisplayed = document.getElementById('map-results-displayed');
            const noResultsAlert = document.getElementById('map-no-results');
            const loadingMore = document.getElementById('map-loading-more');
            const scrollContainer = document.getElementById('map-results-scroll');
            const scrollLeftBtn = document.getElementById('scroll-left-btn');
            const scrollRightBtn = document.getElementById('scroll-right-btn');
            
            let map = null;
            let markers = [];
            let currentFestivities = [];
            let filteredFestivities = [];
            let displayedCount = 0;
            let itemsPerLoad = 3; // Load 3 more items each time
            const MAX_DISPLAYED = 20; // Maximum number of festivities to show
            const madridCenter = { lat: 40.4168, lng: -3.7038 };
            const mapKey = '{{ config('services.google.maps_key') }}';
            const viewAllLink = document.getElementById('map-view-all-link');
            const viewAllContainer = document.getElementById('map-view-all');
            const mapTotalCount = document.getElementById('map-total-count');
            let geocoder = null;
            let isSearching = false;
            
            // Calculate responsive padding for map (camera centered higher than marked point)
            // Moved outside initMap to be accessible from other functions
            const getMapPadding = () => {
                // Calculate approximate map height based on viewport
                const heroHeight = window.innerWidth <= 576 ? 220 : (window.innerWidth <= 768 ? 240 : 260);
                const navbarHeight = 56;
                const mapHeight = window.innerHeight - heroHeight - navbarHeight;
                
                // To position camera higher than the marked point:
                // padding.bottom pushes the visual center UP, showing more area below the point
                // Increased padding for more visible effect
                const paddingBottom = mapHeight * 0.4; // 40% of map height for more dramatic shift
                
                if (window.innerWidth <= 576) {
                    return { top: 0, right: 0, bottom: Math.round(paddingBottom), left: 0 }; // Mobile
                } else if (window.innerWidth <= 768) {
                    return { top: 0, right: 0, bottom: Math.round(paddingBottom), left: 0 }; // Tablet
                } else {
                    return { top: 0, right: 0, bottom: Math.round(paddingBottom), left: 0 }; // Desktop
                }
            };
            
            // Initialize Google Map
            function initMap() {
                if (typeof google === 'undefined' || !google.maps) {
                    console.error('Google Maps not loaded');
                    return;
                }
                
                // Custom map styles with warm, colorful palette (orange predominant with complementary colors)
                const mapStyles = [
                    {
                        "featureType": "all",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#fef5e7"
                            }
                        ]
                    },
                    {
                        "featureType": "all",
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {
                                "color": "#8b4513"
                            }
                        ]
                    },
                    {
                        "featureType": "all",
                        "elementType": "labels.text.stroke",
                        "stylers": [
                            {
                                "color": "#ffffff"
                            },
                            {
                                "weight": 2
                            }
                        ]
                    },
                    {
                        "featureType": "water",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#4a9ec4"
                            }
                        ]
                    },
                    {
                        "featureType": "landscape.natural",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#d4a574"
                            }
                        ]
                    },
                    {
                        "featureType": "landscape.man_made",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#f4d03f"
                            }
                        ]
                    },
                    {
                        "featureType": "road",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#ffffff"
                            }
                        ]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#ff6b35"
                            }
                        ]
                    },
                    {
                        "featureType": "road.arterial",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#ffa366"
                            }
                        ]
                    },
                    {
                        "featureType": "road.local",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#ffe0b2"
                            }
                        ]
                    },
                    {
                        "featureType": "poi.park",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#7fb069"
                            }
                        ]
                    },
                    {
                        "featureType": "poi",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#ffb84d"
                            }
                        ]
                    },
                    {
                        "featureType": "poi.attraction",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#ff8c42"
                            }
                        ]
                    },
                    {
                        "featureType": "administrative.locality",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#f9c784"
                            }
                        ]
                    },
                    {
                        "featureType": "administrative.neighborhood",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#ffe5cc"
                            }
                        ]
                    },
                    {
                        "featureType": "transit",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#c97d60"
                            }
                        ]
                    }
                ];
                
                map = new google.maps.Map(mapElement, {
                    center: madridCenter,
                    zoom: 6,
                    mapTypeControl: false,
                    streetViewControl: false,
                    fullscreenControl: false,
                    styles: mapStyles,
                    // Padding to shift visual center downward (responsive)
                    padding: getMapPadding()
                });
                
                // Update padding on window resize
                window.addEventListener('resize', () => {
                    if (map) {
                        map.setOptions({ padding: getMapPadding() });
                    }
                });
                
                // Initialize Geocoder
                geocoder = new google.maps.Geocoder();
                
                // Load initial festivities
                loadFestivitiesForMap();
                
                // Listen to map bounds changes (only if not currently searching)
                map.addListener('bounds_changed', function() {
                    if (isSearching) return; // Don't reload if we're performing a search
                    clearTimeout(window.mapBoundsTimeout);
                    window.mapBoundsTimeout = setTimeout(function() {
                        loadFestivitiesForMap();
                    }, 500); // Debounce
                });
            }
            
            // Load festivities for current map view
            function loadFestivitiesForMap() {
                if (!map) {
                    return;
                }
                
                const bounds = map.getBounds();
                if (!bounds) {
                    return;
                }
                
                const ne = bounds.getNorthEast();
                const sw = bounds.getSouthWest();
                
                const province = provinceFilter.value;
                const params = new URLSearchParams({
                    north: ne.lat(),
                    south: sw.lat(),
                    east: ne.lng(),
                    west: sw.lng(),
                });
                
                if (province) {
                    params.append('province', province);
                }
                
                fetch(`{{ route('festivities.map') }}?${params.toString()}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.festivities) {
                        currentFestivities = data.festivities;
                        // Clear everything before displaying new results to ensure clean state
                        filteredFestivities = [];
                        displayedCount = 0;
                        if (scrollContainer) {
                            scrollContainer.innerHTML = '';
                        }
                        // Always display results with reset=true to start fresh
                        // displayMapResults will handle filtering by search query and marker updates
                        displayMapResults(data.festivities, true);
                    }
                })
                .catch(error => {
                    console.error('Error loading festivities:', error);
                })
                .finally(() => {
                    isSearching = false;
                });
            }
            
            // Update map markers
            function updateMapMarkers(festivities) {
                // Clear existing markers
                markers.forEach(marker => marker.setMap(null));
                markers = [];
                
                // Add new markers
                festivities.forEach(festivity => {
                    if (festivity.latitude && festivity.longitude) {
                        const marker = new google.maps.Marker({
                            position: { lat: parseFloat(festivity.latitude), lng: parseFloat(festivity.longitude) },
                            map: map,
                            title: festivity.name,
                        });
                        
                        // Add info window
                        const infoWindow = new google.maps.InfoWindow({
                            content: `
                                <div style="padding: 10px; max-width: 250px;">
                                    <h6 class="fw-bold mb-2">${festivity.name}</h6>
                                    <p class="small mb-1"><i class="bi bi-geo-alt"></i> ${festivity.locality.name || ''}</p>
                                    <p class="small mb-2"><i class="bi bi-calendar"></i> ${festivity.start_date}</p>
                                    <a href="${festivity.url}" class="btn btn-sm btn-primary">Ver Más</a>
                                </div>
                            `,
                        });
                        
                        marker.addListener('click', () => {
                            infoWindow.open(map, marker);
                        });
                        
                        markers.push(marker);
                    }
                });
            }
            
            // Normalize text for accent-insensitive search (matches PHP normalizeText)
            function normalizeText(text) {
                if (!text) return '';
                
                // Convert to lowercase
                text = text.toLowerCase();
                
                // Replace accents and special characters
                const replacements = {
                    'á': 'a', 'à': 'a', 'ä': 'a', 'â': 'a', 'ã': 'a',
                    'é': 'e', 'è': 'e', 'ë': 'e', 'ê': 'e',
                    'í': 'i', 'ì': 'i', 'ï': 'i', 'î': 'i',
                    'ó': 'o', 'ò': 'o', 'ö': 'o', 'ô': 'o', 'õ': 'o',
                    'ú': 'u', 'ù': 'u', 'ü': 'u', 'û': 'u',
                    'ñ': 'n', 'ç': 'c',
                    'Á': 'a', 'À': 'a', 'Ä': 'a', 'Â': 'a', 'Ã': 'a',
                    'É': 'e', 'È': 'e', 'Ë': 'e', 'Ê': 'e',
                    'Í': 'i', 'Ì': 'i', 'Ï': 'i', 'Î': 'i',
                    'Ó': 'o', 'Ò': 'o', 'Ö': 'o', 'Ô': 'o', 'Õ': 'o',
                    'Ú': 'u', 'Ù': 'u', 'Ü': 'u', 'Û': 'u',
                    'Ñ': 'n', 'Ç': 'c'
                };
                
                return text.replace(/[áàäâãéèëêíìïîóòöôõúùüûñçÁÀÄÂÃÉÈËÊÍÌÏÎÓÒÖÔÕÚÙÜÛÑÇ]/g, (match) => replacements[match] || match);
            }
            
            // Expand search query with synonyms (matches PHP expandSearchQuery)
            function expandSearchQuery(query) {
                const normalizedQuery = normalizeText(query);
                const expandedQueries = [query, normalizedQuery];
                
                // Synonyms and common variations
                const synonyms = {
                    'fiesta': ['festividad', 'celebracion', 'evento', 'festival'],
                    'festividad': ['fiesta', 'celebracion', 'evento', 'festival'],
                    'celebracion': ['fiesta', 'festividad', 'evento', 'festival'],
                    'evento': ['fiesta', 'festividad', 'celebracion', 'festival'],
                    'festival': ['fiesta', 'festividad', 'celebracion', 'evento'],
                    'feria': ['mercado', 'exposicion', 'muestra'],
                    'carnaval': ['carnavales', 'mascarada'],
                    'navidad': ['navideño', 'navideña'],
                    'semana santa': ['santa semana', 'pascua'],
                    'verano': ['estival', 'estivales'],
                    'invierno': ['invernal', 'invernales']
                };
                
                // Add synonyms if found
                for (const [key, values] of Object.entries(synonyms)) {
                    if (normalizedQuery.includes(key)) {
                        expandedQueries.push(...values);
                    }
                }
                
                return [...new Set(expandedQueries)]; // Remove duplicates
            }
            
            // Calculate relevance score (matches PHP calculateRelevanceScore)
            function calculateRelevanceScore(text, query) {
                const textLower = normalizeText(text);
                const queryLower = normalizeText(query);
                
                // Exact match = 1, starts with = 2, contains = 3, no match = 4
                if (textLower === queryLower) {
                    return 1;
                } else if (textLower.indexOf(queryLower) === 0) {
                    return 2;
                } else if (textLower.includes(queryLower)) {
                    return 3;
                }
                
                return 4;
            }
            
            // Advanced search for festivities (matches PHP searchFestivities logic)
            function searchFestivities(festivities, query) {
                if (!query || !query.trim()) {
                    return festivities;
                }
                
                const expandedQueries = expandSearchQuery(query.trim());
                
                const filtered = festivities.filter(festivity => {
                    const festivityName = festivity.name || '';
                    const festivityDescription = festivity.description || '';
                    const localityName = festivity.locality?.name || '';
                    // Try multiple ways to get province - check both direct and locality.province
                    const province = festivity.province || festivity.locality?.province || '';
                    const localityProvince = festivity.locality?.province || '';
                    
                    const normalizedFestivityName = normalizeText(festivityName);
                    const normalizedDescription = normalizeText(festivityDescription);
                    const normalizedLocalityName = normalizeText(localityName);
                    const normalizedProvince = normalizeText(province);
                    const normalizedLocalityProvince = normalizeText(localityProvince);
                    
                    for (const searchTerm of expandedQueries) {
                        const normalizedTerm = normalizeText(searchTerm);
                        const searchTermLower = searchTerm.toLowerCase();
                        
                        // Search in festivity name (case and accent insensitive)
                        if (festivityName.toLowerCase().includes(searchTermLower) || 
                            normalizedFestivityName.includes(normalizedTerm)) {
                            return true;
                        }
                        
                        // Search in description
                        if (festivityDescription.toLowerCase().includes(searchTermLower) || 
                            normalizedDescription.includes(normalizedTerm)) {
                            return true;
                        }
                        
                        // Search in locality name
                        if (localityName.toLowerCase().includes(searchTermLower) || 
                            normalizedLocalityName.includes(normalizedTerm)) {
                            return true;
                        }
                        
                        // Search in province (case and accent insensitive) - improved logic
                        // Check both direct province and locality.province
                        const provincesToCheck = [province, localityProvince].filter(p => p && p.length > 0);
                        for (const prov of provincesToCheck) {
                            const provLower = prov.toLowerCase();
                            const normalizedProv = normalizeText(prov);
                            
                            // Direct match (normalized)
                            if (normalizedProv.includes(normalizedTerm) || 
                                normalizedTerm.includes(normalizedProv)) {
                                return true;
                            }
                            // Case-insensitive match
                            if (provLower.includes(searchTermLower) || 
                                searchTermLower.includes(provLower)) {
                                return true;
                            }
                        }
                        
                        // Also check if search term matches any province name (normalized)
                        const provinces = {!! json_encode(config('provinces.provinces')) !!};
                        for (const configProvince of provinces) {
                            const normalizedConfigProvince = normalizeText(configProvince);
                            const configProvinceLower = configProvince.toLowerCase();
                            
                            // Check if search term matches this province (normalized or case-insensitive)
                            const termMatchesProvince = normalizedConfigProvince === normalizedTerm || 
                                normalizedConfigProvince.includes(normalizedTerm) ||
                                normalizedTerm.includes(normalizedConfigProvince) ||
                                configProvinceLower.includes(searchTermLower) ||
                                searchTermLower.includes(configProvinceLower);
                            
                            if (termMatchesProvince) {
                                // If this festivity's province matches (exact or normalized)
                                // Check both direct province and locality.province
                                const matchesDirect = province === configProvince || 
                                    normalizedProvince === normalizedConfigProvince ||
                                    (province && normalizedProvince.includes(normalizedConfigProvince)) ||
                                    (province && normalizedConfigProvince.includes(normalizedProvince));
                                    
                                const matchesLocality = localityProvince === configProvince ||
                                    normalizedLocalityProvince === normalizedConfigProvince ||
                                    (localityProvince && normalizedLocalityProvince.includes(normalizedConfigProvince)) ||
                                    (localityProvince && normalizedConfigProvince.includes(normalizedLocalityProvince));
                                
                                if (matchesDirect || matchesLocality) {
                                    return true;
                                }
                            }
                        }
                        
                        // Search by individual words (words longer than 2 characters)
                        // Also search the full term as a substring in all fields
                        const words = searchTerm.trim().split(/\s+/);
                        for (const word of words) {
                            if (word.length >= 2) { // Changed from > 2 to >= 2 to include 2-character words
                                const normalizedWord = normalizeText(word);
                                const wordLower = word.toLowerCase();
                                
                                // Search in all fields with both normalized and case-insensitive
                                if (festivityName.toLowerCase().includes(wordLower) || 
                                    festivityDescription.toLowerCase().includes(wordLower) || 
                                    localityName.toLowerCase().includes(wordLower) ||
                                    (province && province.toLowerCase().includes(wordLower)) ||
                                    (localityProvince && localityProvince.toLowerCase().includes(wordLower)) ||
                                    normalizedFestivityName.includes(normalizedWord) ||
                                    normalizedDescription.includes(normalizedWord) ||
                                    normalizedLocalityName.includes(normalizedWord) ||
                                    (province && normalizedProvince.includes(normalizedWord)) ||
                                    (localityProvince && normalizedLocalityProvince.includes(normalizedWord))) {
                                    return true;
                                }
                            }
                        }
                        
                        // Also search the full search term as substring (not just individual words)
                        // This helps with partial matches like "mal" matching "malaga"
                        if (searchTerm.length >= 2) {
                            const normalizedFullTerm = normalizeText(searchTerm);
                            const fullTermLower = searchTerm.toLowerCase();
                            
                            if (festivityName.toLowerCase().includes(fullTermLower) || 
                                festivityDescription.toLowerCase().includes(fullTermLower) || 
                                localityName.toLowerCase().includes(fullTermLower) ||
                                (province && province.toLowerCase().includes(fullTermLower)) ||
                                (localityProvince && localityProvince.toLowerCase().includes(fullTermLower)) ||
                                normalizedFestivityName.includes(normalizedFullTerm) ||
                                normalizedDescription.includes(normalizedFullTerm) ||
                                normalizedLocalityName.includes(normalizedFullTerm) ||
                                (province && normalizedProvince.includes(normalizedFullTerm)) ||
                                (localityProvince && normalizedLocalityProvince.includes(normalizedFullTerm))) {
                                return true;
                            }
                        }
                    }
                    
                    return false;
                });
                
                // Sort by relevance
                const sorted = filtered.sort((a, b) => {
                    const aScore = calculateRelevanceScore(a.name, query);
                    const bScore = calculateRelevanceScore(b.name, query);
                    
                    if (aScore === bScore) {
                        return a.name.localeCompare(b.name);
                    }
                    
                    return aScore - bScore;
                });
                
                return sorted;
            }
            
            // Display results with horizontal scroll and pagination
            function displayMapResults(festivities, reset = true) {
                if (!festivities || festivities.length === 0) {
                    resultsContainer.classList.add('d-none');
                    resultsContainer.classList.remove('active');
                    noResultsAlert.classList.remove('d-none');
                    scrollContainer.innerHTML = '';
                    filteredFestivities = [];
                    displayedCount = 0;
                    return;
                }
                
                // Get current search query
                const query = searchQuery && searchQuery.value ? searchQuery.value.trim() : '';
                
                // Always filter by current query
                if (query) {
                    // Debug: log first festivity to check province structure
                    if (festivities.length > 0 && console && console.log) {
                        const first = festivities[0];
                        console.log('First festivity structure:', {
                            name: first.name,
                            province: first.province,
                            locality: first.locality,
                            localityProvince: first.locality?.province,
                            allKeys: Object.keys(first)
                        });
                        console.log('Searching for:', query);
                        console.log('Normalized search term:', normalizeText(query));
                        // Check a few festivities to see province structure
                        const sampleProvinces = festivities.slice(0, 5).map(f => ({
                            name: f.name,
                            province: f.province,
                            localityProvince: f.locality?.province
                        }));
                        console.log('Sample provinces:', sampleProvinces);
                    }
                    filteredFestivities = searchFestivities(festivities, query);
                    if (console && console.log) {
                        console.log('Filtered results:', filteredFestivities.length, 'out of', festivities.length);
                        if (filteredFestivities.length > 0) {
                            console.log('First filtered result:', {
                                name: filteredFestivities[0].name,
                                province: filteredFestivities[0].province,
                                localityProvince: filteredFestivities[0].locality?.province
                            });
                        }
                    }
                } else {
                    filteredFestivities = festivities;
                }
                
                // Always reset when explicitly resetting - this ensures clean state
                if (reset) {
                    // Clear container immediately to remove ALL old cards
                    scrollContainer.innerHTML = '';
                    displayedCount = 0;
                    filteredFestivities = query ? searchFestivities(festivities, query) : festivities;
                    
                    // Reset centering
                    scrollContainer.classList.remove('centered', 'scrollable');
                    
                    // Update markers with all festivities in visible area
                    updateMapMarkers(festivities);
                }
                
                if (filteredFestivities.length === 0) {
                    // Hide results container but keep it in DOM for when results appear
                    resultsContainer.classList.add('d-none');
                    resultsContainer.classList.remove('active');
                    // Show "no results" message
                    noResultsAlert.classList.remove('d-none');
                    loadingMore.classList.add('d-none');
                    viewAllContainer.classList.add('d-none');
                    resultsCount.textContent = '0';
                    if (resultsDisplayed) resultsDisplayed.textContent = '0';
                    scrollContainer.innerHTML = '';
                    scrollContainer.classList.remove('centered', 'scrollable');
                    updateScrollButtons();
                    // Don't clear markers - keep them to show where festivities are
                    // markers.forEach(marker => marker.setMap(null));
                    // markers = [];
                    return;
                }
                
                noResultsAlert.classList.add('d-none');
                resultsContainer.classList.remove('d-none');
                resultsContainer.classList.add('active');
                resultsCount.textContent = filteredFestivities.length;
                
                // Calculate how many to show (initial load or load more)
                // On initial load, show enough to fill the visible area (at least 3)
                const initialLoad = displayedCount === 0;
                let cardsToShow;
                
                if (initialLoad) {
                    // Determine screen size and show appropriate initial amount
                    const isMobile = window.innerWidth < 576;
                    const isTablet = window.innerWidth >= 576 && window.innerWidth < 992;
                    
                    if (isMobile) {
                        cardsToShow = 3; // Show 3 cards on mobile (user scrolls to see them)
                    } else if (isTablet) {
                        cardsToShow = 4; // Show 4 cards on tablet
                    } else {
                        cardsToShow = 6; // Show 6 cards on desktop (2 rows of 3)
                    }
                    cardsToShow = Math.min(cardsToShow, filteredFestivities.length);
                } else {
                    cardsToShow = itemsPerLoad;
                }
                
                // Limit to MAX_DISPLAYED
                const maxIndex = Math.min(filteredFestivities.length, MAX_DISPLAYED);
                const endIndex = Math.min(displayedCount + cardsToShow, maxIndex);
                
                // Show loading indicator if loading more (and not at max)
                if (!initialLoad && endIndex < maxIndex) {
                    loadingMore.classList.remove('d-none');
                } else {
                    loadingMore.classList.add('d-none');
                }
                
                // Add cards
                for (let i = displayedCount; i < endIndex; i++) {
                    const festivity = filteredFestivities[i];
                    const card = createFestivityCard(festivity);
                    scrollContainer.appendChild(card);
                }
                
                displayedCount = endIndex;
                if (resultsDisplayed) resultsDisplayed.textContent = displayedCount;
                mapTotalCount.textContent = filteredFestivities.length;
                
                // Update centering based on number of cards
                updateCardsCentering();
                updateScrollButtons();
                
                // Show "View All" link if we've reached the max or if there are more results
                if (displayedCount >= MAX_DISPLAYED && filteredFestivities.length > MAX_DISPLAYED) {
                    viewAllContainer.classList.remove('d-none');
                    removeScrollListener();
                    updateViewAllLink();
                } else if (displayedCount >= filteredFestivities.length) {
                    viewAllContainer.classList.add('d-none');
                    removeScrollListener();
                } else if (displayedCount < MAX_DISPLAYED) {
                    viewAllContainer.classList.add('d-none');
                    addScrollListener();
                } else {
                    viewAllContainer.classList.add('d-none');
                    removeScrollListener();
                }
            }
            
            // Create compact festivity card
            function createFestivityCard(festivity) {
                const cardWrapper = document.createElement('div');
                cardWrapper.className = 'horizontal-festivity-card';
                
                const card = document.createElement('a');
                card.href = festivity.url;
                card.className = 'festivity-card-compact';
                
                const startDate = new Date(festivity.start_date);
                const endDate = festivity.end_date ? new Date(festivity.end_date) : null;
                const dateStr = endDate && endDate.getTime() !== startDate.getTime()
                    ? `${startDate.toLocaleDateString('es-ES', { day: 'numeric', month: 'short' })} - ${endDate.toLocaleDateString('es-ES', { day: 'numeric', month: 'short' })}`
                    : startDate.toLocaleDateString('es-ES', { day: 'numeric', month: 'short', year: 'numeric' });
                
                const province = festivity.province || festivity.locality?.province || '';
                const provinceBadge = province 
                    ? `<span class="festivity-card-province-badge">${province}</span>`
                    : '';
                
                const photoHtml = festivity.photo 
                    ? `<div class="festivity-card-image-wrapper">
                           <img src="${festivity.photo}" class="festivity-card-image" alt="${festivity.name}">
                           <div class="festivity-card-image-overlay"></div>
                       </div>`
                    : `<div class="festivity-card-image-wrapper d-flex align-items-center justify-content-center">
                           <i class="bi bi-calendar-event text-white" style="font-size: 3rem; opacity: 0.6;"></i>
                           <div class="festivity-card-image-overlay"></div>
                       </div>`;
                
                card.innerHTML = `
                    ${photoHtml}
                    <div class="festivity-card-body">
                        <h6 class="festivity-card-title">${festivity.name}</h6>
                        <div class="festivity-card-meta">
                            ${provinceBadge}
                            <p class="festivity-card-date">
                                <i class="bi bi-calendar3"></i>
                                <span>${dateStr}</span>
                            </p>
                        </div>
                        <div class="festivity-card-footer">
                            <div class="festivity-card-rating">
                                <i class="bi bi-heart-fill"></i>
                                <span>${festivity.votes_count || 0}</span>
                            </div>
                            <span class="festivity-card-action">Ver más →</span>
                        </div>
                    </div>
                `;
                
                cardWrapper.appendChild(card);
                return cardWrapper;
            }
            
            // Infinite scroll listener
            let scrollListener = null;
            
            function addScrollListener() {
                if (scrollListener) return;
                
                scrollListener = function() {
                    const container = scrollContainer;
                    const scrollLeft = container.scrollLeft;
                    const scrollWidth = container.scrollWidth;
                    const clientWidth = container.clientWidth;
                    const scrollPercentage = (scrollLeft + clientWidth) / scrollWidth;
                    
                    // Load more when scrolled to 80% of the way (but not beyond MAX_DISPLAYED)
                    if (scrollPercentage > 0.8 && displayedCount < MAX_DISPLAYED && displayedCount < filteredFestivities.length) {
                        displayMapResults(currentFestivities, false);
                    }
                    
                    updateScrollButtons();
                };
                
                scrollContainer.addEventListener('scroll', scrollListener);
            }
            
            // Update "View All" link with current search parameters
            function updateViewAllLink() {
                if (!viewAllLink) return;
                
                const query = searchQuery.value.trim();
                const province = provinceFilter ? provinceFilter.value : '';
                
                // Build URL with parameters
                const url = new URL('{{ route("festivities.index") }}', window.location.origin);
                
                if (query) {
                    url.searchParams.append('search', query);
                }
                
                if (province) {
                    url.searchParams.append('province', province);
                }
                
                viewAllLink.href = url.toString();
            }
            
            function removeScrollListener() {
                if (scrollListener) {
                    scrollContainer.removeEventListener('scroll', scrollListener);
                    scrollListener = null;
                }
            }
            
            // Update cards centering based on number of visible cards
            function updateCardsCentering() {
                if (!scrollContainer) return;
                
                const scrollContainerWrapper = document.getElementById('map-results-scroll-container');
                if (!scrollContainerWrapper) return;
                
                const cards = scrollContainer.querySelectorAll('.horizontal-festivity-card');
                const cardCount = cards.length;
                
                if (cardCount === 0) {
                    scrollContainer.classList.remove('centered', 'scrollable');
                    scrollContainerWrapper.style.width = 'auto';
                    return;
                }
                
                // Wait a tick for cards to render, then calculate actual width
                setTimeout(() => {
                    // Calculate actual width of all cards including gaps
                    let totalCardsWidth = 0;
                    cards.forEach((card, index) => {
                        if (card.offsetWidth) {
                            totalCardsWidth += card.offsetWidth;
                            if (index < cards.length - 1) {
                                totalCardsWidth += 16; // gap (1rem)
                            }
                        }
                    });
                    
                    const padding = 32; // 1rem on each side (0.75rem + 1rem padding)
                    const totalWidth = totalCardsWidth + padding;
                    const maxContainerWidth = window.innerWidth - 64; // Account for margins
                    
                    // If all cards fit in max width, wrap to fit content; otherwise allow scroll
                    if (totalWidth <= maxContainerWidth) {
                        scrollContainerWrapper.style.width = totalWidth + 'px';
                        scrollContainerWrapper.style.maxWidth = 'none';
                        scrollContainer.classList.remove('scrollable');
                        scrollContainer.classList.add('centered');
                    } else {
                        // Allow wrapper to expand to max width when scrolling
                        scrollContainerWrapper.style.width = '100%';
                        scrollContainerWrapper.style.maxWidth = maxContainerWidth + 'px';
                        scrollContainer.classList.remove('centered');
                        scrollContainer.classList.add('scrollable');
                    }
                }, 0);
            }
            
            // Update scroll navigation buttons
            function updateScrollButtons() {
                if (!scrollLeftBtn || !scrollRightBtn) return;
                
                const container = scrollContainer;
                const scrollLeft = container.scrollLeft;
                const scrollWidth = container.scrollWidth;
                const clientWidth = container.clientWidth;
                
                scrollLeftBtn.disabled = scrollLeft <= 10;
                scrollRightBtn.disabled = scrollLeft + clientWidth >= scrollWidth - 10;
            }
            
            // Scroll navigation button handlers
            if (scrollLeftBtn) {
                scrollLeftBtn.addEventListener('click', () => {
                    scrollContainer.scrollBy({ left: -400, behavior: 'smooth' });
                });
            }
            
            if (scrollRightBtn) {
                scrollRightBtn.addEventListener('click', () => {
                    scrollContainer.scrollBy({ left: 400, behavior: 'smooth' });
                    // Also check if we need to load more when scrolling right (only if not at max)
                    if (displayedCount < MAX_DISPLAYED && displayedCount < filteredFestivities.length) {
                        setTimeout(() => {
                            if (scrollListener) scrollListener();
                        }, 400);
                    }
                });
            }
            
            // Update scroll buttons on scroll
            if (scrollContainer) {
                scrollContainer.addEventListener('scroll', updateScrollButtons);
                
                // Update centering on window resize
                window.addEventListener('resize', function() {
                    updateCardsCentering();
                    updateScrollButtons();
                });
            }
            
            // Get user location and center map
            function getNearMeLocation() {
                if (!navigator.geolocation) {
                    alert('La geolocalización no es compatible con tu navegador.');
                    return;
                }
                
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const userLocation = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                        };
                        map.setCenter(userLocation);
                        map.setZoom(12);
                        // Update padding to maintain camera position
                        map.setOptions({ padding: getMapPadding() });
                        loadFestivitiesForMap();
                    },
                    function(error) {
                        alert('No se pudo obtener tu ubicación. Por favor, verifica los permisos de ubicación.');
                    },
                    {
                        enableHighAccuracy: false,
                        timeout: 10000,
                        maximumAge: 60000,
                    }
                );
            }
            
            // Province to coordinates mapping (center of each province)
            const provinceCoordinates = {
                'Álava': { lat: 42.8467, lng: -2.6716, zoom: 10 },
                'Albacete': { lat: 38.9942, lng: -1.8584, zoom: 10 },
                'Alicante': { lat: 38.3452, lng: -0.4810, zoom: 10 },
                'Almería': { lat: 36.8381, lng: -2.4597, zoom: 10 },
                'Asturias': { lat: 43.3614, lng: -5.8593, zoom: 9 },
                'Ávila': { lat: 40.6564, lng: -4.7004, zoom: 11 },
                'Badajoz': { lat: 38.8782, lng: -6.9706, zoom: 10 },
                'Barcelona': { lat: 41.3851, lng: 2.1734, zoom: 10 },
                'Burgos': { lat: 42.3439, lng: -3.6969, zoom: 10 },
                'Cáceres': { lat: 39.4753, lng: -6.3724, zoom: 10 },
                'Cádiz': { lat: 36.5270, lng: -6.2886, zoom: 10 },
                'Cantabria': { lat: 43.4623, lng: -3.8099, zoom: 10 },
                'Castellón': { lat: 39.9864, lng: -0.0513, zoom: 10 },
                'Ciudad Real': { lat: 38.9861, lng: -3.9293, zoom: 10 },
                'Córdoba': { lat: 37.8882, lng: -4.7794, zoom: 10 },
                'Cuenca': { lat: 40.0718, lng: -2.1340, zoom: 11 },
                'Girona': { lat: 41.9794, lng: 2.8214, zoom: 10 },
                'Granada': { lat: 37.1773, lng: -3.5986, zoom: 10 },
                'Guadalajara': { lat: 40.6289, lng: -3.1618, zoom: 10 },
                'Guipúzcoa': { lat: 43.3183, lng: -1.9812, zoom: 10 },
                'Huelva': { lat: 37.2614, lng: -6.9447, zoom: 10 },
                'Huesca': { lat: 42.1361, lng: -0.4087, zoom: 10 },
                'Islas Baleares': { lat: 39.5696, lng: 2.6502, zoom: 9 },
                'Jaén': { lat: 37.7699, lng: -3.7903, zoom: 10 },
                'La Coruña': { lat: 43.3623, lng: -8.4115, zoom: 10 },
                'La Rioja': { lat: 42.4627, lng: -2.4449, zoom: 10 },
                'Las Palmas': { lat: 28.1248, lng: -15.4300, zoom: 10 },
                'León': { lat: 42.5987, lng: -5.5671, zoom: 10 },
                'Lérida': { lat: 41.6176, lng: 0.6200, zoom: 10 },
                'Lugo': { lat: 43.0097, lng: -7.5568, zoom: 10 },
                'Madrid': { lat: 40.4168, lng: -3.7038, zoom: 10 },
                'Málaga': { lat: 36.7213, lng: -4.4214, zoom: 10 },
                'Murcia': { lat: 37.9922, lng: -1.1307, zoom: 10 },
                'Navarra': { lat: 42.8181, lng: -1.6443, zoom: 10 },
                'Ourense': { lat: 42.3360, lng: -7.8643, zoom: 10 },
                'Palencia': { lat: 42.0096, lng: -4.5241, zoom: 11 },
                'Pontevedra': { lat: 42.4310, lng: -8.6444, zoom: 10 },
                'Salamanca': { lat: 40.9701, lng: -5.6635, zoom: 10 },
                'Santa Cruz de Tenerife': { lat: 28.4636, lng: -16.2518, zoom: 10 },
                'Segovia': { lat: 40.9429, lng: -4.1088, zoom: 11 },
                'Sevilla': { lat: 37.3891, lng: -5.9845, zoom: 10 },
                'Soria': { lat: 41.7640, lng: -2.4688, zoom: 11 },
                'Tarragona': { lat: 41.1189, lng: 1.2445, zoom: 10 },
                'Teruel': { lat: 40.3458, lng: -1.1065, zoom: 11 },
                'Toledo': { lat: 39.8628, lng: -4.0273, zoom: 10 },
                'Valencia': { lat: 39.4699, lng: -0.3763, zoom: 10 },
                'Valladolid': { lat: 41.6523, lng: -4.7245, zoom: 10 },
                'Vizcaya': { lat: 43.2627, lng: -2.9253, zoom: 10 },
                'Zamora': { lat: 41.5033, lng: -5.7438, zoom: 11 },
                'Zaragoza': { lat: 41.6488, lng: -0.8891, zoom: 10 },
            };
            
            // Event Listeners
            if (provinceFilter) {
                provinceFilter.addEventListener('change', function() {
                    isSearching = true;
                    const selectedProvince = provinceFilter.value;
                    
                    // If a province is selected, center map on that province
                    if (selectedProvince && provinceCoordinates[selectedProvince]) {
                        const coords = provinceCoordinates[selectedProvince];
                        if (map) {
                            map.setCenter({ lat: coords.lat, lng: coords.lng });
                            map.setZoom(coords.zoom);
                            // Update padding to maintain camera position
                            map.setOptions({ padding: getMapPadding() });
                        }
                    } else if (!selectedProvince) {
                        // If "Provincia" (all) is selected, reset to Madrid
                        if (map) {
                            map.setCenter(madridCenter);
                            map.setZoom(6);
                            // Update padding to maintain camera position
                            map.setOptions({ padding: getMapPadding() });
                        }
                    }
                    
                    // Load festivities for the new map view (this will trigger displayMapResults)
                    loadFestivitiesForMap();
                    // Update view all link
                    updateViewAllLink();
                });
            }
            
            // Geocode search query to find location
            function geocodeSearch(query, shouldFilterFirst = true) {
                // First, filter existing results immediately for real-time feedback
                if (shouldFilterFirst && currentFestivities && currentFestivities.length > 0) {
                    displayMapResults(currentFestivities, true);
                    updateViewAllLink();
                }
                
                if (!geocoder || !query || !query.trim()) {
                    // If no query or no geocoder, filtering already done above
                    return;
                }
                
                isSearching = true;
                
                // Try to geocode the search query in background (non-blocking)
                geocoder.geocode({ address: query + ', España' }, function(results, status) {
                    if (status === 'OK' && results && results.length > 0) {
                        // Found a location, move map to it
                        const location = results[0].geometry.location;
                        const bounds = results[0].geometry.bounds;
                        
                        if (bounds) {
                            // Use bounds if available for better view
                            map.fitBounds(bounds);
                            // Set a minimum zoom level and load festivities after bounds change
                            const boundsListener = map.addListener('bounds_changed', function() {
                                google.maps.event.removeListener(boundsListener);
                                if (map.getZoom() > 15) {
                                    map.setZoom(15);
                                }
                                // Update padding to maintain camera position
                                map.setOptions({ padding: getMapPadding() });
                                // Small delay to ensure map is settled, then load and filter
                                setTimeout(() => {
                                    isSearching = true; // Keep searching flag to prevent bounds_changed interference
                                    loadFestivitiesForMap();
                                    isSearching = false;
                                }, 300);
                            });
                        } else {
                            // Use point location
                            map.setCenter(location);
                            map.setZoom(12);
                            // Update padding to maintain camera position
                            map.setOptions({ padding: getMapPadding() });
                            // Small delay to ensure map is settled, then load and filter
                            setTimeout(() => {
                                isSearching = true; // Keep searching flag to prevent bounds_changed interference
                                loadFestivitiesForMap();
                                isSearching = false;
                            }, 300);
                        }
                    } else {
                        // No geocoding result, try filtering by province name or search in existing results
                        const province = findProvinceInQuery(query);
                        if (province && provinceCoordinates[province]) {
                            // Move to province if found
                            const coords = provinceCoordinates[province];
                            map.setCenter({ lat: coords.lat, lng: coords.lng });
                            map.setZoom(coords.zoom);
                            // Update padding to maintain camera position
                            map.setOptions({ padding: getMapPadding() });
                            loadFestivitiesForMap();
                        } else {
                            // No geocoding result, filter existing results without moving map
                            isSearching = false;
                            // Results already filtered above, just update view all link
                            updateViewAllLink();
                        }
                    }
                });
            }
            
            // Helper to find province name in query
            function findProvinceInQuery(query) {
                const provinces = {!! json_encode(config('provinces.provinces')) !!};
                const normalizedQuery = normalizeText(query);
                
                for (const province of provinces) {
                    const normalizedProvince = normalizeText(province);
                    if (normalizedQuery.includes(normalizedProvince) || normalizedProvince.includes(normalizedQuery)) {
                        return province;
                    }
                }
                return null;
            }
            
            // Search functionality
            function performSearch() {
                const query = searchQuery.value.trim();
                
                // Always reset filteredFestivities and clear cards when starting new search
                filteredFestivities = [];
                displayedCount = 0;
                if (scrollContainer) {
                    scrollContainer.innerHTML = '';
                }
                
                if (!query) {
                    // If query is empty, just show all results
                    if (currentFestivities && currentFestivities.length > 0) {
                        // Always reset to clear filtered cards and show all
                        displayMapResults(currentFestivities, true);
                        updateViewAllLink();
                    }
                    return;
                }
                
                // Filter existing results immediately for real-time feedback
                // Then try geocoding in background (non-blocking)
                // This ensures users see filtered results instantly while geocoding happens
                geocodeSearch(query, true);
            }
            
            // Debounce timer for search
            let searchTimeout = null;
            
            if (searchQuery) {
                // Search on input change (real-time) with shorter debounce for better UX
                searchQuery.addEventListener('input', function() {
                    const query = searchQuery.value.trim();
                    
                    // Clear previous timeout
                    clearTimeout(searchTimeout);
                    
                    // If query is empty, show all results immediately
                    if (!query) {
                        if (currentFestivities && currentFestivities.length > 0) {
                            filteredFestivities = [];
                            displayedCount = 0;
                            if (scrollContainer) {
                                scrollContainer.innerHTML = '';
                            }
                            displayMapResults(currentFestivities, true);
                            updateViewAllLink();
                        }
                        return;
                    }
                    
                    // Filter existing results immediately for instant feedback
                    if (currentFestivities && currentFestivities.length > 0) {
                        filteredFestivities = [];
                        displayedCount = 0;
                        if (scrollContainer) {
                            scrollContainer.innerHTML = '';
                        }
                        displayMapResults(currentFestivities, true);
                        updateViewAllLink();
                    }
                    
                    // Then try geocoding after a short delay (non-blocking)
                    searchTimeout = setTimeout(function() {
                        geocodeSearch(query, false); // Don't filter again, already done above
                    }, 300); // Shorter delay for better responsiveness
                });
                
                // Also search on Enter key (immediate, no debounce)
                searchQuery.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        clearTimeout(searchTimeout); // Cancel debounced search
                        performSearch();
                    }
                });
            }
            
            if (searchBtn) {
                searchBtn.addEventListener('click', performSearch);
            }
            
            if (nearMeBtn) {
                nearMeBtn.addEventListener('click', getNearMeLocation);
            }
            
            
            // Load Google Maps script
            if (!window.googleMapsScriptLoaded) {
                window.googleMapsScriptLoaded = true;
                const script = document.createElement('script');
                script.src = `https://maps.googleapis.com/maps/api/js?key=${mapKey}&callback=initFestivitiesMap`;
                script.async = true;
                script.defer = true;
                script.onerror = function() {
                    console.error('Failed to load Google Maps script');
                    mapElement.innerHTML = '<div style="padding: 20px; text-align: center; color: #666;">Error al cargar Google Maps. Por favor, verifica tu API key.</div>';
                };
                document.head.appendChild(script);
                
                window.initFestivitiesMap = function() {
                    initMap();
                };
            } else if (typeof google !== 'undefined' && google.maps) {
                initMap();
            }
            
        })();
    </script>
</x-app-layout>
