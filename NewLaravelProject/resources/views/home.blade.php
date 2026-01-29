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
        
        /* Remove all spacing from main to footer on home page */
        main {
            margin-bottom: 0 !important;
            padding-bottom: 0 !important;
        }
        
        main > * {
            margin-bottom: 0 !important;
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
            padding-bottom: 0 !important;
            background: transparent;
            position: relative;
        }
        
        /* Results map section - no spacing */
        .results-map-section {
            margin: 0 !important;
            padding: 0 !important;
            margin-top: 0 !important;
            margin-bottom: 0 !important;
            padding-bottom: 0 !important;
        }
        
        /* Ensure no spacing from any wrapper to footer */
        main .container-fluid,
        main .container-fluid.px-0,
        main #map-search-section,
        main .results-map-section {
            margin-bottom: 0 !important;
            padding-bottom: 0 !important;
        }
        
        /* Map section - no spacing */
        .map-section {
            margin: 0 !important;
            padding: 0 !important;
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }
        
        /* Footer - remove all spacing on home page */
        body > footer.mt-5,
        body > footer,
        footer.border-top.mt-4.py-3,
        footer.mt-4,
        footer.py-3 {
            margin-top: 0 !important;
            padding-top: 0 !important;
            margin-bottom: 0 !important;
        }
        
        /* Ensure no gap between main and footer */
        main + footer {
            margin-top: 0 !important;
        }
        
        /* Remove any spacing from results-map-section to footer */
        .results-map-section + footer,
        #map-search-section + footer,
        .container-fluid + footer,
        .container-fluid.px-0 + footer,
        main + footer,
        body > footer {
            margin-top: 0 !important;
            padding-top: 0 !important;
        }
        
        /* Remove all spacing from container-fluid */
        .container-fluid.px-0 {
            margin-bottom: 0 !important;
            padding-bottom: 0 !important;
        }
        
        /* Ensure results-map-section has no bottom spacing */
        #map-search-section {
            margin-bottom: 0 !important;
            padding-bottom: 0 !important;
            background: transparent !important;
        }
        
        /* Remove spacing from results-map-container */
        .results-map-container {
            margin-bottom: 0 !important;
        }
        
        /* Ensure footer has no top spacing and no white background padding */
        footer {
            margin-top: 0 !important;
            padding-top: 0 !important;
        }
        
        /* Remove any white background or spacing above footer */
        footer.border-top.mt-4.py-3::before,
        footer.border-top.mt-4.py-3::after {
            display: none !important;
        }
        
        /* Ensure no white space above footer */
        body > footer.border-top.mt-4.py-3 {
            margin-top: 0 !important;
            padding-top: 0 !important;
        }
        
        /* Ensure footer border is visible on home page */
        body > footer.footer-enhanced {
            border-top: 2px solid rgba(255, 255, 255, 0.8) !important;
            position: relative;
            z-index: 1001 !important;
        }
        
        /* Ensure hero elements don't overlap footer */
        .hero-banner {
            position: relative;
            z-index: 2;
        }
        
        footer.footer-enhanced {
            z-index: 1001 !important;
        }
        
        /* Remove any white background padding/spacing above footer */
        html body footer.border-top.mt-4.py-3 {
            margin-top: 0 !important;
            padding-top: 0 !important;
            margin-bottom: 0 !important;
        }
        
        /* Remove any element that might create white space before footer */
        main + footer,
        .results-map-section + footer,
        #map-search-section + footer {
            margin-top: 0 !important;
            padding-top: 0 !important;
        }
        
        /* Remove any whitespace/spacing from navbar */
        nav.navbar {
            margin-bottom: 0 !important;
        }
        
        /* Add padding to footer container */
        html body footer.border-top.mt-4.py-3 div.container {
            padding-left: var(--spacing-lg);
            padding-right: var(--spacing-lg);
        }
        
        /* Responsive padding for footer container */
        @media (max-width: 768px) {
            html body footer.border-top.mt-4.py-3 div.container {
                padding-left: var(--spacing-md);
                padding-right: var(--spacing-md);
            }
        }
        
        @media (max-width: 576px) {
            html body footer.border-top.mt-4.py-3 div.container {
                padding-left: var(--spacing-sm);
                padding-right: var(--spacing-sm);
            }
        }
    </style>
    
    <!-- Hero Section with Integrated Search - Premium Redesign -->
    <div class="hero-banner">
        <div class="hero-background" style="background-image: url('/storage/hero-1.png');"></div>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="container">
                <!-- Hero Tagline - Enhanced Typography & Hierarchy -->
                <div class="hero-tagline text-center">
                    <h1 class="hero-title brand-text">Descubre la magia de las festividades españolas</h1>
                    <p class="hero-subtitle">Encuentra las mejores celebraciones y tradiciones en toda España</p>
                </div>

                <!-- Premium Search Interface -->
                <div class="hero-search-container">
                    <div class="hero-search-wrapper">
                        <!-- Main Search Bar -->
                        <div class="hero-search-bar">
                            <!-- Search Text Field -->
                            <div class="search-input-group">
                                <i class="bi bi-search search-icon"></i>
                                <input type="text" id="map-search-query" class="search-input" 
                                       placeholder="San Fermín, Feria de Abril, Fallas...">
                            </div>
                            
                            <!-- Vertical Divider -->
                            <div class="search-divider"></div>
                            
                            <!-- Province Dropdown -->
                            <div class="search-select-group">
                                <i class="bi bi-geo-alt-fill select-icon"></i>
                                <select id="map-province-filter" class="search-select">
                                    <option value="">Todas las provincias</option>
                                    @foreach(config('provinces.provinces') as $province)
                                        <option value="{{ $province }}">{{ $province }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Search Button -->
                            <button id="map-search-btn" class="search-btn" type="button">
                                <span class="search-btn-text">Buscar</span>
                                <i class="bi bi-arrow-right search-btn-icon"></i>
                            </button>
                        </div>
                        
                        <!-- Location Button -->
                        <button id="map-near-me-btn" class="near-me-btn" type="button">
                            <i class="bi bi-geo-alt-fill"></i>
                            <span>Cerca de mí</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Results and Map Section - Polished Professional Design -->
        <div id="map-search-section" class="results-map-section">
            <!-- Glassy Container Wrapper -->
            <div class="glassy-container-wrapper">
                <!-- Main Content: Results + Map Side-by-Side -->
                <div class="results-map-container" id="results-map-container">
                <!-- Results Grid Section -->
                <div class="results-grid-section">
                    <div id="map-results-container" class="results-grid-wrapper d-none">
                        <!-- Professional Results Grid -->
                        <div id="map-results-grid" class="festivities-grid">
                            <!-- Festivities cards will be loaded here -->
                        </div>
                        
                        <!-- Loading More Indicator -->
                        <div id="map-loading-more" class="loading-more-indicator d-none">
                            <div class="loading-skeleton-grid">
                                <div class="skeleton-card"></div>
                                <div class="skeleton-card"></div>
                                <div class="skeleton-card"></div>
                            </div>
                        </div>
                        
                        <!-- View All Link -->
                        <div id="map-view-all" class="view-all-container d-none">
                            <div class="view-all-wrapper">
                                <a id="map-view-all-link" href="#" class="view-all-link">
                                    <span>Ver todas las festividades</span>
                                    <span class="view-all-count" id="map-total-count">0</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Professional No Results State -->
                    <div id="map-no-results" class="no-results-message d-none">
                        <div class="no-results-content">
                            <div class="no-results-illustration">
                                <div class="no-results-icon">
                                    <i class="bi bi-compass"></i>
                                </div>
                                <div class="no-results-shape"></div>
                            </div>
                            <h3 class="no-results-title">No encontramos festividades</h3>
                            <p class="no-results-text">Intenta ajustar tus filtros, mover el mapa o cambiar la búsqueda para ver más resultados.</p>
                            <div class="no-results-suggestions">
                                <button class="suggestion-btn" onclick="document.getElementById('map-province-filter').value = ''; document.getElementById('map-province-filter').dispatchEvent(new Event('change'));">
                                    <i class="bi bi-x-circle"></i>
                                    <span>Limpiar filtros</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Polished Map Section -->
                <div class="map-section" id="map-section">
                    <div id="festivities-map" class="festivities-map-container">
                        <div class="map-loading-overlay">
                            <div class="map-loading-spinner"></div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>

    <style>
        /* ============================================
           PREMIUM HOME PAGE REDESIGN
           Design System: Trustworthy, Editorial, Discoverable, Scalable
           ============================================ */
        
        /* Design Tokens */
        :root {
            --spacing-xs: 0.25rem;
            --spacing-sm: 0.5rem;
            --spacing-md: 1rem;
            --spacing-lg: 1.5rem;
            --spacing-xl: 2rem;
            --spacing-2xl: 3rem;
            --spacing-3xl: 4rem;
            
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
            --radius-full: 9999px;
            
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.07);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px rgba(0, 0, 0, 0.15);
            --shadow-2xl: 0 25px 50px rgba(0, 0, 0, 0.25);
        }
        
        /* ============================================
           HERO SECTION - Premium Redesign
           ============================================ */
        .hero-banner {
            position: relative;
            min-height: 100vh;
            height: auto;
            overflow: visible;
            margin: 0;
            padding: 0;
            margin-bottom: -20px;
            background-color: #0F172A;
            z-index: 2;
        }
        
        /* Mobile: Reduce hero height */
        @media (max-width: 992px) {
            .hero-banner {
                min-height: auto;
                height: auto;
            }
        }
        
        @media (max-width: 768px) {
            .hero-banner {
                min-height: auto;
                height: auto;
            }
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
            opacity: 0.75;
        }
        
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            padding-bottom: 2rem;
            background: linear-gradient(
                180deg,
                rgba(15, 23, 42, 0.4) 0%,
                rgba(15, 23, 42, 0.6) 40%,
                rgba(15, 23, 42, 0.85) 70%,
                rgba(15, 23, 42, 0.95) 100%
            );
            z-index: 1;
            overflow: hidden;
        }
        
        .hero-content {
            position: relative;
            flex: 0 0 auto;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            z-index: 1;
            padding: var(--spacing-3xl) var(--spacing-lg) var(--spacing-2xl);
        }
        
        /* Mobile: Reduce padding */
        @media (max-width: 768px) {
            .hero-content {
                padding: var(--spacing-xl) var(--spacing-md) var(--spacing-lg);
            }
        }
        
        @media (max-width: 576px) {
            .hero-content {
                padding: var(--spacing-lg) var(--spacing-sm) var(--spacing-md);
            }
        }
        
        .hero-tagline {
            text-align: center;
            margin: 0 auto var(--spacing-2xl) auto;
            max-width: 900px;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 400;
            line-height: 1.1;
            letter-spacing: -0.02em;
            color: #FFFFFF;
            margin: 0 0 var(--spacing-md) 0;
            text-shadow: 0 2px 20px rgba(0, 0, 0, 0.3), 0 4px 40px rgba(0, 0, 0, 0.2);
        }
        
        /* Responsive title sizes */
        @media (max-width: 1200px) {
            .hero-title {
                font-size: 3rem;
            }
        }
        
        @media (max-width: 992px) {
            .hero-title {
                font-size: 2.5rem;
            }
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }
        }
        
        @media (max-width: 576px) {
            .hero-title {
                font-size: 1.75rem;
            }
        }
        
        @media (max-width: 400px) {
            .hero-title {
                font-size: 1.5rem;
            }
        }
        
        .hero-subtitle {
            font-size: 1.25rem;
            font-weight: 400;
            line-height: 1.5;
            color: rgba(255, 255, 255, 0.95);
            margin: 0;
            text-shadow: 0 1px 10px rgba(0, 0, 0, 0.2);
        }
        
        /* Responsive subtitle sizes */
        @media (max-width: 1200px) {
            .hero-subtitle {
                font-size: 1.125rem;
            }
        }
        
        @media (max-width: 768px) {
            .hero-subtitle {
                font-size: 1rem;
            }
        }
        
        @media (max-width: 576px) {
            .hero-subtitle {
                font-size: 0.9375rem;
            }
        }
        
        @media (max-width: 400px) {
            .hero-subtitle {
                font-size: 0.875rem;
            }
        }
        
        /* Premium Search Interface */
        .hero-search-container {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 var(--spacing-md);
        }
        
        /* Ensure Bootstrap container is responsive */
        .hero-content .container {
            width: 100%;
            max-width: 100%;
            padding-left: 0;
            padding-right: 0;
        }
        
        /* Mobile: Full width container */
        @media (max-width: 992px) {
            .hero-search-container {
                max-width: 100%;
                padding: 0 var(--spacing-md);
            }
        }
        
        @media (max-width: 768px) {
            .hero-search-container {
                padding: 0 var(--spacing-sm);
            }
        }
        
        @media (max-width: 576px) {
            .hero-search-container {
                width: 100%;
                padding: 0 var(--spacing-xs);
            }
        }
        
        .hero-search-wrapper {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
            flex-wrap: wrap;
            justify-content: center;
        }
        
        /* Mobile: Stack wrapper elements */
        @media (max-width: 768px) {
            .hero-search-wrapper {
                flex-direction: column;
                align-items: stretch;
                gap: var(--spacing-sm);
            }
        }
        
        .hero-search-bar {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px) saturate(180%);
            border-radius: var(--radius-full);
            box-shadow: var(--shadow-2xl), 0 0 0 1px rgba(255, 255, 255, 0.1) inset;
            padding: var(--spacing-xs);
            flex: 1;
            min-width: 320px;
            max-width: 800px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Mobile: Stack search bar elements */
        @media (max-width: 768px) {
            .hero-search-bar {
                min-width: auto;
                max-width: 100%;
                flex-direction: column;
                align-items: stretch;
                border-radius: var(--radius-lg);
                padding: var(--spacing-sm);
            }
        }
        
        .hero-search-bar:focus-within {
            box-shadow: var(--shadow-2xl), 0 0 0 3px rgba(254, 177, 1, 0.2);
            transform: translateY(-2px);
        }
        
        .search-input-group {
            display: flex;
            align-items: center;
            flex: 1;
            min-width: 0;
            padding: 0 var(--spacing-md);
        }
        
        /* Mobile: Adjust input group */
        @media (max-width: 768px) {
            .search-input-group {
                padding: var(--spacing-sm);
                border-bottom: 1px solid #E5E7EB;
                margin-bottom: var(--spacing-sm);
            }
        }
        
        .search-icon {
            font-size: 1.125rem;
            color: #6B7280;
            margin-right: var(--spacing-sm);
            flex-shrink: 0;
        }
        
        .search-input {
            flex: 1;
            border: none;
            background: transparent;
            font-size: 1rem;
            font-weight: 400;
            color: #1F2937;
            padding: var(--spacing-md) 0;
            outline: none;
            min-width: 0;
        }
        
        .search-input::placeholder {
            color: #9CA3AF;
            font-weight: 400;
        }
        
        .search-divider {
            width: 1px;
            height: 32px;
            background: linear-gradient(to bottom, transparent, #E5E7EB, transparent);
            flex-shrink: 0;
        }
        
        /* Mobile: Hide divider */
        @media (max-width: 768px) {
            .search-divider {
                display: none;
            }
        }
        
        .search-select-group {
            display: flex;
            align-items: center;
            padding: 0 var(--spacing-md);
            position: relative;
            min-width: 180px;
        }
        
        /* Mobile: Adjust select group */
        @media (max-width: 768px) {
            .search-select-group {
                padding: var(--spacing-sm);
                min-width: auto;
                border-top: none;
            }
        }
        
        .select-icon {
            font-size: 1rem;
            color: #1FA4A9;
            margin-right: var(--spacing-sm);
            flex-shrink: 0;
        }
        
        .search-select {
            flex: 1;
            border: none;
            background: transparent;
            font-size: 1rem;
            font-weight: 500;
            color: #1F2937;
            padding: var(--spacing-md) 0;
            outline: none;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236B7280' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right center;
            padding-right: var(--spacing-xl);
        }
        
        .search-btn {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            background: linear-gradient(135deg, #FEB101 0%, #F59E0B 100%);
            color: #FFFFFF;
            border: none;
            border-radius: var(--radius-full);
            padding: var(--spacing-md) var(--spacing-xl);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
            box-shadow: 0 4px 12px rgba(254, 177, 1, 0.3);
        }
        
        /* Mobile: Full width button */
        @media (max-width: 768px) {
            .search-btn {
                width: 100%;
                justify-content: center;
                margin-top: var(--spacing-sm);
                border-radius: var(--radius-md);
            }
        }
        
        .search-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(254, 177, 1, 0.4);
            background: linear-gradient(135deg, #F59E0B 0%, #FEB101 100%);
        }
        
        .search-btn:active {
            transform: translateY(0);
        }
        
        .search-btn-icon {
            font-size: 1.125rem;
        }
        
        .near-me-btn {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            color: #FFFFFF;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: var(--radius-full);
            padding: var(--spacing-md) var(--spacing-xl);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
        }
        
        /* Mobile: Full width button */
        @media (max-width: 768px) {
            .near-me-btn {
                width: 100%;
                justify-content: center;
                margin-top: 0;
                border-radius: var(--radius-md);
            }
        }
        
        .near-me-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-1px);
        }
        
        /* ============================================
           RESULTS SECTION - Complete Professional Redesign
           ============================================ */
        .results-map-section {
            display: flex;
            flex-direction: column;
            height: auto;
            position: relative;
            z-index: 1;
            min-height: auto;
            max-height: none;
            background: transparent !important;
            margin: 0;
            padding: 0;
            margin-top: calc(var(--spacing-3xl) + var(--spacing-2xl) - 20px);
            margin-bottom: 0 !important;
            padding-bottom: 0 !important;
            position: relative;
            z-index: 2;
            width: 100%;
        }
        
        /* Mobile: Adjust margin-top */
        @media (max-width: 992px) {
            .results-map-section {
                margin-top: var(--spacing-xl);
            }
        }
        
        @media (max-width: 768px) {
            .results-map-section {
                margin-top: var(--spacing-lg);
                padding: 0 var(--spacing-md);
            }
        }
        
        @media (max-width: 576px) {
            .results-map-section {
                margin-top: var(--spacing-md);
                padding: 0 var(--spacing-sm);
            }
        }
        
        #map-search-section {
            background: transparent !important;
        }
        
        /* Glassy Container Wrapper - Translucent Glass Effect */
        .glassy-container-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0.375rem 0.375rem;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            position: relative;
            overflow: hidden;
        }
        
        .glassy-container-wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, 
                transparent, 
                rgba(255, 255, 255, 0.3) 50%, 
                transparent
            );
            pointer-events: none;
        }
        
        /* Responsive adjustments for glassy container */
        @media (max-width: 992px) {
            .glassy-container-wrapper {
                padding: 0.25rem 0.25rem;
                border-radius: 20px;
            }
        }
        
        @media (max-width: 768px) {
            .glassy-container-wrapper {
                padding: 0.25rem 0.25rem;
                border-radius: 16px;
                margin: 0 var(--spacing-md);
            }
        }
        
        @media (max-width: 576px) {
            .glassy-container-wrapper {
                margin: 0 var(--spacing-sm);
                padding: 0.25rem 0.25rem;
            }
        }
        
        
        .results-map-container {
            flex: 0 0 auto;
            display: flex !important;
            flex-direction: row;
            min-height: 0;
            overflow: hidden;
            gap: 0;
            position: relative;
            height: calc((min(1200px, 100vw - 4rem) - 450px - 2rem) / 3 * 1.2 + 4rem);
            width: 100%;
            max-width: 100%;
            margin: 0;
            background: transparent;
            border: none;
            border-radius: 16px;
            padding-bottom: 1rem;
            box-sizing: border-box;
        }

        @media (min-width: 1401px) {
            .results-map-container {
                height: calc((1200px - 450px - 2rem) / 3 * 1.2 + 4rem);
            }
        }

        .results-map-container.map-visible {
            height: calc((min(1200px, 100vw - 4rem) - 450px - 2rem) / 3 * 1.2 + 4rem);
        }

        @media (min-width: 1401px) {
            .results-map-container.map-visible {
                height: calc((1200px - 450px - 2rem) / 3 * 1.2 + 4rem);
            }
        }
        
        /* Tablet: Simplify height calculations */
        @media (max-width: 992px) {
            .results-map-container {
                flex-direction: column;
                height: auto;
                min-height: auto;
                max-width: 100%;
                width: 100%;
                border-radius: 16px;
                padding-bottom: 0;
            }
            
            .results-map-container.map-visible {
                height: auto;
            }
        }
        
        /* Mobile: Further simplify */
        @media (max-width: 768px) {
            .results-map-container {
                border-radius: 12px;
                margin: 0 auto;
                width: 100%;
                max-width: 100%;
            }
        }
        
        .results-grid-section {
            flex: 0 0 auto;
            display: block;
            background: #FAFAFA;
            overflow-x: auto;
            overflow-y: hidden;
            transition: margin-right 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            height: calc((min(1200px, 100vw - 4rem) - 450px - 2rem) / 3 * 1.2 + 4rem);
            max-height: calc((min(1200px, 100vw - 4rem) - 450px - 2rem) / 3 * 1.2 + 4rem);
            min-width: 0;
            border-top-left-radius: 16px;
            border-top-right-radius: 0;
            border-bottom-left-radius: 16px;
            border-bottom-right-radius: 0;
            width: auto;
            min-width: calc(min(1200px, 100vw - 4rem) - 450px);
            position: relative;
            padding-bottom: 0;
            /* Clip scrollbar buttons to respect border-radius - bottom-left corner */
            clip-path: inset(0 round 16px 0 0 16px);
        }
        
        /* Tablet: Simplify grid section */
        @media (max-width: 992px) {
            .results-grid-section {
                height: auto;
                max-height: none;
                width: 100% !important;
                min-width: 100% !important;
                max-width: 100% !important;
                overflow-x: auto;
                overflow-y: visible;
                border-radius: 16px 16px 0 0;
                clip-path: none;
                display: block !important;
                margin: 0 auto;
                padding-left: 0;
                padding-right: 0;
            }
            
            .results-map-container.map-visible .results-grid-section {
                margin-right: 0;
                height: auto;
                max-height: none;
                width: 100% !important;
                min-width: 100% !important;
                max-width: 100% !important;
                border-radius: 16px 16px 0 0;
            }
            
            /* Ensure results are visible even when map is hidden on mobile/tablet */
            .results-map-container:not(.map-visible) .results-grid-section {
                border-radius: 16px;
                display: block !important;
                width: 100% !important;
                min-width: 100% !important;
                max-width: 100% !important;
            }
        }
        
        /* Mobile: Further adjustments */
        @media (max-width: 768px) {
            .results-grid-section {
                border-radius: 12px 12px 0 0;
                display: block !important;
                width: 100% !important;
                min-width: 100% !important;
                max-width: 100% !important;
                margin: 0 auto;
                padding-left: 0;
                padding-right: 0;
            }
            
            /* Ensure results are visible even when map is hidden on mobile */
            .results-map-container:not(.map-visible) .results-grid-section {
                border-radius: 12px;
                display: block !important;
                width: 100% !important;
                min-width: 100% !important;
                max-width: 100% !important;
            }
        }
        
        .results-grid-section::-webkit-scrollbar {
            height: 12px;
        }
        
        .results-grid-section::-webkit-scrollbar-track {
            background: #FAFAFA;
            border-bottom-left-radius: 16px;
            border-bottom-right-radius: 0;
            /* Ensure track respects border-radius */
            margin-left: 0;
            margin-right: 0;
            margin-bottom: 0;
            /* Clip the track to respect border-radius */
            border-bottom-left-radius: 16px;
        }
        
        .results-grid-section::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 6px;
        }
        
        .results-grid-section::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 0, 0.3);
        }
        
        /* Hide and clip default scrollbar buttons */
        .results-grid-section::-webkit-scrollbar-button {
            display: none;
            width: 0;
            height: 0;
        }
        
        /* Clip scrollbar buttons if they appear */
        .results-grid-section::-webkit-scrollbar-button:single-button:horizontal:decrement {
            clip-path: inset(0 0 0 16px round 0 0 0 16px);
        }
        
        .results-grid-section::-webkit-scrollbar-button:single-button:horizontal:increment {
            clip-path: none;
        }
        
        /* Firefox scrollbar */
        .results-grid-section {
            scrollbar-width: thin;
            scrollbar-color: rgba(0, 0, 0, 0.2) #FAFAFA;
        }
        
        @media (min-width: 1401px) {
            .results-grid-section {
                height: calc((1200px - 450px - 2rem) / 3 * 1.2 + 4rem);
                max-height: calc((1200px - 450px - 2rem) / 3 * 1.2 + 4rem);
                width: calc(1200px - 450px);
            }
        }

        .results-map-container.map-visible .results-grid-section {
            margin-right: 0;
            height: calc((min(1200px, 100vw - 4rem) - 450px - 2rem) / 3 * 1.2 + 4rem);
            max-height: calc((min(1200px, 100vw - 4rem) - 450px - 2rem) / 3 * 1.2 + 4rem);
        }

        @media (min-width: 1401px) {
            .results-map-container.map-visible .results-grid-section {
                height: calc((1200px - 450px - 2rem) / 3 * 1.2 + 4rem);
                max-height: calc((1200px - 450px - 2rem) / 3 * 1.2 + 4rem);
            }
        }
        
        .results-grid-wrapper {
            padding: 0.5rem;
            padding-bottom: 0.25rem;
            display: block;
            height: 100%;
            width: 100%;
            background: #FAFAFA;
            margin: 0 auto;
        }
        
        .results-grid-wrapper.scrollable {
            width: max-content;
            min-width: 100%;
        }
        
        /* Mobile: Ensure wrapper is centered */
        @media (max-width: 768px) {
            .results-grid-wrapper {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 auto;
                padding-left: var(--spacing-sm);
                padding-right: var(--spacing-sm);
            }
            
            .results-grid-wrapper.scrollable {
                width: 100% !important;
                min-width: 100% !important;
            }
        }
        
        .festivities-grid {
            display: grid;
            grid-template-rows: 1fr;
            grid-auto-rows: 1fr;
            gap: 0.75rem;
            margin-bottom: var(--spacing-md);
            align-content: stretch;
            align-items: stretch;
            width: 100%;
            height: 100%;
            padding: 0.25rem;
            grid-template-columns: repeat(4, 1fr);
        }
        
        /* Tablet Landscape: 3 columns */
        @media (max-width: 1400px) {
            .festivities-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        /* Tablet Portrait: 2 columns */
        @media (max-width: 992px) {
            .festivities-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: var(--spacing-md);
                height: auto;
            }
        }
        
        /* Mobile: 1 column */
        @media (max-width: 768px) {
            .festivities-grid {
                grid-template-columns: 1fr;
                gap: var(--spacing-md);
                height: auto;
            }
        }
        
        /* Small Mobile: Smaller gap */
        @media (max-width: 576px) {
            .festivities-grid {
                gap: var(--spacing-sm);
            }
        }
        
        /* Premium Card Design - Polished */
        .festivity-grid-card {
            position: relative;
            height: 100%;
            width: 100%;
            border-radius: 10px;
            overflow: hidden;
            background: #FFFFFF;
            border: 1px solid #E5E7EB;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            cursor: pointer;
        }
        
        .festivity-grid-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15), 0 6px 12px rgba(0, 0, 0, 0.1);
            border-color: #D1D5DB;
            text-decoration: none;
            color: inherit;
            z-index: 10;
        }
        
        .festivity-grid-card:active {
            transform: translateY(-3px);
        }
        
        .festivity-grid-card-background {
            width: 100%;
            flex: 0 0 68%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(135deg, #FEB101 0%, #F59E0B 100%);
        }
        
        .festivity-grid-card:hover .festivity-grid-card-background {
            transform: scale(1.08);
        }
        
        .festivity-grid-card-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 68%;
            background: linear-gradient(
                to bottom,
                rgba(0, 0, 0, 0) 0%,
                rgba(0, 0, 0, 0.05) 60%,
                rgba(0, 0, 0, 0.2) 80%,
                rgba(0, 0, 0, 0.4) 100%
            );
            z-index: 1;
            pointer-events: none;
        }
        
        /* Remove overlay on mobile/phone view */
        @media (max-width: 768px) {
            .festivity-grid-card-overlay {
                display: none;
            }
        }
        
        /* Polished Card Content */
        .festivity-grid-card-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 0.875rem;
            background: #FFFFFF;
            z-index: 2;
            position: relative;
        }
        
        .festivity-grid-card-title {
            font-size: 0.9375rem;
            font-weight: 600;
            color: #111827;
            margin: 0 0 0.625rem 0;
            line-height: 1.35;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            letter-spacing: -0.01em;
        }
        
        .festivity-grid-card-meta {
            display: flex;
            flex-direction: column;
            gap: 0.375rem;
            margin-top: auto;
        }
        
        .festivity-grid-card-location {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.8125rem;
            font-weight: 400;
            color: #6B7280;
        }
        
        .festivity-grid-card-location i {
            font-size: 0.8125rem;
            color: #1FA4A9;
        }
        
        .festivity-grid-card-votes {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            z-index: 10;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            padding: 0.375rem 0.625rem;
            border-radius: 20px;
            font-size: 0.8125rem;
            font-weight: 600;
            color: #92400E;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }
        
        .festivity-grid-card-votes i {
            color: #F59E0B;
            font-size: 0.875rem;
            filter: drop-shadow(0 1px 2px rgba(245, 158, 11, 0.4));
        }
        
        .festivity-grid-card-votes span {
            color: #92400E;
            font-weight: 600;
        }
        
        .festivity-grid-card-badge {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            z-index: 3;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(12px);
            padding: 0.375rem 0.625rem;
            border-radius: 8px;
            font-size: 0.6875rem;
            font-weight: 600;
            color: #1F2937;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.8);
        }
        
        /* Compact Loading States */
        .loading-more-indicator {
            padding: 1.5rem 0;
        }
        
        .loading-skeleton-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.75rem;
        }
        
        .skeleton-card {
            aspect-ratio: 0.75;
            background: linear-gradient(90deg, #F3F4F6 25%, #E5E7EB 50%, #F3F4F6 75%);
            background-size: 200% 100%;
            animation: skeleton-loading 1.5s ease-in-out infinite;
            border-radius: 12px;
            border: 1px solid #F3F4F6;
        }
        
        @keyframes skeleton-loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        /* Polished View All Section */
        .view-all-container {
            padding: 1.25rem 0;
            border-top: 1px solid #E5E7EB;
            margin-top: 0.5rem;
        }
        
        .view-all-wrapper {
            text-align: center;
        }
        
        .view-all-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            background: #FFFFFF;
            border: 1.5px solid #E5E7EB;
            color: #374151;
            font-weight: 600;
            font-size: 0.875rem;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }
        
        .view-all-link:hover {
            border-color: #1FA4A9;
            background: #F0FDFF;
            color: #1FA4A9;
            gap: 0.625rem;
            box-shadow: 0 2px 4px rgba(31, 164, 169, 0.1);
            transform: translateY(-1px);
        }
        
        .view-all-count {
            font-weight: 600;
            color: #6B7280;
        }
        
        .view-all-link i {
            font-size: 0.875rem;
            transition: transform 0.2s ease;
        }
        
        .view-all-link:hover i {
            transform: translateX(3px);
        }
        
        /* Professional No Results State */
        .no-results-message {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: var(--spacing-xl);
            min-height: auto;
            min-width: 100%;
            width: 100%;
        }
        
        .no-results-content {
            text-align: center;
            max-width: 400px;
            padding: var(--spacing-md);
        }
        
        .no-results-illustration {
            position: relative;
            width: 100px;
            height: 100px;
            margin: 0 auto var(--spacing-lg);
        }
        
        .no-results-icon {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 2;
            box-shadow: 0 4px 12px rgba(254, 177, 1, 0.2);
        }
        
        .no-results-icon i {
            font-size: 2.5rem;
            color: #F59E0B;
        }
        
        .no-results-shape {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 120%;
            height: 120%;
            border: 2px dashed #FDE68A;
            border-radius: 50%;
            animation: pulse-ring 2s ease-in-out infinite;
        }
        
        @keyframes pulse-ring {
            0%, 100% {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1);
            }
            50% {
                opacity: 0.5;
                transform: translate(-50%, -50%) scale(1.1);
            }
        }
        
        .no-results-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
            margin: 0 0 var(--spacing-sm) 0;
            letter-spacing: -0.02em;
        }
        
        .no-results-text {
            font-size: 0.875rem;
            color: #6B7280;
            margin: 0 0 var(--spacing-md) 0;
            line-height: 1.5;
        }
        
        .no-results-suggestions {
            display: flex;
            justify-content: center;
            gap: var(--spacing-md);
        }
        
        .suggestion-btn {
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-sm);
            padding: var(--spacing-sm) var(--spacing-lg);
            background: #FFFFFF;
            border: 2px solid #E5E7EB;
            border-radius: var(--radius-lg);
            color: #374151;
            font-weight: 600;
            font-size: 0.9375rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .suggestion-btn:hover {
            border-color: #FEB101;
            background: #FEF3C7;
            color: #92400E;
        }
        
        .suggestion-btn i {
            font-size: 1rem;
        }
        
        /* ============================================
           MAP SECTION - Professional Design
           ============================================ */
        .map-section {
            flex: 0 0 450px !important;
            position: relative;
            background: #FFFFFF;
            border-left: 2px solid #F3F4F6;
            display: flex !important;
            flex-direction: column;
            overflow: hidden;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            height: calc((min(1200px, 100vw - 4rem) - 450px - 2rem) / 3 * 1.2 + 4rem);
            opacity: 1 !important;
            visibility: visible !important;
            min-width: 450px;
            width: 450px;
            max-width: 100%;
            box-shadow: -2px 0 8px rgba(0, 0, 0, 0.04);
            border-top-left-radius: 0;
            border-top-right-radius: 20px;
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 20px;
        }
        
        @media (min-width: 1401px) {
            .map-section {
                height: calc((1200px - 450px - 2rem) / 3 * 1.2 + 4rem);
            }
        }
        
        /* Tablet: Stack map below results */
        @media (max-width: 992px) {
            .map-section {
                flex: 0 0 auto;
                width: 100%;
                min-width: 100%;
                height: 400px;
                min-height: 400px;
                border-left: none;
                border-top: 1px solid #E5E7EB;
                border-radius: 0 0 18px 18px;
                box-shadow: none;
            }
            
            .results-map-container:not(.map-visible) .map-section {
                display: none !important;
            }
        }
        
        /* Mobile: Smaller map height */
        @media (max-width: 768px) {
            .map-section {
                height: 350px;
                min-height: 350px;
                border-radius: 0 0 14px 14px;
            }
        }
        
        /* Small Mobile: Even smaller map */
        @media (max-width: 576px) {
            .map-section {
                height: 300px;
                min-height: 300px;
            }
        }
        
        /* Extra Small Mobile: Minimal map */
        @media (max-width: 400px) {
            .map-section {
                height: 250px;
                min-height: 250px;
            }
        }
        
        .festivities-map-container {
            flex: 1 1 auto;
            width: 100%;
            min-height: 0;
            position: relative;
            background: #F9FAFB;
            border-radius: 0;
        }
        
        .map-loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: #F3F4F6;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: var(--spacing-md);
            z-index: 1;
        }
        
        .map-loading-overlay.hidden {
            display: none;
        }
        
        .map-loading-spinner {
            width: 48px;
            height: 48px;
            border: 4px solid #E5E7EB;
            border-top-color: #1FA4A9;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        
        
        #festivities-map {
            width: 100%;
            height: 100%;
            border: none;
            margin: 0;
            padding: 0;
            pointer-events: none;
        }
        
        /* Hide Google Maps controls, buttons, and attribution links */
        #festivities-map .gm-style-cc,
        #festivities-map .gm-style-cc a,
        #festivities-map a[href^="https://maps.google.com"],
        #festivities-map a[href^="https://www.google.com/maps"],
        #festivities-map .gmnoprint,
        #festivities-map button,
        #festivities-map .gm-control-active,
        #festivities-map .gm-fullscreen-control,
        #festivities-map .gm-svpc,
        #festivities-map .gmnoprint a,
        .festivities-map-container .gm-style-cc,
        .festivities-map-container .gm-style-cc a,
        .festivities-map-container a[href^="https://maps.google.com"],
        .festivities-map-container a[href^="https://www.google.com/maps"],
        .festivities-map-container .gmnoprint,
        .festivities-map-container button {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
        }
        
        /* Hide all Google Maps UI elements and attribution */
        .gm-style > div:first-child > div:last-child,
        .gm-style-cc,
        .gmnoprint {
            display: none !important;
        }
        
        /* But allow pointer events on markers if needed */
        #festivities-map .gm-marker {
            pointer-events: auto;
        }
        
        /* Polished Card Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .festivity-grid-card {
            animation: fadeInUp 0.5s cubic-bezier(0.4, 0, 0.2, 1) backwards;
        }
        
        .festivity-grid-card:nth-child(1) { animation-delay: 0.05s; }
        .festivity-grid-card:nth-child(2) { animation-delay: 0.1s; }
        .festivity-grid-card:nth-child(3) { animation-delay: 0.15s; }
        .festivity-grid-card:nth-child(4) { animation-delay: 0.2s; }
        .festivity-grid-card:nth-child(5) { animation-delay: 0.25s; }
        .festivity-grid-card:nth-child(6) { animation-delay: 0.3s; }
        .festivity-grid-card:nth-child(7) { animation-delay: 0.35s; }
        .festivity-grid-card:nth-child(8) { animation-delay: 0.4s; }
        
        /* ============================================
           RESPONSIVE DESIGN
           ============================================ */
        
        /* Tablet Landscape (992px - 1400px) */
        @media (max-width: 1400px) {
            .results-map-container {
                max-width: min(1200px, 100vw - 2rem);
            }
        }
        
        /* Tablet Portrait (768px - 1200px) */
        @media (max-width: 1200px) {
            .hero-title {
                font-size: 3rem;
            }
            
            .hero-subtitle {
                font-size: 1.125rem;
            }
        }
        
        /* Tablet and Small Desktop (768px - 992px) */
        @media (max-width: 992px) {
            .hero-banner {
                min-height: auto;
                height: auto;
                padding-bottom: 2rem;
            }
            
            .hero-content {
                padding: var(--spacing-2xl) var(--spacing-lg) var(--spacing-xl);
            }
            
            .hero-title {
                font-size: 2.5rem;
                line-height: 1.2;
            }
            
            .hero-subtitle {
                font-size: 1.125rem;
            }
            
            .hero-tagline {
                margin-bottom: var(--spacing-xl);
            }
            
            .hero-search-container {
                max-width: 100%;
            }
            
            .results-map-section {
                margin-top: var(--spacing-xl);
            }
            
            .results-grid-wrapper {
                padding: var(--spacing-md);
            }
            
            .no-results-message {
                min-height: auto;
                padding: var(--spacing-lg);
            }
        }
        
        /* Mobile Landscape and Small Tablets (576px - 768px) */
        @media (max-width: 768px) {
            .hero-banner {
                min-height: auto;
                height: auto;
                padding-bottom: var(--spacing-xl);
            }
            
            .hero-content {
                padding: var(--spacing-xl) var(--spacing-md) var(--spacing-lg);
            }
            
            .hero-title {
                font-size: 2rem;
                margin-bottom: var(--spacing-sm);
                line-height: 1.2;
            }
            
            .hero-subtitle {
                font-size: 1rem;
                line-height: 1.5;
            }
            
            .hero-tagline {
                margin-bottom: var(--spacing-lg);
            }
            
            .search-input {
                font-size: 0.9375rem;
            }
            
            .search-select {
                font-size: 0.9375rem;
            }
            
            .search-btn {
                padding: var(--spacing-md) var(--spacing-lg);
            }
            
            .results-map-section {
                margin-top: var(--spacing-lg);
                padding: 0 var(--spacing-md);
            }
            
            .results-grid-wrapper {
                padding: var(--spacing-sm);
            }
            
            .loading-skeleton-grid {
                grid-template-columns: 1fr;
                gap: var(--spacing-md);
            }
            
            .no-results-message {
                min-height: auto;
                padding: var(--spacing-md);
            }
            
            .no-results-illustration {
                width: 80px;
                height: 80px;
                margin-bottom: var(--spacing-md);
            }
            
            .no-results-icon i {
                font-size: 2rem;
            }
            
            .no-results-title {
                font-size: 1.125rem;
            }
            
            .no-results-text {
                font-size: 0.8125rem;
            }
        }
        
        /* Mobile Portrait (up to 576px) */
        @media (max-width: 576px) {
            .hero-banner {
                min-height: auto;
                height: auto;
                padding-bottom: var(--spacing-lg);
            }
            
            .hero-content {
                padding: var(--spacing-lg) var(--spacing-sm) var(--spacing-md);
            }
            
            .hero-title {
                font-size: 1.75rem;
                margin-bottom: var(--spacing-xs);
                line-height: 1.2;
            }
            
            .hero-subtitle {
                font-size: 0.9375rem;
                line-height: 1.4;
            }
            
            .hero-tagline {
                margin-bottom: var(--spacing-md);
            }
            
            .hero-search-container {
                width: 100%;
            }
            
            .hero-search-wrapper {
                gap: var(--spacing-xs);
            }
            
            .hero-search-bar {
                border-radius: var(--radius-md);
                padding: var(--spacing-xs);
            }
            
            .search-input-group {
                padding: var(--spacing-xs) var(--spacing-sm);
            }
            
            .search-input {
                font-size: 0.875rem;
                padding: var(--spacing-sm) 0;
            }
            
            .search-icon {
                font-size: 1rem;
            }
            
            .search-select-group {
                padding: var(--spacing-xs) var(--spacing-sm);
            }
            
            .search-select {
                font-size: 0.875rem;
                padding: var(--spacing-sm) 0;
            }
            
            .select-icon {
                font-size: 0.9375rem;
            }
            
            .search-btn {
                padding: var(--spacing-sm) var(--spacing-md);
                font-size: 0.9375rem;
            }
            
            .near-me-btn {
                padding: var(--spacing-sm) var(--spacing-md);
                font-size: 0.9375rem;
            }
            
            .results-map-section {
                margin-top: var(--spacing-md);
                padding: 0 var(--spacing-sm);
            }
            
            .results-grid-wrapper {
                padding: var(--spacing-xs);
            }
            
            .festivity-grid-card {
                border-radius: 8px;
            }
            
            .festivity-grid-card-content {
                padding: var(--spacing-sm);
            }
            
            .festivity-grid-card-title {
                font-size: 0.875rem;
            }
            
            .festivity-grid-card-location {
                font-size: 0.75rem;
            }
            
            .view-all-link {
                font-size: 0.8125rem;
                padding: var(--spacing-sm) var(--spacing-md);
            }
            
            .no-results-message {
                min-height: auto;
                padding: var(--spacing-sm);
            }
            
            .no-results-illustration {
                width: 70px;
                height: 70px;
                margin-bottom: var(--spacing-sm);
            }
            
            .no-results-icon i {
                font-size: 1.75rem;
            }
            
            .no-results-title {
                font-size: 1rem;
            }
            
            .no-results-text {
                font-size: 0.75rem;
            }
            
            .suggestion-btn {
                font-size: 0.875rem;
                padding: var(--spacing-xs) var(--spacing-md);
            }
        }
        
        /* Extra Small Mobile (up to 400px) */
        @media (max-width: 400px) {
            .hero-title {
                font-size: 1.5rem;
            }
            
            .hero-subtitle {
                font-size: 0.875rem;
            }
            
            .results-grid-wrapper {
                padding: var(--spacing-xs);
            }
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
            const resultsGrid = document.getElementById('map-results-grid');
            const resultsCount = document.getElementById('map-results-count');
            const noResultsAlert = document.getElementById('map-no-results');
            const loadingMore = document.getElementById('map-loading-more');
            
            let map = null;
            let markers = [];
            let currentFestivities = [];
            let filteredFestivities = [];
            let displayedCount = 0;
            let itemsPerLoad = 15; // Load 15 items at a time
            const MAX_DISPLAYED = 15; // Maximum number of festivities to show
            const madridCenter = { lat: 40.4168, lng: -3.7038 };
            const mapKey = '{{ config('services.google.maps_key') }}';
            const viewAllLink = document.getElementById('map-view-all-link');
            const viewAllContainer = document.getElementById('map-view-all');
            const mapTotalCount = document.getElementById('map-total-count');
            
            // Initialize viewAllContainer reference
            if (!viewAllContainer) {
                // Will be set when DOM is ready
            }
            let geocoder = null;
            let isSearching = false;
            
            // Calculate responsive padding for map
            // Moved outside initMap to be accessible from other functions
            const getMapPadding = () => {
                // With the new side-by-side layout, map is smaller, so minimal padding
                return { top: 0, right: 0, bottom: 0, left: 0 };
            };
            
            // Initialize Google Map
            function initMap() {
                if (typeof google === 'undefined' || !google.maps) {
                    console.error('Google Maps not loaded');
                    return;
                }
                
                // Hide map loading overlay
                const mapLoadingOverlay = document.querySelector('.map-loading-overlay');
                if (mapLoadingOverlay) {
                    mapLoadingOverlay.classList.add('hidden');
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
                    zoomControl: false,
                    disableDefaultUI: true,
                    draggable: false,
                    scrollwheel: false,
                    disableDoubleClickZoom: true,
                    keyboardShortcuts: false,
                    gestureHandling: 'none',
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
                
                // Load initial festivities once map is ready (idle event fires when map is fully loaded with bounds)
                let initialLoadDone = false;
                const initialLoadListener = map.addListener('idle', function() {
                    if (!initialLoadDone && map.getBounds()) {
                        initialLoadDone = true;
                        loadFestivitiesForMap();
                        google.maps.event.removeListener(initialLoadListener);
                    }
                });
                
                // Also try loading immediately in case map is already ready
                // This ensures results load even if idle event already fired
                setTimeout(() => {
                    if (!initialLoadDone && map && map.getBounds()) {
                        initialLoadDone = true;
                        loadFestivitiesForMap();
                        google.maps.event.removeListener(initialLoadListener);
                    } else if (!initialLoadDone) {
                        // On mobile, if map isn't ready, load with default bounds
                        initialLoadDone = true;
                        loadFestivitiesForMap();
                        google.maps.event.removeListener(initialLoadListener);
                    }
                }, 500);
                
                // Hide Google Maps UI elements and attribution links
                const hideGoogleMapsUI = () => {
                    // Hide attribution and controls
                    const attributionElements = document.querySelectorAll('.gm-style-cc, .gmnoprint, a[href^="https://maps.google.com"], a[href^="https://www.google.com/maps"]');
                    attributionElements.forEach(el => {
                        if (el) {
                            el.style.display = 'none';
                            el.style.visibility = 'hidden';
                            el.style.opacity = '0';
                        }
                    });
                    
                    // Hide buttons
                    const buttons = document.querySelectorAll('#festivities-map button, .festivities-map-container button');
                    buttons.forEach(btn => {
                        if (btn) {
                            btn.style.display = 'none';
                            btn.style.visibility = 'hidden';
                        }
                    });
                };
                
                // Hide immediately and on intervals (Google Maps loads UI elements dynamically)
                hideGoogleMapsUI();
                setTimeout(hideGoogleMapsUI, 500);
                setTimeout(hideGoogleMapsUI, 1000);
                setTimeout(hideGoogleMapsUI, 2000);
                
                // Use MutationObserver to hide elements as they appear
                const observer = new MutationObserver(hideGoogleMapsUI);
                if (mapElement) {
                    observer.observe(mapElement, {
                        childList: true,
                        subtree: true
                    });
                }
            }
            
            // Load festivities for current map view
            function loadFestivitiesForMap() {
                // On mobile, if map is not initialized, use default bounds (Spain)
                let bounds = null;
                let ne, sw;
                
                if (map) {
                    bounds = map.getBounds();
                }
                
                // Fallback bounds for Spain if map not ready (mobile scenario)
                if (!bounds || !bounds.getNorthEast) {
                    // Use default Spain bounds
                    ne = { lat: () => 44.0, lng: () => 4.5 };
                    sw = { lat: () => 36.0, lng: () => -9.5 };
                } else {
                    ne = bounds.getNorthEast();
                    sw = bounds.getSouthWest();
                }
                
                const province = provinceFilter ? provinceFilter.value : '';
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
                        if (resultsGrid) {
                            resultsGrid.innerHTML = '';
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
                                    <p class="small mb-1"><i class="bi bi-geo-alt"></i> ${festivity.locality?.name || ''}</p>
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
                
                // Update marker count display
                const mapMarkerCount = document.getElementById('map-marker-count');
                if (mapMarkerCount) {
                    mapMarkerCount.textContent = markers.length;
                }
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
            
            // Display results in grid layout
            function displayMapResults(festivities, reset = true) {
                if (!festivities || festivities.length === 0) {
                    if (resultsContainer) resultsContainer.classList.add('d-none');
                    if (noResultsAlert) noResultsAlert.classList.remove('d-none');
                    if (resultsGrid) resultsGrid.innerHTML = '';
                    filteredFestivities = [];
                    displayedCount = 0;
                    return;
                }
                
                // Get current search query
                const query = searchQuery && searchQuery.value ? searchQuery.value.trim() : '';
                
                // Always filter by current query
                if (query) {
                    filteredFestivities = searchFestivities(festivities, query);
                } else {
                    filteredFestivities = festivities;
                }
                
                // Always reset when explicitly resetting - this ensures clean state
                if (reset) {
                    // Clear container immediately to remove ALL old cards
                    if (resultsGrid) resultsGrid.innerHTML = '';
                    displayedCount = 0;
                    filteredFestivities = query ? searchFestivities(festivities, query) : festivities;
                    
                    // Update markers with all festivities in visible area
                    updateMapMarkers(festivities);
                }
                
                if (filteredFestivities.length === 0) {
                    // Hide results container but keep it in DOM for when results appear
                    if (resultsContainer) resultsContainer.classList.add('d-none');
                    // Show "no results" message
                    if (noResultsAlert) noResultsAlert.classList.remove('d-none');
                    if (loadingMore) loadingMore.classList.add('d-none');
                    if (viewAllContainer) viewAllContainer.classList.add('d-none');
                    if (resultsCount) resultsCount.textContent = '0';
                    if (resultsGrid) resultsGrid.innerHTML = '';
                    updateGridColumns();
                    return;
                }
                
                if (noResultsAlert) noResultsAlert.classList.add('d-none');
                if (resultsContainer) resultsContainer.classList.remove('d-none');
                if (resultsCount) resultsCount.textContent = filteredFestivities.length;
                
                // Calculate how many to show (initial load or load more)
                const initialLoad = displayedCount === 0;
                let cardsToShow;
                
                if (initialLoad) {
                    // Show 15 cards initially
                    cardsToShow = Math.min(15, filteredFestivities.length);
                } else {
                    cardsToShow = itemsPerLoad;
                }
                
                // Limit to MAX_DISPLAYED
                const maxIndex = Math.min(filteredFestivities.length, MAX_DISPLAYED);
                const endIndex = Math.min(displayedCount + cardsToShow, maxIndex);
                
                // Show loading indicator if loading more (and not at max)
                if (!initialLoad && endIndex < maxIndex && loadingMore) {
                    loadingMore.classList.remove('d-none');
                } else if (loadingMore) {
                    loadingMore.classList.add('d-none');
                }
                
                // Add cards to grid
                for (let i = displayedCount; i < endIndex; i++) {
                    const festivity = filteredFestivities[i];
                    const card = createFestivityCard(festivity);
                    if (resultsGrid) resultsGrid.appendChild(card);
                }
                
                displayedCount = endIndex;
                if (mapTotalCount) mapTotalCount.textContent = filteredFestivities.length;
                
                // Show "View All" link if there are more than 15 results
                if (viewAllContainer) {
                    if (filteredFestivities.length > MAX_DISPLAYED) {
                        // Show link if we've displayed 15 or if there are more than 15 total
                        viewAllContainer.classList.remove('d-none');
                        updateViewAllLink();
                    } else {
                        // Hide link if 15 or fewer results
                        viewAllContainer.classList.add('d-none');
                    }
                }
                
                // Add scroll listener for infinite scroll
                if (displayedCount < MAX_DISPLAYED && displayedCount < filteredFestivities.length) {
                    addScrollListener();
                } else {
                    removeScrollListener();
                }
                
                // Update grid columns based on number of cards (max 3, min 1)
                updateGridColumns();
                
            }
            
            // Update grid columns and container width based on number of visible cards
            function updateGridColumns() {
                if (!resultsGrid || !resultsGridSection) return;
                const cardCount = resultsGrid.children.length;
                const gap = 12; // 0.75rem = 12px
                const isMobile = window.innerWidth < 768;
                const isTablet = window.innerWidth >= 768 && window.innerWidth < 992;
                const isDesktop = window.innerWidth >= 992;
                
                const gridWrapper = resultsGrid.parentElement;
                
                // Reset all styles first
                resultsGrid.style.gridTemplateColumns = '';
                resultsGrid.style.width = '';
                resultsGrid.style.minWidth = '';
                
                // On mobile/tablet, ensure CSS controls the width
                if (isMobile || isTablet) {
                    resultsGridSection.style.width = '';
                    resultsGridSection.style.minWidth = '';
                    resultsGridSection.style.maxWidth = '';
                    if (gridWrapper) {
                        gridWrapper.style.width = '';
                        gridWrapper.style.minWidth = '';
                        gridWrapper.style.maxWidth = '';
                        gridWrapper.classList.remove('scrollable');
                    }
                    // CSS handles layout on mobile/tablet
                    return;
                }
                
                // Desktop: reset and set styles
                resultsGridSection.style.width = '';
                resultsGridSection.style.minWidth = '';
                resultsGridSection.style.maxWidth = '';
                if (gridWrapper) {
                    gridWrapper.style.width = '';
                    gridWrapper.style.minWidth = '';
                    gridWrapper.style.maxWidth = '';
                    gridWrapper.classList.remove('scrollable');
                }
                
                if (cardCount === 0) {
                    // No cards - reset everything
                    return;
                }
                
                // Desktop: calculate based on available width
                const padding = 16; // 0.5rem * 2 = 16px total padding
                const maxContainerWidth = Math.min(1200, window.innerWidth - 64); // 1200px or viewport - 4rem
                const mapWidth = 450;
                const availableWidth = maxContainerWidth - mapWidth - 32; // minus 2rem gap
                const cardWidth = (availableWidth - (gap * 2) - padding) / 3; // 3 cards with 2 gaps
                
                if (cardCount === 1) {
                    // 1 card - container adjusts to 1 card width
                    const containerWidth = cardWidth + padding;
                    resultsGridSection.style.width = `${containerWidth}px`;
                    resultsGridSection.style.minWidth = `${containerWidth}px`;
                    resultsGridSection.style.maxWidth = `${containerWidth}px`;
                    
                    resultsGrid.style.gridTemplateColumns = `${cardWidth}px`;
                    
                } else if (cardCount === 2) {
                    // 2 cards - container adjusts to 2 cards width
                    const containerWidth = (cardWidth * 2) + gap + padding;
                    resultsGridSection.style.width = `${containerWidth}px`;
                    resultsGridSection.style.minWidth = `${containerWidth}px`;
                    resultsGridSection.style.maxWidth = `${containerWidth}px`;
                    
                    resultsGrid.style.gridTemplateColumns = `repeat(2, ${cardWidth}px)`;
                    
                } else {
                    // 3+ cards - container maintains 3 cards width and allows scroll
                    const containerWidth = (cardWidth * 3) + (gap * 2) + padding;
                    resultsGridSection.style.width = `${containerWidth}px`;
                    resultsGridSection.style.minWidth = `${containerWidth}px`;
                    resultsGridSection.style.maxWidth = `${containerWidth}px`;
                    
                    // Calculate total width for all cards
                    const totalCardsWidth = (cardWidth * cardCount) + (gap * (cardCount - 1));
                    const wrapperWidth = totalCardsWidth + padding;
                    
                    resultsGrid.style.gridTemplateColumns = `repeat(${cardCount}, ${cardWidth}px)`;
                    resultsGrid.style.width = `${wrapperWidth}px`;
                    resultsGrid.style.minWidth = `${wrapperWidth}px`;
                    
                    if (gridWrapper) {
                        gridWrapper.style.width = `${wrapperWidth}px`;
                        gridWrapper.style.minWidth = `${wrapperWidth}px`;
                        gridWrapper.style.maxWidth = 'none';
                        gridWrapper.classList.add('scrollable');
                    }
                }
            }
            
            // Update grid columns on window resize
            let resizeGridTimeout;
            window.addEventListener('resize', function() {
                clearTimeout(resizeGridTimeout);
                resizeGridTimeout = setTimeout(function() {
                    updateGridColumns();
                }, 150);
            });
            
            // Create professional grid festivity card
            function createFestivityCard(festivity) {
                const card = document.createElement('a');
                card.href = festivity.url;
                card.className = 'festivity-grid-card';
                
                const startDate = new Date(festivity.start_date);
                const endDate = festivity.end_date ? new Date(festivity.end_date) : null;
                const dateStr = endDate && endDate.getTime() !== startDate.getTime()
                    ? `${startDate.toLocaleDateString('es-ES', { day: 'numeric', month: 'short' })} - ${endDate.toLocaleDateString('es-ES', { day: 'numeric', month: 'short' })}`
                    : startDate.toLocaleDateString('es-ES', { day: 'numeric', month: 'short', year: 'numeric' });
                
                const votesCount = festivity.votes_count || 0;
                const localityName = festivity.locality?.name || '';
                const provinceName = festivity.locality?.province || festivity.province || '';
                const locationStr = localityName && provinceName 
                    ? `${localityName}, ${provinceName}`
                    : localityName || provinceName || 'España';
                
                // Background image or gradient
                const backgroundImage = festivity.photo 
                    ? `<img src="${festivity.photo}" class="festivity-grid-card-background" alt="${festivity.name}">`
                    : '<div class="festivity-grid-card-background"></div>';
                
                // Format votes count - always show
                const votesDisplay = votesCount || 0;
                
                card.innerHTML = `
                    ${backgroundImage}
                    <div class="festivity-grid-card-overlay"></div>
                    ${votesCount > 10 ? '<div class="festivity-grid-card-badge">Popular</div>' : ''}
                    <div class="festivity-grid-card-votes">
                        <i class="bi bi-heart-fill"></i>
                        <span>${votesDisplay}</span>
                    </div>
                    <div class="festivity-grid-card-content">
                        <h6 class="festivity-grid-card-title">${festivity.name}</h6>
                        <div class="festivity-grid-card-meta">
                            <div class="festivity-grid-card-location">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span>${locationStr}</span>
                            </div>
                        </div>
                    </div>
                `;
                
                return card;
            }
            
            // Infinite scroll listener for grid
            let scrollListener = null;
            const resultsGridSection = document.querySelector('.results-grid-section');
            
            function addScrollListener() {
                if (scrollListener || !resultsGridSection) return;
                
                scrollListener = function() {
                    const container = resultsGridSection;
                    const scrollLeft = container.scrollLeft;
                    const scrollWidth = container.scrollWidth;
                    const clientWidth = container.clientWidth;
                    const scrollPercentage = (scrollLeft + clientWidth) / scrollWidth;
                    
                    // Load more when scrolled to 80% of the way (but not beyond MAX_DISPLAYED)
                    if (scrollPercentage > 0.8 && displayedCount < MAX_DISPLAYED && displayedCount < filteredFestivities.length) {
                        displayMapResults(currentFestivities, false);
                    }
                };
                
                resultsGridSection.addEventListener('scroll', scrollListener);
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
                if (scrollListener && resultsGridSection) {
                    resultsGridSection.removeEventListener('scroll', scrollListener);
                    scrollListener = null;
                }
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
                if (resultsGrid) {
                    resultsGrid.innerHTML = '';
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
                            if (resultsGrid) {
                                resultsGrid.innerHTML = '';
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
                        if (resultsGrid) {
                            resultsGrid.innerHTML = '';
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
            
            // Map visibility functionality
            const resultsMapContainer = document.querySelector('.results-map-container');
            
            // Initialize map visibility state (default: visible on desktop, hidden on mobile)
            let mapVisible = window.innerWidth >= 992;
            
            // Set map-visible class on desktop by default
            if (resultsMapContainer && window.innerWidth >= 992) {
                resultsMapContainer.classList.add('map-visible');
                mapVisible = true;
            }
            
            function updateMapVisibility() {
                if (!resultsMapContainer) return;
                
                if (mapVisible) {
                    resultsMapContainer.classList.add('map-visible');
                } else {
                    // Only hide on mobile
                    if (window.innerWidth < 992) {
                        resultsMapContainer.classList.remove('map-visible');
                    }
                }
                
                // Trigger map resize if map exists
                if (map && typeof google !== 'undefined' && google.maps) {
                    setTimeout(() => {
                        google.maps.event.trigger(map, 'resize');
                    }, 300);
                }
            }
            
            // Update map visibility on window resize
            let resizeTimeout;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(function() {
                    // On mobile (< 992px), default to hidden
                    if (window.innerWidth < 992 && !mapVisible) {
                        updateMapVisibility();
                    } else if (window.innerWidth >= 992 && !mapVisible) {
                        mapVisible = true;
                        updateMapVisibility();
                    }
                }, 150);
            });
            
            // Initialize map visibility on page load
            updateMapVisibility();
            
            // Load Google Maps script with loading=async parameter
            if (!window.googleMapsScriptLoaded) {
                window.googleMapsScriptLoaded = true;
                const script = document.createElement('script');
                script.src = `https://maps.googleapis.com/maps/api/js?key=${mapKey}&loading=async&callback=initFestivitiesMap`;
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



