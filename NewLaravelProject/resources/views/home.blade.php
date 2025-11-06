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
    </script>
</x-app-layout>
