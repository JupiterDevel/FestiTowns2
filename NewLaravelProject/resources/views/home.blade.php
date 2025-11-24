<x-app-layout>
    <x-slot name="header">
        <h1 class="display-6 fw-bold text-primary mb-0">Discover Spanish Festivals</h1>
    </x-slot>

    <div class="container">
        <!-- Search Section -->
        <div class="search-section bg-light rounded-3 p-4 mb-5">
            <h3 class="fw-bold mb-3"><i class="bi bi-search me-2"></i>Buscar Festividades</h3>
            <form method="GET" action="{{ route('home') }}" class="row g-3">
                <div class="col-md-6">
                    <label for="search" class="form-label">Término de búsqueda</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ $searchType !== 'date' ? $searchQuery : '' }}" placeholder="Buscar festividades, localidades...">
                        <input type="date" class="form-control" id="search_date" name="search_date" 
                               value="{{ $searchType == 'date' ? $searchQuery : '' }}" 
                               style="display: none;">
                        <select class="form-control" id="search_province" name="search_province" 
                                style="display: none;">
                            <option value="">Seleccionar provincia...</option>
                            @foreach(config('provinces.provinces') as $province)
                                <option value="{{ $province }}" 
                                        {{ ($searchType == 'province' && $searchQuery == $province) ? 'selected' : '' }}>
                                    {{ $province }}
                                </option>
                            @endforeach
                        </select>
                        <span class="input-group-text" id="search_icon" style="display: none;">
                            <i class="bi bi-calendar3"></i>
                        </span>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="search_type" class="form-label">Tipo de búsqueda</label>
                    <select class="form-select" id="search_type" name="search_type">
                        <option value="festivity" {{ ($searchType ?? '') == 'festivity' ? 'selected' : '' }}>
                            Por Festividad
                        </option>
                        <option value="locality" {{ ($searchType ?? '') == 'locality' ? 'selected' : '' }}>
                            Por Localidad
                        </option>
                        <option value="date" {{ ($searchType ?? '') == 'date' ? 'selected' : '' }}>
                            Por Fecha
                        </option>
                        <option value="province" {{ ($searchType ?? '') == 'province' ? 'selected' : '' }}>
                            Por Provincia
                        </option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="bi bi-search me-1"></i>Buscar
                    </button>
                    @if($searchQuery)
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x"></i>
                        </a>
                    @endif
                </div>
            </form>
            @if($searchType == 'date')
                <div class="mt-2">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Selecciona una fecha para ver festividades cercanas (hasta una semana después)
                    </small>
                </div>
            @elseif($searchType == 'province')
                <div class="mt-2">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Selecciona una provincia para ver todas las festividades en esa provincia
                    </small>
                </div>
            @endif
        </div>

        @if($searchResults)
            <!-- Search Results -->
            <div class="search-results mb-5">
                <h3 class="fw-bold mb-4">
                    <i class="bi bi-funnel me-2"></i>Resultados de búsqueda
                    @if($searchQuery)
                        para "{{ $searchQuery }}"
                    @endif
                    <span class="badge bg-info ms-2">{{ $searchResults->total() }} resultado(s)</span>
                </h3>
                
                @if($searchResults->count() > 0)
                    @if($searchType == 'locality')
                        <!-- Localities Results -->
                        <div class="row g-4">
                            @foreach($searchResults as $locality)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card locality-card card-hover h-100">
                                        @if($locality->photos && count($locality->photos) > 0)
                                            <img src="{{ $locality->photos[0] }}" class="card-img-top" alt="{{ $locality->name }}">
                                        @endif
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title">{{ $locality->name }}</h5>
                                            <p class="text-muted mb-2">
                                                <i class="bi bi-geo-alt me-1"></i>{{ $locality->address }}
                                                @if($locality->province)
                                                    <br><small><i class="bi bi-map me-1"></i>{{ $locality->province }}</small>
                                                @endif
                                            </p>
                                            <p class="card-text flex-grow-1">{{ Str::limit($locality->description, 120) }}</p>
                                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                                <span class="badge bg-info">
                                                    {{ $locality->festivities->count() }} 
                                                    {{ Str::plural('festivity', $locality->festivities->count()) }}
                                                </span>
                                                <a href="{{ route('localities.show', $locality) }}" class="btn btn-success btn-sm">
                                                    Ver Localidad
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Festivities Results -->
                        <div class="row g-4">
                            @foreach($searchResults as $festivity)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card festivity-card card-hover h-100">
                                        @if($festivity->photos && count($festivity->photos) > 0)
                                            <img src="{{ $festivity->photos[0] }}" class="card-img-top" alt="{{ $festivity->name }}">
                                        @endif
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title">{{ $festivity->name }}</h5>
                                            <p class="text-muted mb-2">
                                                <i class="bi bi-geo-alt me-1"></i>{{ $festivity->locality->name }}
                                                @if($festivity->province)
                                                    <br><small><i class="bi bi-map me-1"></i>{{ $festivity->province }}</small>
                                                @endif
                                            </p>
                                            <p class="text-muted small mb-3">
                                                <i class="bi bi-calendar me-1"></i>
                                                {{ $festivity->start_date->format('M j, Y') }}
                                                @if($festivity->end_date)
                                                    - {{ $festivity->end_date->format('M j, Y') }}
                                                @endif
                                            </p>
                                            <p class="card-text flex-grow-1">{{ Str::limit($festivity->description, 100) }}</p>
                                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                                <span class="badge bg-warning">
                                                    <i class="bi bi-heart-fill me-1"></i>{{ $festivity->votes_count ?? 0 }} votos
                                                </span>
                                                <a href="{{ route('festivities.show', $festivity) }}" class="btn btn-primary btn-sm">
                                                    Ver Festividad
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $searchResults->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-info-circle me-2"></i>
                        No se encontraron resultados para "{{ $searchQuery }}". 
                        <a href="{{ route('home') }}" class="alert-link">Ver todas las festividades</a>
                    </div>
                @endif
            </div>
        @endif

        <!-- Hero Section -->
        <div class="hero-section rounded-3 p-5 mb-5">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-3">🎉 FestiTowns</h1>
                    <p class="lead mb-4">Discover the most vibrant and exciting festivals across Spain</p>
                    <p class="h5">From the Running of the Bulls in Pamplona to the spectacular Fallas in Valencia, explore the rich cultural heritage of Spanish festivities.</p>
                </div>
                <div class="col-lg-4 text-center">
                    <i class="display-1">🇪🇸</i>
                </div>
            </div>
        </div>

        <!-- Near Me Section -->
        <div class="mb-5" id="near-me-section">
            <h2 class="display-6 fw-bold text-dark mb-4">
                <i class="bi bi-geo-alt-fill me-2"></i>Near Me
            </h2>
            
            <!-- Location Permission Request -->
            <div id="location-permission-request" class="card bg-light border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-geo-alt-fill display-4 text-primary mb-3"></i>
                    <h4 class="fw-bold mb-3">Discover Festivities Near You</h4>
                    <p class="text-muted mb-4">
                        Allow location access to find festivities happening near your current location.
                    </p>
                    <button id="request-location-btn" class="btn btn-primary btn-lg">
                        <i class="bi bi-geo-alt me-2"></i>Find Festivities Near Me
                    </button>
                </div>
            </div>
            
            <!-- Loading State -->
            <div id="location-loading" class="card bg-light border-0 shadow-sm" style="display: none;">
                <div class="card-body text-center py-5">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-muted mb-0">Finding festivities near you...</p>
                </div>
            </div>
            
            <!-- Error State -->
            <div id="location-error" class="alert alert-warning" style="display: none;" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <span id="location-error-message"></span>
            </div>
            
            <!-- Results Container -->
            <div id="nearby-festivities-results" style="display: none;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <p class="text-muted mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        <span id="nearby-count">0</span> festivities found within <span id="search-radius">50</span> km
                    </p>
                    <button id="refresh-location-btn" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                    </button>
                </div>
                <div id="nearby-festivities-grid" class="row g-4">
                    <!-- Festivities will be loaded here via JavaScript -->
                </div>
                <div id="no-nearby-festivities" class="alert alert-info" style="display: none;">
                    <i class="bi bi-info-circle me-2"></i>
                    No festivities found near your location. Try increasing the search radius or check back later!
                </div>
            </div>
        </div>

        <!-- Upcoming Festivities -->
        <div class="mb-5">
            <h2 class="display-6 fw-bold text-dark mb-4">
                <i class="bi bi-calendar-event me-2"></i>Upcoming Festivities
            </h2>
            <div class="row g-4">
                @foreach($upcomingFestivities as $festivity)
                    <div class="col-md-6 col-lg-4">
                        <div class="card festivity-card card-hover h-100">
                            @if($festivity->photos && count($festivity->photos) > 0)
                                <img src="{{ $festivity->photos[0] }}" class="card-img-top" alt="{{ $festivity->name }}">
                            @endif
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $festivity->name }}</h5>
                                <p class="text-muted mb-2">
                                    <i class="bi bi-geo-alt me-1"></i>{{ $festivity->locality->name }}
                                    @if($festivity->province)
                                        <br><small><i class="bi bi-map me-1"></i>{{ $festivity->province }}</small>
                                    @endif
                                </p>
                                <p class="text-muted small mb-3">
                                    <i class="bi bi-calendar me-1"></i>
                                    {{ $festivity->start_date->format('M j') }}
                                    @if($festivity->end_date)
                                        - {{ $festivity->end_date->format('M j, Y') }}
                                    @endif
                                </p>
                                <p class="card-text flex-grow-1">{{ Str::limit($festivity->description, 100) }}</p>
                                <a href="{{ route('festivities.show', $festivity) }}" class="btn btn-primary btn-custom">
                                    Learn More <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Localities Section -->
        <div class="mb-5">
            <h2 class="display-6 fw-bold text-dark mb-4">
                <i class="bi bi-map me-2"></i>Explore Localities
            </h2>
            <div class="row g-4">
                @foreach($localities as $locality)
                    <div class="col-md-6 col-lg-4">
                        <div class="card locality-card card-hover h-100">
                            @if($locality->photos && count($locality->photos) > 0)
                                <img src="{{ $locality->photos[0] }}" class="card-img-top" alt="{{ $locality->name }}">
                            @endif
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $locality->name }}</h5>
                                <p class="text-muted mb-2">
                                    <i class="bi bi-geo-alt me-1"></i>{{ $locality->address }}
                                    @if($locality->province)
                                        <br><small><i class="bi bi-map me-1"></i>{{ $locality->province }}</small>
                                    @endif
                                </p>
                                <p class="card-text flex-grow-1">{{ Str::limit($locality->description, 120) }}</p>
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <span class="badge bg-info">
                                        {{ $locality->festivities->count() }} 
                                        {{ Str::plural('festivity', $locality->festivities->count()) }}
                                    </span>
                                    <a href="{{ route('localities.show', $locality) }}" class="btn btn-success btn-custom">
                                        Explore <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Call to Action -->
        @guest
            <div class="card bg-primary text-white text-center">
                <div class="card-body py-5">
                    <h2 class="display-6 fw-bold mb-4">Join Our Community</h2>
                    <p class="lead mb-4">Register to share your festival experiences and connect with other festival enthusiasts!</p>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="{{ route('register') }}" class="btn btn-light btn-custom btn-lg">
                            <i class="bi bi-person-plus me-2"></i>Sign Up
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-custom btn-lg">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login
                        </a>
                    </div>
                </div>
            </div>
        @endguest
    </div>

    <style>
        .search-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 1px solid #dee2e6;
        }
        
        .search-results {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .card-hover {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .festivity-card .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        
        .locality-card .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        
        .btn-custom {
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-custom:hover {
            transform: translateY(-2px);
        }
        
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .pagination .page-link {
            border-radius: 50%;
            margin: 0 2px;
            border: none;
            background: #f8f9fa;
            color: #495057;
        }
        
        .pagination .page-link:hover {
            background: #007bff;
            color: white;
        }
        
        .pagination .page-item.active .page-link {
            background: #007bff;
            border-color: #007bff;
        }
        
        /* Estilos para el selector de fecha */
        #search_date {
            transition: all 0.3s ease;
        }
        
        #search_date:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        
        /* Animación suave para el cambio de inputs */
        #search, #search_date {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        
        /* Mejorar la apariencia del date picker */
        input[type="date"] {
            position: relative;
        }
        
        input[type="date"]::-webkit-calendar-picker-indicator {
            background: transparent;
            bottom: 0;
            color: transparent;
            cursor: pointer;
            height: auto;
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
            width: auto;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchTypeSelect = document.getElementById('search_type');
            const searchInput = document.getElementById('search');
            const searchDateInput = document.getElementById('search_date');
            const searchProvinceSelect = document.getElementById('search_province');
            const searchIcon = document.getElementById('search_icon');
            const searchForm = document.querySelector('form[method="GET"]');
            
            // Cambiar entre input de texto, selector de fecha y selector de provincia
            function toggleInputType() {
                const searchType = searchTypeSelect.value;
                
                // Ocultar todos los inputs primero
                searchInput.style.display = 'none';
                searchDateInput.style.display = 'none';
                searchProvinceSelect.style.display = 'none';
                searchIcon.style.display = 'none';
                
                // Resetear required
                searchInput.required = false;
                searchDateInput.required = false;
                searchProvinceSelect.required = false;
                
                if (searchType === 'date') {
                    // Mostrar selector de fecha
                    searchDateInput.style.display = 'block';
                    searchIcon.style.display = 'block';
                    searchDateInput.required = true;
                    // Limpiar otros inputs
                    searchInput.value = '';
                    searchProvinceSelect.value = '';
                } else if (searchType === 'province') {
                    // Mostrar selector de provincia
                    searchProvinceSelect.style.display = 'block';
                    searchProvinceSelect.required = true;
                    // Limpiar otros inputs
                    searchInput.value = '';
                    searchDateInput.value = '';
                } else {
                    // Mostrar input de texto
                    searchInput.style.display = 'block';
                    searchInput.required = true;
                    // Limpiar otros inputs
                    searchDateInput.value = '';
                    searchProvinceSelect.value = '';
                }
            }
            
            // Cambiar placeholder según el tipo de búsqueda
            function updatePlaceholder() {
                const searchType = searchTypeSelect.value;
                switch(searchType) {
                    case 'festivity':
                        searchInput.placeholder = 'Buscar festividades...';
                        break;
                    case 'locality':
                        searchInput.placeholder = 'Buscar localidades...';
                        break;
                    case 'date':
                        searchInput.placeholder = 'Selecciona una fecha';
                        break;
                    case 'province':
                        searchInput.placeholder = 'Selecciona una provincia';
                        break;
                }
            }
            
            // Sincronizar valores entre inputs (solo al cargar la página)
            function syncInputs() {
                // Solo sincronizar si hay valores válidos y el tipo coincide
                if (searchTypeSelect.value === 'date' && searchDateInput.value) {
                    // Si estamos en modo fecha y hay fecha, mantenerla
                    return;
                } else if (searchTypeSelect.value !== 'date' && searchInput.value) {
                    // Si estamos en modo texto y hay texto, mantenerlo
                    return;
                }
                // Si no hay valores válidos, no hacer nada
            }
            
            // Actualizar tipo de input al cambiar el selector
            searchTypeSelect.addEventListener('change', function() {
                toggleInputType();
                updatePlaceholder();
                // No sincronizar valores al cambiar tipo, solo limpiar
            });
            
            // Sincronizar cuando se cambie el valor del date picker
            searchDateInput.addEventListener('change', function() {
                if (searchTypeSelect.value === 'date') {
                    searchInput.value = searchDateInput.value;
                }
            });
            
            // Validar antes de enviar
            searchForm.addEventListener('submit', function(e) {
                if (searchTypeSelect.value === 'date') {
                    if (!searchDateInput.value) {
                        e.preventDefault();
                        alert('Por favor, selecciona una fecha');
                        searchDateInput.focus();
                        return;
                    }
                } else if (searchTypeSelect.value === 'province') {
                    if (!searchProvinceSelect.value) {
                        e.preventDefault();
                        alert('Por favor, selecciona una provincia');
                        searchProvinceSelect.focus();
                        return;
                    }
                }
            });
            
            // Función para validar fecha
            function isValidDate(dateString) {
                const regex = /^\d{4}-\d{2}-\d{2}$/;
                if (!regex.test(dateString)) return false;
                
                const date = new Date(dateString);
                return date instanceof Date && !isNaN(date) && dateString === date.toISOString().split('T')[0];
            }
            
            // Inicializar
            toggleInputType();
            updatePlaceholder();
            syncInputs();
        });
        
        // Near Me Section - Geolocation
        (function() {
            const requestLocationBtn = document.getElementById('request-location-btn');
            const refreshLocationBtn = document.getElementById('refresh-location-btn');
            const permissionRequest = document.getElementById('location-permission-request');
            const locationLoading = document.getElementById('location-loading');
            const locationError = document.getElementById('location-error');
            const errorMessage = document.getElementById('location-error-message');
            const resultsContainer = document.getElementById('nearby-festivities-results');
            const resultsGrid = document.getElementById('nearby-festivities-grid');
            const noResultsAlert = document.getElementById('no-nearby-festivities');
            const nearbyCount = document.getElementById('nearby-count');
            const searchRadius = document.getElementById('search-radius');
            
            let currentLatitude = null;
            let currentLongitude = null;
            const defaultRadius = 50; // km
            
            function showPermissionRequest() {
                permissionRequest.style.display = 'block';
                locationLoading.style.display = 'none';
                locationError.style.display = 'none';
                resultsContainer.style.display = 'none';
            }
            
            function showLoading() {
                permissionRequest.style.display = 'none';
                locationLoading.style.display = 'block';
                locationError.style.display = 'none';
                resultsContainer.style.display = 'none';
            }
            
            function showError(message) {
                permissionRequest.style.display = 'none';
                locationLoading.style.display = 'none';
                locationError.style.display = 'block';
                errorMessage.textContent = message;
                resultsContainer.style.display = 'none';
            }
            
            function showResults() {
                permissionRequest.style.display = 'none';
                locationLoading.style.display = 'none';
                locationError.style.display = 'none';
                resultsContainer.style.display = 'block';
            }
            
            function getLocation() {
                if (!navigator.geolocation) {
                    showError('Geolocation is not supported by your browser. Please use a modern browser like Chrome, Firefox, or Edge.');
                    return;
                }
                
                showLoading();
                
                const options = {
                    enableHighAccuracy: false, // Changed to false for better compatibility
                    timeout: 15000, // Increased timeout
                    maximumAge: 300000 // Allow cached position up to 5 minutes
                };
                
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        currentLatitude = position.coords.latitude;
                        currentLongitude = position.coords.longitude;
                        console.log('Location obtained:', currentLatitude, currentLongitude);
                        fetchNearbyFestivities(currentLatitude, currentLongitude, defaultRadius);
                    },
                    function(error) {
                        console.error('Geolocation error:', error);
                        let errorMsg = 'Unable to retrieve your location. ';
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                errorMsg += 'Location access was denied. Please allow location access in your browser settings and try again.';
                                // Show manual input option
                                showManualLocationInput();
                                return;
                            case error.POSITION_UNAVAILABLE:
                                errorMsg += 'Location information is unavailable. This might be because:<br>' +
                                    '• Your device doesn\'t have GPS/WiFi location services enabled<br>' +
                                    '• You\'re using HTTP instead of HTTPS (geolocation requires HTTPS on most browsers)<br>' +
                                    '• Your browser is blocking location access<br><br>' +
                                    '<strong>Tip:</strong> Try using HTTPS or manually enter your location below.';
                                showManualLocationInput();
                                return;
                            case error.TIMEOUT:
                                errorMsg += 'The request to get your location timed out. Please try again or enter your location manually.';
                                showManualLocationInput();
                                return;
                            default:
                                errorMsg += 'An unknown error occurred. Error code: ' + error.code;
                                showManualLocationInput();
                                return;
                        }
                        showError(errorMsg);
                    },
                    options
                );
            }
            
            function showManualLocationInput() {
                // Create manual input container
                let manualContainer = document.getElementById('manual-location-container');
                if (!manualContainer) {
                    manualContainer = document.createElement('div');
                    manualContainer.id = 'manual-location-container';
                    manualContainer.className = 'card bg-light border-0 shadow-sm mt-3';
                    manualContainer.innerHTML = `
                        <div class="card-body">
                            <h5 class="fw-bold mb-3"><i class="bi bi-pencil-square me-2"></i>Enter Location Manually</h5>
                            <p class="text-muted small mb-3">You can enter coordinates manually if automatic location detection fails:</p>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="manual-latitude" class="form-label">Latitude</label>
                                    <input type="number" step="any" id="manual-latitude" class="form-control" 
                                           placeholder="e.g., 40.4168" min="-90" max="90">
                                </div>
                                <div class="col-md-6">
                                    <label for="manual-longitude" class="form-label">Longitude</label>
                                    <input type="number" step="any" id="manual-longitude" class="form-control" 
                                           placeholder="e.g., -3.7038" min="-180" max="180">
                                </div>
                            </div>
                            <div class="mt-3">
                                <button id="use-manual-location-btn" class="btn btn-primary">
                                    <i class="bi bi-search me-1"></i>Find Festivities
                                </button>
                                <button id="try-again-btn" class="btn btn-outline-secondary ms-2">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Try Again
                                </button>
                            </div>
                            <div class="mt-3">
                                <small class="text-muted">
                                    <strong>Quick locations:</strong><br>
                                    Madrid: 40.4168, -3.7038 | Barcelona: 41.3851, 2.1734 | Valencia: 39.4699, -0.3763
                                </small>
                            </div>
                        </div>
                    `;
                    locationError.appendChild(manualContainer);
                } else {
                    manualContainer.style.display = 'block';
                }
                
                // Add event listeners for manual input (only once)
                setTimeout(function() {
                    const useManualBtn = document.getElementById('use-manual-location-btn');
                    const tryAgainBtn = document.getElementById('try-again-btn');
                    const manualLat = document.getElementById('manual-latitude');
                    const manualLng = document.getElementById('manual-longitude');
                    
                    if (useManualBtn && !useManualBtn.hasAttribute('data-listener-added')) {
                        useManualBtn.setAttribute('data-listener-added', 'true');
                        useManualBtn.addEventListener('click', function() {
                            const lat = parseFloat(manualLat.value);
                            const lng = parseFloat(manualLng.value);
                            
                            if (isNaN(lat) || isNaN(lng)) {
                                alert('Please enter valid latitude and longitude values.');
                                return;
                            }
                            
                            if (lat < -90 || lat > 90 || lng < -180 || lng > 180) {
                                alert('Please enter valid coordinates. Latitude: -90 to 90, Longitude: -180 to 180');
                                return;
                            }
                            
                            currentLatitude = lat;
                            currentLongitude = lng;
                            showLoading();
                            fetchNearbyFestivities(lat, lng, defaultRadius);
                        });
                    }
                    
                    if (tryAgainBtn && !tryAgainBtn.hasAttribute('data-listener-added')) {
                        tryAgainBtn.setAttribute('data-listener-added', 'true');
                        tryAgainBtn.addEventListener('click', function() {
                            const manualContainer = document.getElementById('manual-location-container');
                            if (manualContainer) {
                                manualContainer.style.display = 'none';
                            }
                            locationError.style.display = 'none';
                            showPermissionRequest();
                        });
                    }
                }, 100);
            }
            
            function fetchNearbyFestivities(lat, lng, radius) {
                const url = `{{ route('festivities.nearby') }}?latitude=${lat}&longitude=${lng}&radius=${radius}`;
                
                console.log('Fetching from URL:', url);
                
                fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    },
                    credentials: 'same-origin'
                })
                .then(async response => {
                    console.log('Response status:', response.status, response.statusText);
                    
                    if (!response.ok) {
                        // Try to get error message from response
                        let errorMessage = `Server returned ${response.status}: ${response.statusText}`;
                        try {
                            const errorData = await response.json();
                            if (errorData.message) {
                                errorMessage = errorData.message;
                            } else if (errorData.errors) {
                                errorMessage = 'Validation error: ' + JSON.stringify(errorData.errors);
                            }
                        } catch (e) {
                            // If response is not JSON, use status text
                            const text = await response.text();
                            if (text) {
                                errorMessage += ' - ' + text.substring(0, 200);
                            }
                        }
                        throw new Error(errorMessage);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success && data.festivities !== undefined) {
                        displayNearbyFestivities(data.festivities);
                    } else {
                        showError('Failed to load nearby festivities. Response: ' + JSON.stringify(data));
                    }
                })
                .catch(error => {
                    console.error('Error fetching nearby festivities:', error);
                    showError('Failed to load nearby festivities: ' + error.message + '. Please check the browser console for more details.');
                });
            }
            
            function displayNearbyFestivities(festivities) {
                resultsGrid.innerHTML = '';
                
                if (festivities.length === 0) {
                    noResultsAlert.style.display = 'block';
                    nearbyCount.textContent = '0';
                    showResults();
                    return;
                }
                
                noResultsAlert.style.display = 'none';
                nearbyCount.textContent = festivities.length;
                
                festivities.forEach(festivity => {
                    const card = createFestivityCard(festivity);
                    resultsGrid.appendChild(card);
                });
                
                showResults();
            }
            
            function createFestivityCard(festivity) {
                const col = document.createElement('div');
                col.className = 'col-md-6 col-lg-4';
                
                const photoHtml = festivity.photo 
                    ? `<img src="${festivity.photo}" class="card-img-top" alt="${festivity.name}" style="height: 200px; object-fit: cover;">`
                    : '';
                
                const endDateHtml = festivity.end_date 
                    ? ` - ${new Date(festivity.end_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}`
                    : '';
                
                const distanceHtml = festivity.distance 
                    ? `<span class="badge bg-success ms-2"><i class="bi bi-rulers me-1"></i>${festivity.distance} km</span>`
                    : '';
                
                col.innerHTML = `
                    <div class="card festivity-card card-hover h-100">
                        ${photoHtml}
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">${festivity.name}</h5>
                            <p class="text-muted mb-2">
                                <i class="bi bi-geo-alt me-1"></i>${festivity.locality.name || 'Unknown'}
                                ${festivity.locality.province ? `<br><small><i class="bi bi-map me-1"></i>${festivity.locality.province}</small>` : ''}
                            </p>
                            <p class="text-muted small mb-3">
                                <i class="bi bi-calendar me-1"></i>
                                ${new Date(festivity.start_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}${endDateHtml}
                                ${distanceHtml}
                            </p>
                            <p class="card-text flex-grow-1">${festivity.description || ''}</p>
                            <a href="${festivity.url}" class="btn btn-primary btn-custom">
                                Learn More <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                `;
                
                return col;
            }
            
            // Event Listeners
            if (requestLocationBtn) {
                requestLocationBtn.addEventListener('click', getLocation);
            }
            
            if (refreshLocationBtn) {
                refreshLocationBtn.addEventListener('click', function() {
                    if (currentLatitude && currentLongitude) {
                        showLoading();
                        fetchNearbyFestivities(currentLatitude, currentLongitude, defaultRadius);
                    } else {
                        getLocation();
                    }
                });
            }
        })();
    </script>
</x-app-layout>
