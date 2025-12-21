<x-app-layout>
    <div class="container-fluid px-0">
        <!-- Sticky Banner Ad (ad1) - Always on screen -->
        <div class="sticky-banner-ad">
            <div class="container">
                @include('ads.main_banner', ['ad' => $mainAdvertisement, 'newAdParams' => $adCreationParams])
            </div>
        </div>

        <div class="container mt-4 mb-5">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($visitPointsEarned ?? false)
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-star-fill me-2"></i>
                    <strong>¡Puntos ganados!</strong> Has obtenido 1 punto por visitar una festividad de otra localidad.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Photo Carousel with Info Overlay -->
            @if(!empty($festivity->photos) && is_array($festivity->photos) && count($festivity->photos) > 0)
                <div class="photo-carousel-section mb-4">
                    <div id="festivityCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($festivity->photos as $index => $photo)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ $photo }}" 
                                         class="d-block w-100 carousel-image-clickable" 
                                         alt="{{ $festivity->name }}"
                                         data-photo-index="{{ $index }}"
                                         data-photo-url="{{ $photo }}"
                                         style="height: 350px; object-fit: cover; cursor: pointer;"
                                         onclick="openPhotoModal('{{ $photo }}', {{ $index }}, {{ count($festivity->photos) }})">
                                    
                                    <!-- Vote Button and Admin Actions Overlay (Top Right) -->
                                    <div class="vote-overlay-button" onclick="event.stopPropagation();">
                                        <div class="d-flex align-items-center gap-2">
                                            @auth
                                                @if(!($votingEnabled ?? true))
                                                    <button type="button" class="btn btn-sm btn-outline-light" disabled>
                                                        <i class="bi bi-pause-circle me-1"></i>
                                                        <span class="vote-count">{{ $festivity->votes_count }}</span>
                                                    </button>
                                                @elseif($userVotedToday)
                                                    <button type="button" class="btn btn-sm btn-outline-light" disabled>
                                                        <i class="bi bi-heart-fill me-1"></i>
                                                        <span class="vote-count">{{ $festivity->votes_count }}</span>
                                                    </button>
                                                @else
                                                    <form action="{{ route('votes.store', $festivity) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-light vote-btn-overlay">
                                                            <i class="bi bi-heart me-1"></i>
                                                            <span class="vote-count">{{ $festivity->votes_count }}</span>
                                                        </button>
                                                    </form>
                                                @endif
                                                
                                                <!-- Admin Action Buttons -->
                                                @can('update', $festivity)
                                                    <a href="{{ route('festivities.edit', $festivity) }}" class="btn btn-sm btn-light vote-btn-overlay" title="Editar festividad">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                @endcan
                                                @can('delete', $festivity)
                                                    <form action="{{ route('festivities.destroy', $festivity) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta festividad?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-light vote-btn-overlay" title="Eliminar festividad" style="background: rgba(220, 53, 69, 0.9) !important; color: white;">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            @else
                                                <a href="{{ route('login') }}" class="btn btn-sm btn-light vote-btn-overlay">
                                                    <i class="bi bi-heart me-1"></i>
                                                    <span class="vote-count">{{ $festivity->votes_count }}</span>
                                                </a>
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Carousel Controls (Always visible) -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#festivityCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#festivityCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                    
                    <!-- Info Overlay on Carousel (Name bottom-left, Date bottom-right) -->
                    <div class="carousel-info-overlay">
                        <div class="info-content-simple">
                            <div class="info-name">{{ $festivity->name }}</div>
                            <div class="info-date">
                                {{ $festivity->start_date->format('d M Y') }}
                                @if($festivity->end_date && $festivity->end_date != $festivity->start_date)
                                    - {{ $festivity->end_date->format('d M Y') }}
                                @endif
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
                                    <img id="modal-photo-image" src="" alt="{{ $festivity->name }}" class="img-fluid" style="max-height: 85vh; object-fit: contain;">
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
                        let totalPhotos = {{ count($festivity->photos) }};
                        let photos = @json($festivity->photos);
                        
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
                    <!-- Vote Button and Admin Actions Overlay (Top Right) -->
                    <div class="vote-overlay-button">
                        <div class="d-flex align-items-center gap-2">
                            @auth
                                @if(!($votingEnabled ?? true))
                                    <button type="button" class="btn btn-sm btn-outline-light" disabled>
                                        <i class="bi bi-pause-circle me-1"></i>
                                        <span class="vote-count">{{ $festivity->votes_count }}</span>
                                    </button>
                                @elseif($userVotedToday)
                                    <button type="button" class="btn btn-sm btn-outline-light" disabled>
                                        <i class="bi bi-heart-fill me-1"></i>
                                        <span class="vote-count">{{ $festivity->votes_count }}</span>
                                    </button>
                                @else
                                    <form action="{{ route('votes.store', $festivity) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-light vote-btn-overlay">
                                            <i class="bi bi-heart me-1"></i>
                                            <span class="vote-count">{{ $festivity->votes_count }}</span>
                                        </button>
                                    </form>
                                @endif
                                
                                <!-- Admin Action Buttons -->
                                @can('update', $festivity)
                                    <a href="{{ route('festivities.edit', $festivity) }}" class="btn btn-sm btn-light vote-btn-overlay" title="Editar festividad">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endcan
                                @can('delete', $festivity)
                                    <form action="{{ route('festivities.destroy', $festivity) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta festividad?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light vote-btn-overlay" title="Eliminar festividad" style="background: rgba(220, 53, 69, 0.9) !important; color: white;">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endcan
                            @else
                                <a href="{{ route('login') }}" class="btn btn-sm btn-light vote-btn-overlay">
                                    <i class="bi bi-heart me-1"></i>
                                    <span class="vote-count">{{ $festivity->votes_count }}</span>
                                </a>
                            @endauth
                        </div>
                    </div>
                    <div class="carousel-info-overlay">
                        <div class="info-content-simple">
                            <div class="info-name">{{ $festivity->name }}</div>
                            <div class="info-date">
                                {{ $festivity->start_date->format('d M Y') }}
                                @if($festivity->end_date && $festivity->end_date != $festivity->start_date)
                                    - {{ $festivity->end_date->format('d M Y') }}
                                @endif
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
                            <!-- Location and Date Info -->
                            <div class="festivity-location-info mb-3">
                                @if($festivity->locality)
                                    <div class="location-item">
                                        <i class="bi bi-geo-alt me-1"></i>
                                        <a href="{{ route('localities.show', $festivity->locality) }}" class="text-decoration-none">
                                            <strong>{{ $festivity->locality->name }}</strong>
                                        </a>
                                    </div>
                                @endif
                                @if($festivity->province)
                                    <div class="location-item">
                                        <i class="bi bi-map me-1"></i>
                                        <span>{{ $festivity->province }}</span>
                                    </div>
                                @endif
                                <div class="location-item">
                                    <i class="bi bi-calendar me-1"></i>
                                    <span>
                                        {{ $festivity->start_date->format('d M Y') }}
                                        @if($festivity->end_date && $festivity->end_date != $festivity->start_date)
                                            - {{ $festivity->end_date->format('d M Y') }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Description Text -->
                            <p class="lead-text mb-0">{{ $festivity->description }}</p>
                        </div>
                    </div>

                    <!-- Map (Column 3) -->
                    <div class="col-lg-4">
                        <div class="card-body-custom border-start-lg">
                            @if($festivity->latitude && $festivity->longitude)
                                <x-google-map 
                                    :latitude="$festivity->latitude" 
                                    :longitude="$festivity->longitude"
                                    :title="$festivity->name"
                                    height="300px"
                                />
                                @if($festivity->google_maps_url)
                                    <div class="mt-3">
                                        <a href="{{ $festivity->google_maps_url }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm w-100" style="border-color: #1FA4A9; color: #1FA4A9;">
                                            <i class="bi bi-box-arrow-up-right me-2"></i>Abrir en Google Maps
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="empty-state">
                                    <i class="bi bi-geo-alt"></i>
                                    <p>No hay ubicación disponible</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Events and Comments Section (4 Columns) -->
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

                <!-- Columns 2-3: Events -->
                <div class="col-lg-6">
                    <!-- Events -->
                    <div class="content-card mb-4">
                        <div class="card-body-custom">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="h5 mb-0"><i class="bi bi-calendar-event me-2"></i>Eventos</h3>
                                <a href="{{ route('events.index', $festivity) }}" class="btn btn-primary btn-sm" style="background-color: #FEB101; border: none;">
                                    <i class="bi bi-eye me-2"></i>Ver Todos
                                </a>
                            </div>
                            @if($festivity->events->count() > 0)
                                <div class="row g-3">
                                    @foreach($festivity->events->take(6) as $event)
                                        <div class="col-md-6">
                                            <div class="event-mini-card">
                                                <h6 class="event-mini-title">
                                                    <i class="bi bi-calendar-check me-1"></i>{{ $event->name }}
                                                </h6>
                                                
                                                @if($event->start_time || $event->end_time)
                                                    <p class="event-mini-time text-muted small mb-2">
                                                        @if($event->start_time)
                                                            <i class="bi bi-clock me-1"></i>
                                                            {{ $event->start_time->format('d/m H:i') }}
                                                            @if($event->end_time)
                                                                - {{ $event->end_time->format('H:i') }}
                                                            @endif
                                                        @endif
                                                    </p>
                                                @endif

                                                @if($event->location)
                                                    <p class="event-mini-location text-muted small mb-2">
                                                        <i class="bi bi-geo-alt me-1"></i>{{ $event->location }}
                                                    </p>
                                                @endif

                                                @if($event->description)
                                                    <p class="event-mini-desc text-muted small">{{ Str::limit($event->description, 80) }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @if($festivity->events->count() > 6)
                                    <div class="text-center mt-3">
                                        <a href="{{ route('events.index', $festivity) }}" class="btn btn-outline-primary btn-sm" style="border-color: #FEB101; color: #FEB101;">
                                            Ver {{ $festivity->events->count() - 6 }} eventos más
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="empty-state">
                                    <i class="bi bi-calendar-x"></i>
                                    <p>No hay eventos programados para esta festividad</p>
                                    @auth
                                        @php
                                            $user = auth()->user();
                                            $canCreate = false;
                                            if ($user->isAdmin()) {
                                                $canCreate = true;
                                            } elseif ($user->isTownHall() && $user->locality_id && $festivity->locality_id === $user->locality_id) {
                                                $canCreate = true;
                                            }
                                        @endphp
                                        @if($canCreate)
                                            <a href="{{ route('events.create', $festivity) }}" class="btn btn-primary btn-sm mt-2 d-inline-flex align-items-center" style="background-color: #FEB101; border: none; white-space: nowrap;">
                                                <i class="bi bi-plus-circle me-1"></i>Crear Primer Evento
                                            </a>
                                        @endif
                                    @endauth
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

            <!-- Comments Section (Full Width - 4 Columns) -->
            <div class="row">
                <div class="col-12">
                    <div class="content-card">
                        <div class="card-body-custom">
                            <h3 class="h5 mb-3"><i class="bi bi-chat-dots me-2"></i>Comentarios</h3>
                            
                            <!-- Comment Form -->
                            @auth
                                <div class="mb-4">
                                    <form method="POST" action="{{ route('comments.store', $festivity) }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row g-3">
                                            <div class="col-md-8">
                                                <label for="content" class="form-label fw-semibold small">
                                                    <i class="bi bi-chat-quote me-1"></i>Comparte tu experiencia
                                                </label>
                                                <textarea name="content" id="content" rows="3" 
                                                        class="form-control @error('content') is-invalid @enderror"
                                                        placeholder="Cuéntanos sobre tu experiencia..." required></textarea>
                                                @error('content')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <label for="photo" class="form-label fw-semibold small">
                                                    <i class="bi bi-image me-1"></i>Foto (opcional)
                                                </label>
                                                <input type="file" 
                                                       name="photo" 
                                                       id="photo" 
                                                       class="form-control form-control-sm @error('photo') is-invalid @enderror"
                                                       accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                                <small class="form-text text-muted d-block mt-1" style="font-size: 0.75rem;">
                                                    JPEG, PNG, GIF, WEBP<br>Máx. 5MB
                                                </small>
                                                @error('photo')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div id="photo-preview" class="mt-2" style="display: none;">
                                                    <img id="preview-image" src="" alt="Vista previa" class="img-thumbnail" style="max-width: 100%; max-height: 150px;">
                                                    <button type="button" class="btn btn-sm btn-danger mt-1 w-100" onclick="clearPhotoPreview()">
                                                        <i class="bi bi-x-circle me-1"></i>Eliminar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-12">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <button type="submit" class="btn btn-primary btn-sm" style="background-color: #FEB101; border: none;">
                                                        <i class="bi bi-send me-2"></i>Publicar Comentario
                                                    </button>
                                                    <small class="text-muted">
                                                        <i class="bi bi-info-circle me-1"></i>Será revisado antes de publicarse
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                
                                <script>
                                    document.getElementById('photo').addEventListener('change', function(e) {
                                        const file = e.target.files[0];
                                        if (file) {
                                            const reader = new FileReader();
                                            reader.onload = function(e) {
                                                document.getElementById('preview-image').src = e.target.result;
                                                document.getElementById('photo-preview').style.display = 'block';
                                            }
                                            reader.readAsDataURL(file);
                                        }
                                    });
                                    
                                    function clearPhotoPreview() {
                                        document.getElementById('photo').value = '';
                                        document.getElementById('preview-image').src = '';
                                        document.getElementById('photo-preview').style.display = 'none';
                                    }
                                </script>
                            @else
                                <div class="alert alert-warning mb-4" role="alert">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    Por favor <a href="{{ route('login') }}" class="alert-link">inicia sesión</a> para compartir tus comentarios sobre esta festividad.
                                </div>
                            @endauth

                            <!-- Approved Comments -->
                            @if($festivity->approvedComments->count() > 0)
                                <div class="mt-4">
                                    <h4 class="h5 fw-bold mb-3">
                                        <i class="bi bi-chat-square-text me-2"></i>
                                        {{ $festivity->approvedComments->count() }} {{ Str::plural('Comentario', $festivity->approvedComments->count()) }}
                                    </h4>
                                    @foreach($festivity->approvedComments as $comment)
                                        <div class="comment-card mb-3">
                                            <div class="comment-body">
                                                <div class="row g-3">
                                                    @if($comment->photo)
                                                        <div class="col-auto">
                                                            <img src="{{ asset($comment->photo) }}" 
                                                                 alt="Foto del comentario de {{ $comment->user->name }}" 
                                                                 class="comment-photo rounded shadow-sm"
                                                                 onclick="openImageModal('{{ asset($comment->photo) }}')"
                                                                 onerror="this.style.display='none';">
                                                        </div>
                                                    @endif
                                                    <div class="col">
                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                            <h6 class="comment-author mb-1">
                                                                <i class="bi bi-person-circle me-1"></i>{{ $comment->user->name }}
                                                            </h6>
                                                            <small class="text-muted">
                                                                <i class="bi bi-clock me-1"></i>{{ $comment->created_at->format('d M Y') }}
                                                            </small>
                                                        </div>
                                                        <div class="comment-text-wrapper">
                                                            <p class="comment-text mb-0" id="comment-text-{{ $comment->id }}">
                                                                @if(strlen($comment->content) > 300)
                                                                    <span class="comment-text-short">{{ Str::limit($comment->content, 300) }}</span>
                                                                    <span class="comment-text-full" style="display: none;">{{ $comment->content }}</span>
                                                                    <button type="button" class="btn btn-link btn-sm p-0 text-primary comment-expand-btn" onclick="toggleComment({{ $comment->id }})" style="text-decoration: none;">
                                                                        <span class="expand-text">Ver más</span>
                                                                        <span class="collapse-text" style="display: none;">Ver menos</span>
                                                                    </button>
                                                                @else
                                                                    {{ $comment->content }}
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    <!-- Modal para ver imagen en grande -->
                                    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Imagen del comentario</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <img id="modal-image" src="" alt="Imagen ampliada" class="img-fluid">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <script>
                                        function openImageModal(imageSrc) {
                                            document.getElementById('modal-image').src = imageSrc;
                                            const modal = new bootstrap.Modal(document.getElementById('imageModal'));
                                            modal.show();
                                        }
                                        
                                        function toggleComment(commentId) {
                                            const commentElement = document.getElementById('comment-text-' + commentId);
                                            const shortText = commentElement.querySelector('.comment-text-short');
                                            const fullText = commentElement.querySelector('.comment-text-full');
                                            const expandBtn = commentElement.querySelector('.comment-expand-btn');
                                            const expandText = expandBtn.querySelector('.expand-text');
                                            const collapseText = expandBtn.querySelector('.collapse-text');
                                            
                                            if (shortText && fullText) {
                                                if (shortText.style.display !== 'none') {
                                                    shortText.style.display = 'none';
                                                    fullText.style.display = 'inline';
                                                    expandText.style.display = 'none';
                                                    collapseText.style.display = 'inline';
                                                } else {
                                                    shortText.style.display = 'inline';
                                                    fullText.style.display = 'none';
                                                    expandText.style.display = 'inline';
                                                    collapseText.style.display = 'none';
                                                }
                                            }
                                        }
                                    </script>
                                </div>
                            @else
                                <div class="empty-state">
                                    <i class="bi bi-chat-square-text"></i>
                                    <p>No hay comentarios aún. ¡Sé el primero en compartir tu experiencia!</p>
                                </div>
                            @endif
                        </div>
                    </div>
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
        
        /* Sticky Banner Ad */
        .sticky-banner-ad {
            position: sticky;
            top: 0;
            z-index: 100;
            background: transparent;
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
        }
        
        .sticky-banner-ad img {
            border: none !important;
        }
        
        /* Ad Name Link Styles */
        .ad-name-link {
            transition: all 0.2s ease;
        }
        
        .ad-name-link:hover {
            background: rgba(0,0,0,0.6) !important;
            text-decoration: underline !important;
            transform: scale(1.05);
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            justify-content: flex-end;
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
        
        /* Vote Button Overlay (Top Right) */
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
        
        .vote-count {
            font-weight: 700;
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
        }
        
        .location-item a:hover {
            color: #FEB101;
        }
        
        /* Comment Expand/Collapse */
        .comment-text-wrapper {
            word-wrap: break-word;
        }
        
        .comment-expand-btn {
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: inline-block;
        }
        
        .comment-expand-btn:hover {
            text-decoration: underline !important;
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
        
        .card-header-custom {
            background: linear-gradient(to right, #F8F9FA, #FFFFFF);
            padding: 1.75rem;
            border-bottom: 2px solid #F3F4F6;
        }
        
        .card-header-custom h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1F2937;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .card-header-custom h2 i {
            color: #F2B705;
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
        
        /* Event Mini Cards */
        .event-mini-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1rem;
            height: 100%;
        }
        
        .event-mini-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: #2d3748;
        }
        
        .event-mini-time,
        .event-mini-location {
            margin-bottom: 0.5rem;
        }
        
        .event-mini-desc {
            font-size: 0.85rem;
            line-height: 1.5;
        }
        
        /* Comment Cards */
        .comment-card {
            background: #f8f9fa;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .comment-body {
            padding: 1rem;
        }
        
        .comment-photo {
            width: 120px;
            height: 120px;
            object-fit: cover;
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        
        .comment-photo:hover {
            transform: scale(1.05);
        }
        
        .comment-author {
            font-weight: 600;
            color: #2d3748;
        }
        
        .comment-text {
            color: #4a5568;
            line-height: 1.6;
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
        
        /* Vote Section */
        .vote-section {
            border-top: 1px solid #e5e7eb;
        }
        
        /* Border for map column on large screens */
        @media (min-width: 992px) {
            .border-start-lg {
                border-left: 1px solid #e5e7eb !important;
            }
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
            
            .action-buttons {
                justify-content: flex-start;
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
