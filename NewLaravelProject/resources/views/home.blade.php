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

        <!-- Map Search Section -->
        <div class="mb-5" id="map-search-section">
            <h2 class="display-6 fw-bold text-dark mb-4">
                <i class="bi bi-map me-2"></i>Cerca de mí
            </h2>
            
            <!-- Search Bar - Slim, Large, Round -->
            <div class="mb-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center bg-white border border-2 rounded-pill shadow-sm p-2 flex-grow-1">
                        <!-- Search Text Field -->
                        <input type="text" id="map-search-query" class="form-control border-0 shadow-none flex-grow-1" 
                               placeholder="Buscar todas las fiestas..." style="background: transparent;">
                        
                        <!-- Province Dropdown (blended) -->
                        <select id="map-province-filter" class="form-select border-0 shadow-none" style="background: transparent; width: auto; min-width: 180px;">
                            <option value="">Provincia</option>
                            @foreach(config('provinces.provinces') as $province)
                                <option value="{{ $province }}">{{ $province }}</option>
                            @endforeach
                        </select>
                        
                        <!-- Divider -->
                        <div class="vr mx-2" style="height: 30px;"></div>
                        
                        <!-- Search Button -->
                        <button id="map-search-btn" class="btn btn-primary rounded-pill px-4" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                    
                    <!-- Location Button (Primary, outside the search bar) -->
                    <button id="map-near-me-btn" class="btn btn-primary rounded-pill px-4" type="button">
                        <i class="bi bi-geo-alt me-2"></i>Cerca de mí
                    </button>
                </div>
            </div>
            
            <!-- Google Map -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body p-0">
                    <div id="festivities-map" style="width: 100%; height: 500px; border-radius: 8px; overflow: hidden;"></div>
                </div>
            </div>
            
            <!-- Results - Horizontal Scrollable Cards -->
            <div id="map-results-container" style="display: none;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-list-ul me-2"></i>
                        <span id="map-results-count">0</span> festividades encontradas
                    </h5>
                    <button id="map-refresh-btn" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-arrow-clockwise me-1"></i>Actualizar
                    </button>
                </div>
                <div id="map-results-scroll" class="d-flex gap-3 overflow-auto pb-3" style="scroll-behavior: smooth;">
                    <!-- Festivities cards will be loaded here -->
                </div>
                <div id="map-no-results" class="alert alert-info" style="display: none;">
                    <i class="bi bi-info-circle me-2"></i>
                    No se encontraron festividades. Intenta cambiar la provincia o mover el mapa.
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
                                    Ver Más <i class="bi bi-arrow-right ms-1"></i>
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
        
        // Map Search Section - Google Maps Integration
        (function() {
            const mapElement = document.getElementById('festivities-map');
            const provinceFilter = document.getElementById('map-province-filter');
            const searchQuery = document.getElementById('map-search-query');
            const searchBtn = document.getElementById('map-search-btn');
            const nearMeBtn = document.getElementById('map-near-me-btn');
            const refreshBtn = document.getElementById('map-refresh-btn');
            const resultsContainer = document.getElementById('map-results-container');
            const resultsScroll = document.getElementById('map-results-scroll');
            const resultsCount = document.getElementById('map-results-count');
            const noResultsAlert = document.getElementById('map-no-results');
            
            let map = null;
            let markers = [];
            let currentFestivities = [];
            const madridCenter = { lat: 40.4168, lng: -3.7038 };
            const mapKey = '{{ config('services.google.maps_key') }}';
            
            // Initialize Google Map
            function initMap() {
                if (typeof google === 'undefined' || !google.maps) {
                    console.error('Google Maps not loaded');
                    return;
                }
                
                map = new google.maps.Map(mapElement, {
                    center: madridCenter,
                    zoom: 6,
                    mapTypeControl: true,
                    streetViewControl: true,
                    fullscreenControl: true,
                });
                
                // Load initial festivities
                loadFestivitiesForMap();
                
                // Listen to map bounds changes
                map.addListener('bounds_changed', function() {
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
                        updateMapMarkers(data.festivities);
                        displayMapResults(data.festivities);
                    }
                })
                .catch(error => {
                    console.error('Error loading festivities:', error);
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
            
            // Display results in horizontal scrollable list
            function displayMapResults(festivities) {
                // Filter by search query if present
                const query = searchQuery.value.toLowerCase().trim();
                let filtered = festivities;
                if (query) {
                    filtered = festivities.filter(festivity => {
                        const name = (festivity.name || '').toLowerCase();
                        const locality = (festivity.locality?.name || '').toLowerCase();
                        return name.includes(query) || locality.includes(query);
                    });
                }
                
                if (filtered.length === 0) {
                    resultsContainer.style.display = 'none';
                    noResultsAlert.style.display = 'block';
                    return;
                }
                
                noResultsAlert.style.display = 'none';
                resultsContainer.style.display = 'block';
                resultsCount.textContent = filtered.length;
                resultsScroll.innerHTML = '';
                
                filtered.forEach(festivity => {
                    const card = createHorizontalCard(festivity);
                    resultsScroll.appendChild(card);
                });
            }
            
            // Create horizontal scrollable card
            function createHorizontalCard(festivity) {
                const card = document.createElement('div');
                card.className = 'card flex-shrink-0';
                card.style.width = '300px';
                
                const photoHtml = festivity.photo 
                    ? `<img src="${festivity.photo}" class="card-img-top" alt="${festivity.name}" style="height: 150px; object-fit: cover;">`
                    : '<div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;"><i class="bi bi-image text-muted" style="font-size: 3rem;"></i></div>';
                
                const endDateHtml = festivity.end_date 
                    ? ` - ${new Date(festivity.end_date).toLocaleDateString('es-ES', { day: 'numeric', month: 'short' })}`
                    : '';
                
                card.innerHTML = `
                    ${photoHtml}
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title fw-bold">${festivity.name}</h6>
                        <p class="text-muted small mb-2">
                            <i class="bi bi-geo-alt me-1"></i>${festivity.locality.name || ''}
                            ${festivity.locality.province ? `<br><small>${festivity.locality.province}</small>` : ''}
                        </p>
                        <p class="text-muted small mb-2">
                            <i class="bi bi-calendar me-1"></i>
                            ${new Date(festivity.start_date).toLocaleDateString('es-ES', { day: 'numeric', month: 'short' })}${endDateHtml}
                        </p>
                        <p class="card-text small flex-grow-1">${festivity.description || ''}</p>
                        <a href="${festivity.url}" class="btn btn-primary btn-sm mt-auto">
                            Ver Más <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                `;
                
                return card;
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
                    const selectedProvince = provinceFilter.value;
                    
                    // If a province is selected, center map on that province
                    if (selectedProvince && provinceCoordinates[selectedProvince]) {
                        const coords = provinceCoordinates[selectedProvince];
                        if (map) {
                            map.setCenter({ lat: coords.lat, lng: coords.lng });
                            map.setZoom(coords.zoom);
                        }
                    } else if (!selectedProvince) {
                        // If "Provincia" (all) is selected, reset to Madrid
                        if (map) {
                            map.setCenter(madridCenter);
                            map.setZoom(6);
                        }
                    }
                    
                    // Load festivities for the new map view
                    loadFestivitiesForMap();
                });
            }
            
            // Search functionality
            function performSearch() {
                displayMapResults(currentFestivities);
            }
            
            if (searchQuery) {
                searchQuery.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
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
            
            if (refreshBtn) {
                refreshBtn.addEventListener('click', loadFestivitiesForMap);
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
