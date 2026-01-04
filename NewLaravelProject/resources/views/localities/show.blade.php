<x-app-layout>
    <div class="container-fluid px-0">
        <!-- Sticky Banner Ad (ad1) - Always on screen -->
        <div class="sticky-banner-ad">
            <div class="container">
                @include('ads.main_banner', ['ad' => $mainAdvertisement, 'newAdParams' => $adCreationParams])
            </div>
        </div>

        <div class="container mt-4 mb-5">

            <!-- Photo Carousel with Info Overlay -->
            @if(!empty($locality->photos) && is_array($locality->photos) && count($locality->photos) > 0)
                <div class="photo-carousel-section mb-4">
                    <div id="localityCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($locality->photos as $index => $photo)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ $photo }}" 
                                         class="d-block w-100 carousel-image-clickable" 
                                         alt="{{ $locality->name }}"
                                         data-photo-index="{{ $index }}"
                                         data-photo-url="{{ $photo }}"
                                         style="height: 350px; object-fit: cover; cursor: pointer;"
                                         onclick="openPhotoModal('{{ $photo }}', {{ $index }}, {{ count($locality->photos) }})">
                                    
                                    <!-- Admin Actions Overlay (Top Right) -->
                                    <div class="vote-overlay-button" onclick="event.stopPropagation();">
                                        <div class="d-flex align-items-center gap-2">
                                            @auth
                                                @can('update', $locality)
                                                    <a href="{{ route('localities.edit', $locality) }}" class="btn btn-sm btn-light vote-btn-overlay" title="Editar localidad">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                @endcan
                                                @can('delete', $locality)
                                                    <form action="{{ route('localities.destroy', $locality) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta localidad?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-light vote-btn-overlay" title="Eliminar localidad" style="background: rgba(220, 53, 69, 0.9) !important; color: white;">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Carousel Controls (Always visible) -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#localityCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#localityCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                    
                    <!-- Info Overlay on Carousel (Name bottom-left, Festivities count bottom-right - clickable) -->
                    <div class="carousel-info-overlay">
                        <div class="info-content-simple">
                            <div class="info-name">{{ $locality->name }}</div>
                            <div class="info-date">
                                <a href="{{ route('festivities.index') }}?locality={{ $locality->slug }}" class="text-white text-decoration-none" style="text-shadow: 0 1px 4px rgba(0,0,0,0.5);">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    {{ $festivities->total() }} {{ $festivities->total() === 1 ? 'festividad' : 'festividades' }}
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Modal for Photo Expansion -->
                    <div class="modal fade" id="photoModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-xl">
                            <div class="modal-content bg-dark">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title text-white">
                                        <span id="photo-counter"></span>
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-0 text-center">
                                    <img id="modal-photo-image" src="" alt="{{ $locality->name }}" class="img-fluid" style="max-height: 85vh; object-fit: contain;">
                                </div>
                                <div class="modal-footer border-0 justify-content-between">
                                    <button type="button" class="btn btn-outline-light" id="prev-photo-btn" onclick="navigatePhoto(-1)">
                                        <i class="bi bi-chevron-left me-1"></i>Anterior
                                    </button>
                                    <button type="button" class="btn btn-outline-light" id="next-photo-btn" onclick="navigatePhoto(1)">
                                        Siguiente<i class="bi bi-chevron-right ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <script>
                        let currentPhotoIndex = 0;
                        let totalPhotos = {{ count($locality->photos) }};
                        let photos = @json($locality->photos);
                        
                        function openPhotoModal(photoUrl, index, total) {
                            currentPhotoIndex = index;
                            totalPhotos = total;
                            document.getElementById('modal-photo-image').src = photoUrl;
                            document.getElementById('photo-counter').textContent = `Foto ${index + 1} de ${total}`;
                            
                            // Show/hide navigation buttons
                            document.getElementById('prev-photo-btn').style.display = total > 1 ? 'block' : 'none';
                            document.getElementById('next-photo-btn').style.display = total > 1 ? 'block' : 'none';
                            
                            const modal = new bootstrap.Modal(document.getElementById('photoModal'));
                            modal.show();
                        }
                        
                        function navigatePhoto(direction) {
                            currentPhotoIndex += direction;
                            
                            if (currentPhotoIndex < 0) {
                                currentPhotoIndex = totalPhotos - 1;
                            } else if (currentPhotoIndex >= totalPhotos) {
                                currentPhotoIndex = 0;
                            }
                            
                            document.getElementById('modal-photo-image').src = photos[currentPhotoIndex];
                            document.getElementById('photo-counter').textContent = `Foto ${currentPhotoIndex + 1} de ${totalPhotos}`;
                        }
                        
                        // Keyboard navigation
                        document.addEventListener('keydown', function(e) {
                            const modal = document.getElementById('photoModal');
                            if (modal.classList.contains('show')) {
                                if (e.key === 'ArrowLeft') {
                                    navigatePhoto(-1);
                                } else if (e.key === 'ArrowRight') {
                                    navigatePhoto(1);
                                } else if (e.key === 'Escape') {
                                    bootstrap.Modal.getInstance(modal).hide();
                                }
                            }
                        });
                    </script>
                </div>
            @else
                <!-- Fallback if no photos -->
                <div class="photo-carousel-section mb-4" style="background: linear-gradient(135deg, #FEB101 0%, #F59E0B 50%, #D97706 100%); height: 350px; display: flex; align-items: center; justify-content: center; border-radius: 12px; position: relative;">
                    <!-- Admin Actions Overlay (Top Right) -->
                    <div class="vote-overlay-button">
                        <div class="d-flex align-items-center gap-2">
                            @auth
                                @can('update', $locality)
                                    <a href="{{ route('localities.edit', $locality) }}" class="btn btn-sm btn-light vote-btn-overlay" title="Editar localidad">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endcan
                                @can('delete', $locality)
                                    <form action="{{ route('localities.destroy', $locality) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta localidad?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light vote-btn-overlay" title="Eliminar localidad" style="background: rgba(220, 53, 69, 0.9) !important; color: white;">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endcan
                            @endauth
                        </div>
                    </div>
                    <div class="carousel-info-overlay">
                        <div class="info-content-simple">
                            <div class="info-name">{{ $locality->name }}</div>
                            <div class="info-date">
                                <a href="{{ route('festivities.index') }}?locality={{ $locality->slug }}" class="text-white text-decoration-none" style="text-shadow: 0 1px 4px rgba(0,0,0,0.5);">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    {{ $festivities->total() }} {{ $festivities->total() === 1 ? 'festividad' : 'festividades' }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Description and Map Section (Single Container) -->
            <div class="content-card mb-4">
                <div class="row g-0">
                    <!-- Description (Columns 1-2) -->
                    <div class="col-lg-8">
                        <div class="card-body-custom">
                            <!-- Province and Address Info -->
                            <div class="festivity-location-info mb-3">
                                @if($locality->province)
                                    <div class="location-item">
                                        <i class="bi bi-map me-1"></i>
                                        <a href="{{ route('localities.index') }}?province={{ urlencode($locality->province) }}" class="text-decoration-none">
                                            <span>{{ $locality->province }}</span>
                                        </a>
                                    </div>
                                @endif
                                @if($locality->address)
                                    <div class="location-item">
                                        <i class="bi bi-geo-alt me-1"></i>
                                        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($locality->address) }}" target="_blank" rel="noopener" class="text-decoration-none">
                                            <span>{{ $locality->address }}</span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Description Text -->
                            <p class="lead-text mb-0">{{ $locality->description }}</p>
                        </div>
                    </div>

                    <!-- Monuments and Places of Interest (Column 3) -->
                    <div class="col-lg-4">
                        <div class="card-body-custom border-start-lg">
                            @if($locality->monuments || $locality->places_of_interest)
                                @if($locality->monuments)
                                    <div class="mb-4">
                                        <h4 class="h5 mb-3">
                                            <i class="bi bi-building me-2" style="color: #FEB101;"></i>Monumentos
                                        </h4>
                                        <div class="content-text">{{ $locality->monuments }}</div>
                                    </div>
                                @endif
                                
                                @if($locality->places_of_interest)
                                    <div>
                                        <h4 class="h5 mb-3">
                                            <i class="bi bi-star me-2" style="color: #FEB101;"></i>Lugares de Interés
                                        </h4>
                                        <div class="content-text">{{ $locality->places_of_interest }}</div>
                                    </div>
                                @endif
                            @else
                                <div class="empty-state">
                                    <i class="bi bi-info-circle"></i>
                                    <p>No hay información adicional disponible</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Festivities Section (4 Columns) -->
            <div class="row g-4 mb-4">
                @php
                    $ad2 = $secondaryAdvertisements->first() ?? null;
                    $ad3 = $secondaryAdvertisements->skip(1)->first() ?? null;
                    $adIndex = 0;
                @endphp
                
                <!-- Column 1: Ad (if available) -->
                <div class="col-lg-3">
                    @if($adIndex < $secondaryAdvertisements->count())
                        @php
                            $currentAd = $secondaryAdvertisements[$adIndex];
                            $adIndex++;
                        @endphp
                        <div class="h-100">
                            @include('ads.secondary_banner', ['ads' => collect([$currentAd]), 'orientation' => 'sidebar', 'newAdParams' => $adCreationParams])
                        </div>
                    @else
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body text-center bg-light border border-2 border-secondary border-opacity-25" style="border-style: dashed; min-height: 300px; display: flex; align-items: center; justify-content: center;">
                                <div>
                                    <div class="text-muted small text-uppercase mb-2">Google Ads</div>
                                    <p class="mb-1 fw-semibold">Bloque publicitario</p>
                                    <p class="mb-0 text-muted small">Placeholder</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Columns 2-3: Festivities -->
                <div class="col-lg-6">
                    <div class="content-card">
                        <div class="card-body-custom">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="h5 mb-0"><i class="bi bi-calendar-event me-2"></i>Festividades</h3>
                                @auth
                                    @can('create', App\Models\Festivity::class)
                                        @if(auth()->user()->isVisitor())
                                            <a href="{{ route('festivities.create') }}?locality_id={{ $locality->id }}" class="btn btn-primary btn-sm" style="background-color: #FEB101; border: none;">
                                                <i class="bi bi-lightbulb me-1"></i>Sugerir una festividad
                                            </a>
                                        @elseif(!auth()->user()->isTownHall() || (auth()->user()->isTownHall() && auth()->user()->locality_id === $locality->id))
                                            <a href="{{ route('festivities.create') }}?locality_id={{ $locality->id }}" class="btn btn-primary btn-sm" style="background-color: #FEB101; border: none;">
                                                <i class="bi bi-plus-circle me-1"></i>Crear Festividad
                                            </a>
                                        @endif
                                    @endcan
                                @endauth
                            </div>
                            @if($festivities->count() > 0)
                                <div class="row g-3">
                                    @foreach($festivities as $festivity)
                                        <div class="col-md-6">
                                            <a href="{{ route('festivities.show', $festivity) }}" class="text-decoration-none" style="color: inherit;">
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
                                                        <div class="festivity-mini-votes mt-auto">
                                                            <div class="d-flex align-items-center" style="gap: 0.4rem;">
                                                                <i class="bi bi-heart-fill" style="color: #F59E0B; font-size: 1rem;"></i>
                                                                <span class="fw-bold" style="color: #1F2937; font-size: 1rem;">{{ $festivity->votes_count ?? 0 }}</span>
                                                                <span style="color: #6B7280; font-size: 0.8rem;">{{ Str::plural('voto', $festivity->votes_count ?? 0) }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <!-- Pagination -->
                                @if($festivities->hasPages())
                                    <div class="mt-4 pt-3 border-top">
                                        {{ $festivities->links('pagination::bootstrap-5') }}
                                    </div>
                                @endif
                            @else
                                <div class="empty-state">
                                    <i class="bi bi-calendar-x"></i>
                                    <p>No hay festividades registradas aún</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Column 4: Ad (if available) -->
                <div class="col-lg-3">
                    @if($adIndex < $secondaryAdvertisements->count())
                        @php
                            $currentAd = $secondaryAdvertisements[$adIndex];
                        @endphp
                        <div class="h-100">
                            @include('ads.secondary_banner', ['ads' => collect([$currentAd]), 'orientation' => 'sidebar', 'newAdParams' => $adCreationParams])
                        </div>
                    @else
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body text-center bg-light border border-2 border-secondary border-opacity-25" style="border-style: dashed; min-height: 300px; display: flex; align-items: center; justify-content: center;">
                                <div>
                                    <div class="text-muted small text-uppercase mb-2">Google Ads</div>
                                    <p class="mb-1 fw-semibold">Bloque publicitario</p>
                                    <p class="mb-0 text-muted small">Placeholder</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Body Background */
        body {
            background-image: url('{{ asset('storage/background.png') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            position: relative;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: white;
            opacity: 0.5;
            z-index: -1;
            pointer-events: none;
        }
        
        main {
            flex: 1;
        }
        
        /* Ensure footer stays at bottom */
        footer {
            margin-top: auto;
        }
        
        /* Sticky Banner Ad */
        .sticky-banner-ad {
            position: sticky;
            top: 0;
            z-index: 100;
            background: transparent;
            box-shadow: none !important;
        }
        
        .sticky-banner-ad section {
            margin-bottom: 0 !important;
        }
        
        .sticky-banner-ad section .card {
            margin-bottom: 0;
            border-radius: 0;
            border: none !important;
            box-shadow: none !important;
        }
        
        .sticky-banner-ad .card-body {
            border: none !important;
            padding: 0 !important;
        }
        
        .sticky-banner-ad img {
            border: none !important;
        }
        
        /* Photo Carousel Section */
        .photo-carousel-section {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.25);
        }
        
        .photo-carousel-section .carousel {
            border-radius: 12px;
        }
        
        .photo-carousel-section .carousel-inner {
            border-radius: 12px;
        }
        
        .photo-carousel-section .carousel-control-prev,
        .photo-carousel-section .carousel-control-next {
            width: 50px;
            height: 50px;
            background: rgba(0,0,0,0.5);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.8;
        }
        
        .photo-carousel-section .carousel-control-prev {
            left: 20px;
        }
        
        .photo-carousel-section .carousel-control-next {
            right: 20px;
        }
        
        .photo-carousel-section .carousel-control-prev:hover,
        .photo-carousel-section .carousel-control-next:hover {
            opacity: 1;
        }
        
        .carousel-image-clickable {
            transition: transform 0.2s ease;
        }
        
        .carousel-image-clickable:hover {
            transform: scale(1.02);
        }
        
        /* Admin Actions Overlay (Top Right) */
        .vote-overlay-button {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 20;
        }
        
        .vote-overlay-button .d-flex {
            flex-wrap: nowrap;
        }
        
        .vote-btn-overlay {
            background: rgba(255, 255, 255, 0.95) !important;
            border: none;
            box-shadow: 0 1px 4px rgba(0,0,0,0.35);
            font-weight: 600;
            padding: 0.375rem 0.75rem;
            min-width: auto;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .vote-btn-overlay:hover {
            background: white !important;
            box-shadow: 0 2px 6px rgba(0,0,0,0.4);
        }
        
        .vote-btn-overlay i {
            font-size: 0.9rem;
        }
        
        /* Carousel Info Overlay (Simplified) */
        .carousel-info-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.8), rgba(0,0,0,0.4), transparent);
            padding: 1.5rem;
            z-index: 10;
        }
        
        .info-content-simple {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            color: white;
        }
        
        .info-name {
            font-size: 1.75rem;
            font-weight: 700;
            text-shadow: 0 2px 8px rgba(0,0,0,0.5);
        }
        
        .info-date {
            font-size: 1rem;
            text-shadow: 0 1px 4px rgba(0,0,0,0.5);
        }
        
        .info-date a:hover {
            text-decoration: underline !important;
        }
        
        /* Location Info in Description */
        .festivity-location-info {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .location-item {
            display: flex;
            align-items: center;
            font-size: 0.95rem;
            color: #4B5563;
        }
        
        .location-item i {
            color: #FEB101;
        }
        
        .location-item a {
            color: #1F2937;
            font-weight: 600;
            transition: color 0.2s ease;
        }
        
        .location-item a:hover {
            color: #FEB101;
            text-decoration: underline;
        }
        
        .location-item a span {
            color: inherit;
        }
        
        /* Content Cards */
        .content-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            overflow: hidden;
            transition: box-shadow 0.3s ease;
        }
        
        .content-card:hover {
            box-shadow: 0 3px 10px rgba(0,0,0,0.3);
        }
        
        /* Override Bootstrap shadow-sm for darker, shorter shadows */
        .shadow-sm,
        .card.shadow-sm {
            box-shadow: 0 1px 3px rgba(0,0,0,0.25) !important;
        }
        
        .rounded.shadow-sm {
            box-shadow: 0 1px 3px rgba(0,0,0,0.25) !important;
        }
        
        .card-body-custom {
            padding: 1.5rem;
        }
        
        .lead-text {
            font-size: 1.125rem;
            line-height: 1.8;
            color: #4B5563;
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
            flex-direction: column;
            background: #f8f9fa;
            border-radius: 12px;
            overflow: hidden;
            min-height: 100%;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
            box-shadow: 0 1px 3px rgba(0,0,0,0.25);
        }
        
        .festivity-mini-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
        }
        
        a .festivity-mini-card {
            text-decoration: none;
            color: inherit;
        }
        
        a:hover .festivity-mini-card {
            text-decoration: none;
        }
        
        .festivity-mini-img {
            width: 100%;
            height: 180px;
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
        
        .festivity-mini-votes {
            padding-top: 0.75rem;
            border-top: 1px solid #F3F4F6;
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
        
        /* Border for map column on large screens */
        @media (min-width: 992px) {
            .border-start-lg {
                border-left: 1px solid #e5e7eb !important;
            }
        }
        
        /* Pagination Styles */
        .pagination {
            margin-bottom: 0;
        }
        
        .pagination .page-link {
            color: #1F2937;
            border-color: #e5e7eb;
            box-shadow: 0 1px 3px rgba(0,0,0,0.25);
            transition: all 0.2s ease;
            padding: 0.5rem 0.75rem;
        }
        
        .pagination .page-link:hover {
            background-color: #FEB101;
            border-color: #FEB101;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .pagination .page-item.active .page-link {
            background-color: #FEB101;
            border-color: #FEB101;
            color: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.25);
        }
        
        .pagination .page-item.disabled .page-link {
            color: #9ca3af;
            background-color: #f9fafb;
            border-color: #e5e7eb;
            cursor: not-allowed;
            box-shadow: none;
        }
        
        .pagination .page-item.disabled .page-link:hover {
            transform: none;
            box-shadow: none;
            background-color: #f9fafb;
            border-color: #e5e7eb;
            color: #9ca3af;
        }
        
        /* Pagination text info */
        .pagination + .small {
            color: #6B7280;
        }
        
        /* Responsive */
        @media (max-width: 991px) {
            .photo-carousel-section .carousel img {
                height: 300px !important;
            }
            
            .info-name {
                font-size: 1.5rem;
            }
            
            .info-date {
                font-size: 0.9rem;
            }
            
            .carousel-info-overlay {
                padding: 1rem;
            }
            
            .festivity-location-info {
                gap: 1rem;
            }
        }
        
        @media (max-width: 767px) {
            .photo-carousel-section .carousel img {
                height: 250px !important;
            }
            
            .info-content-simple {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            
            .info-name {
                font-size: 1.25rem;
            }
            
            .info-date {
                font-size: 0.85rem;
            }
            
            .carousel-info-overlay {
                padding: 1rem;
            }
            
            .vote-overlay-button {
                top: 10px;
                right: 10px;
            }
            
            .photo-carousel-section .carousel-control-prev,
            .photo-carousel-section .carousel-control-next {
                width: 40px;
                height: 40px;
            }
            
            .photo-carousel-section .carousel-control-prev {
                left: 10px;
            }
            
            .photo-carousel-section .carousel-control-next {
                right: 10px;
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
            
            .festivity-location-info {
                flex-direction: column;
                gap: 0.75rem;
            }
        }
        
        @media (max-width: 575px) {
            .photo-carousel-section .carousel img {
                height: 200px !important;
            }
            
            .info-name {
                font-size: 1.1rem;
            }
            
            .info-date {
                font-size: 0.8rem;
            }
            
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }
    </style>
</x-app-layout>
