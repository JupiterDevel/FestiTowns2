<x-app-layout>
    <!-- Hero Header with Locality Info -->
    <div class="locality-hero">
        @if(!empty($locality->photos) && is_array($locality->photos) && count($locality->photos) > 0)
            <div class="hero-image" style="background-image: url('{{ $locality->photos[0] }}');"></div>
            <div class="hero-overlay"></div>
        @else
            <div class="hero-gradient"></div>
        @endif
        
        <div class="hero-content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="locality-header-content">
                            @if($locality->province)
                                <span class="province-badge">{{ $locality->province }}</span>
                            @endif
                            <h1 class="locality-title">{{ $locality->name }}</h1>
                            <div class="locality-meta">
                                <span class="meta-item">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    {{ $locality->address }}
                                </span>
                                @if($locality->festivities->count() > 0)
                                    <span class="meta-item">
                                        <i class="bi bi-calendar-event me-1"></i>
                                        {{ $locality->festivities->count() }} {{ $locality->festivities->count() === 1 ? 'festividad' : 'festividades' }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        @auth
                            <div class="action-buttons">
                                @can('update', $locality)
                                    <a href="{{ route('localities.edit', $locality) }}" class="btn btn-light btn-sm">
                                        <i class="bi bi-pencil me-1"></i>Editar
                                    </a>
                                @endcan
                                @can('delete', $locality)
                                    <form method="POST" action="{{ route('localities.destroy', $locality) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                onclick="return confirm('¿Eliminar esta localidad?')">
                                            <i class="bi bi-trash me-1"></i>Eliminar
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4 mb-5">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @include('ads.main_banner', ['ad' => $mainAdvertisement, 'newAdParams' => $adCreationParams])

        <div class="row g-4">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Description Card -->
                <div class="content-card mb-4">
                    <div class="card-header-custom">
                        <h2><i class="bi bi-info-circle me-2"></i>Sobre {{ $locality->name }}</h2>
                    </div>
                    <div class="card-body-custom">
                        <p class="lead-text">{{ $locality->description }}</p>
                    </div>
                </div>

                <!-- Photo Gallery -->
                @if(!empty($locality->photos) && is_array($locality->photos) && count($locality->photos) > 0)
                    <div class="content-card mb-4">
                        <div class="card-header-custom">
                            <h2><i class="bi bi-images me-2"></i>Galería</h2>
                        </div>
                        <div class="card-body-custom">
                            <div id="localityCarousel" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-indicators">
                                    @foreach($locality->photos as $index => $photo)
                                        <button type="button" data-bs-target="#localityCarousel" data-bs-slide-to="{{ $index }}" 
                                                class="{{ $index === 0 ? 'active' : '' }}"></button>
                                    @endforeach
                                </div>
                                
                                <div class="carousel-inner rounded">
                                    @foreach($locality->photos as $index => $photo)
                                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                            <img src="{{ $photo }}" 
                                                 class="d-block w-100" 
                                                 alt="{{ $locality->name }}"
                                                 style="height: 450px; object-fit: cover;">
                                        </div>
                                    @endforeach
                                </div>
                                
                                @if(count($locality->photos) > 1)
                                    <button class="carousel-control-prev" type="button" data-bs-target="#localityCarousel" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon"></span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#localityCarousel" data-bs-slide="next">
                                        <span class="carousel-control-next-icon"></span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Places of Interest -->
                <div class="content-card mb-4">
                    <div class="card-header-custom">
                        <h2><i class="bi bi-star me-2"></i>Lugares de Interés</h2>
                    </div>
                    <div class="card-body-custom">
                        <div class="content-text">{{ $locality->places_of_interest }}</div>
                    </div>
                </div>

                <!-- Monuments -->
                <div class="content-card mb-4">
                    <div class="card-header-custom">
                        <h2><i class="bi bi-building me-2"></i>Monumentos</h2>
                    </div>
                    <div class="card-body-custom">
                        <div class="content-text">{{ $locality->monuments }}</div>
                    </div>
                </div>

                <!-- Mobile Ads -->
                <div class="d-lg-none mb-4">
                    @include('ads.secondary_banner', ['ads' => $secondaryAdvertisements, 'orientation' => 'inline', 'newAdParams' => $adCreationParams])
                </div>

                <!-- Festivities -->
                <div class="content-card">
                    <div class="card-header-custom d-flex justify-content-between align-items-center">
                        <h2><i class="bi bi-calendar-event me-2"></i>Festividades</h2>
                        @auth
                            @can('create', App\Models\Festivity::class)
                                @if(!auth()->user()->isTownHall() || (auth()->user()->isTownHall() && auth()->user()->locality_id === $locality->id))
                                    <a href="{{ route('festivities.create') }}?locality_id={{ $locality->id }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-plus-circle me-1"></i>Añadir
                                    </a>
                                @endif
                            @endcan
                        @endauth
                    </div>
                    <div class="card-body-custom">
                        @if($locality->festivities->count() > 0)
                            <div class="row g-3">
                                @foreach($locality->festivities as $festivity)
                                    <div class="col-md-6">
                                        <div class="festivity-mini-card">
                                            @if(!empty($festivity->photos) && is_array($festivity->photos) && count($festivity->photos) > 0)
                                                <div class="festivity-mini-img" style="background-image: url('{{ $festivity->photos[0] }}');"></div>
                                            @else
                                                <div class="festivity-mini-img festivity-mini-placeholder">
                                                    <i class="bi bi-calendar-event"></i>
                                                </div>
                                            @endif
                                            <div class="festivity-mini-content">
                                                <h5 class="festivity-mini-title">{{ $festivity->name }}</h5>
                                                <p class="festivity-mini-date">
                                                    <i class="bi bi-calendar me-1"></i>
                                                    {{ $festivity->start_date->format('d M Y') }}
                                                    @if($festivity->end_date)
                                                        - {{ $festivity->end_date->format('d M Y') }}
                                                    @endif
                                                </p>
                                                <p class="festivity-mini-desc">{{ Str::limit($festivity->description, 80) }}</p>
                                                <a href="{{ route('festivities.show', $festivity) }}" class="btn btn-sm btn-outline-primary">
                                                    Ver detalles <i class="bi bi-arrow-right ms-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="bi bi-calendar-x"></i>
                                <p>No hay festividades registradas aún</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4 d-none d-lg-block">
                @include('ads.secondary_banner', ['ads' => $secondaryAdvertisements, 'orientation' => 'sidebar', 'newAdParams' => $adCreationParams])
            </div>
        </div>
    </div>

    <style>
        /* Hero Section */
        .locality-hero {
            position: relative;
            height: 400px;
            overflow: hidden;
            margin-top: -1.5rem;
        }
        
        .hero-image {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-size: cover;
            background-position: center;
            filter: brightness(0.7);
        }
        
        .hero-gradient {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0.3), rgba(0,0,0,0.6));
        }
        
        .hero-content-wrapper {
            position: relative;
            z-index: 10;
            height: 100%;
            display: flex;
            align-items: flex-end;
            padding-bottom: 3rem;
        }
        
        .locality-header-content {
            color: white;
        }
        
        .province-badge {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        
        .locality-title {
            font-size: 3rem;
            font-weight: 700;
            margin: 0.5rem 0;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }
        
        .locality-meta {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
            margin-top: 1rem;
        }
        
        .meta-item {
            font-size: 1rem;
            opacity: 0.95;
        }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            justify-content: flex-end;
        }
        
        /* Content Cards */
        .content-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .card-header-custom {
            background: linear-gradient(to right, #f8f9fa, #ffffff);
            padding: 1.5rem;
            border-bottom: 1px solid #e9ecef;
        }
        
        .card-header-custom h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2d3748;
            margin: 0;
        }
        
        .card-body-custom {
            padding: 1.5rem;
        }
        
        .lead-text {
            font-size: 1.1rem;
            line-height: 1.7;
            color: #4a5568;
            margin: 0;
        }
        
        .content-text {
            white-space: pre-line;
            line-height: 1.7;
            color: #4a5568;
        }
        
        /* Festivity Mini Cards */
        .festivity-mini-card {
            display: flex;
            background: #f8f9fa;
            border-radius: 12px;
            overflow: hidden;
            min-height: 100%;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .festivity-mini-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .festivity-mini-img {
            width: 120px;
            min-height: 200px;
            background-size: cover;
            background-position: center;
            flex-shrink: 0;
        }
        
        .festivity-mini-placeholder {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
        }
        
        .festivity-mini-content {
            padding: 1rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .festivity-mini-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #2d3748;
        }
        
        .festivity-mini-date {
            font-size: 0.85rem;
            color: #667eea;
            margin-bottom: 0.5rem;
        }
        
        .festivity-mini-desc {
            font-size: 0.85rem;
            color: #6b7280;
            margin-bottom: 1rem;
            flex-grow: 1;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #9ca3af;
        }
        
        .empty-state i {
            font-size: 4rem;
            opacity: 0.5;
        }
        
        .empty-state p {
            margin-top: 1rem;
            font-size: 1.1rem;
        }
        
        /* Responsive */
        @media (max-width: 991px) {
            .locality-hero {
                height: 300px;
            }
            
            .locality-title {
                font-size: 2rem;
            }
            
            .hero-content-wrapper {
                padding-bottom: 2rem;
            }
            
            .action-buttons {
                margin-top: 1rem;
                justify-content: flex-start;
            }
            
            .festivity-mini-card {
                flex-direction: column;
                height: auto;
            }
            
            .festivity-mini-img {
                width: 100%;
                height: 180px;
                flex-shrink: 0;
            }
            
            .festivity-mini-content {
                min-height: 200px;
            }
            
            .card-header-custom {
                padding: 1.25rem;
            }
            
            .card-body-custom {
                padding: 1.25rem;
            }
        }
        
        @media (max-width: 767px) {
            .locality-hero {
                height: 250px;
                margin-top: 0;
            }
            
            .locality-title {
                font-size: 1.75rem;
            }
            
            .hero-content-wrapper {
                padding-bottom: 1.5rem;
            }
            
            .locality-meta {
                gap: 1rem;
                flex-direction: column;
            }
            
            .meta-item {
                font-size: 0.9rem;
            }
            
            .province-badge {
                font-size: 0.8rem;
                padding: 0.3rem 0.8rem;
            }
            
            .card-header-custom h2 {
                font-size: 1.25rem;
            }
            
            .card-header-custom {
                padding: 1rem;
            }
            
            .card-body-custom {
                padding: 1rem;
            }
            
            .lead-text {
                font-size: 1rem;
            }
            
            .content-card {
                border-radius: 12px;
            }
            
            .festivity-mini-img {
                height: 160px;
            }
            
            .festivity-mini-content {
                padding: 1rem;
                min-height: auto;
            }
            
            .festivity-mini-title {
                font-size: 0.95rem;
            }
            
            .action-buttons .btn {
                font-size: 0.875rem;
            }
        }
        
        @media (max-width: 575px) {
            .locality-hero {
                height: 220px;
            }
            
            .locality-title {
                font-size: 1.5rem;
            }
            
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }
    </style>
</x-app-layout>
