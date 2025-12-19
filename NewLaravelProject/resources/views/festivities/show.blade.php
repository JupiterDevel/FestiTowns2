<x-app-layout>
    <!-- Hero Header with Festivity Info -->
    <div class="festivity-hero">
        @if(!empty($festivity->photos) && is_array($festivity->photos) && count($festivity->photos) > 0)
            <div class="hero-image" style="background-image: url('{{ $festivity->photos[0] }}');"></div>
            <div class="hero-overlay"></div>
        @else
            <div class="hero-gradient"></div>
        @endif
        
        <div class="hero-content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="festivity-header-content">
                            @if($festivity->province)
                                <span class="province-badge">{{ $festivity->province }}</span>
                            @endif
                            <h1 class="festivity-title">{{ $festivity->name }}</h1>
                            <div class="festivity-meta">
                                @if($festivity->locality)
                                    <span class="meta-item">
                                        <i class="bi bi-geo-alt me-1"></i>
                                        <a href="{{ route('localities.show', $festivity->locality) }}" class="text-white text-decoration-none">
                                            {{ $festivity->locality->name }}
                                        </a>
                                    </span>
                                @endif
                                <span class="meta-item">
                                    <i class="bi bi-calendar me-1"></i>
                                    {{ $festivity->start_date->format('d M Y') }}
                                    @if($festivity->end_date && $festivity->end_date != $festivity->start_date)
                                        - {{ $festivity->end_date->format('d M Y') }}
                                    @endif
                                </span>
                                <span class="meta-item">
                                    <i class="bi bi-heart me-1"></i>
                                    {{ $festivity->votes_count }} {{ $festivity->votes_count === 1 ? 'voto' : 'votos' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        @auth
                            <div class="action-buttons">
                                @can('update', $festivity)
                                    <a href="{{ route('festivities.edit', $festivity) }}" class="btn btn-light btn-sm">
                                        <i class="bi bi-pencil me-1"></i>Editar
                                    </a>
                                @endcan
                                @can('delete', $festivity)
                                    <form method="POST" action="{{ route('festivities.destroy', $festivity) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                onclick="return confirm('¿Eliminar esta festividad?')">
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

        @include('ads.main_banner', ['ad' => $mainAdvertisement, 'newAdParams' => $adCreationParams])

        <div class="row g-4">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Description Card -->
                <div class="content-card mb-4">
                    <div class="card-header-custom">
                        <h2><i class="bi bi-info-circle me-2"></i>Sobre {{ $festivity->name }}</h2>
                    </div>
                    <div class="card-body-custom">
                        <p class="lead-text">{{ $festivity->description }}</p>
                    </div>
                </div>

                <!-- AdSense Advertisement -->
                <div class="mb-4">
                    <x-adsense-ad 
                        clientId="ca-pub-5837712015612104" 
                        slotId="6300978111"
                        type="display"
                        style="display:block; min-height: 250px; width: 100%;"
                        format="horizontal"
                        testMode="true"
                    />
                </div>

                <!-- Photo Gallery -->
                @if(!empty($festivity->photos) && is_array($festivity->photos) && count($festivity->photos) > 0)
                    <div class="content-card mb-4">
                        <div class="card-header-custom">
                            <h2><i class="bi bi-images me-2"></i>Galería</h2>
                        </div>
                        <div class="card-body-custom">
                            <div id="festivityCarousel" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-indicators">
                                    @foreach($festivity->photos as $index => $photo)
                                        <button type="button" data-bs-target="#festivityCarousel" data-bs-slide-to="{{ $index }}" 
                                                class="{{ $index === 0 ? 'active' : '' }}"></button>
                                    @endforeach
                                </div>
                                
                                <div class="carousel-inner rounded">
                                    @foreach($festivity->photos as $index => $photo)
                                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                            <img src="{{ $photo }}" 
                                                 class="d-block w-100" 
                                                 alt="{{ $festivity->name }}"
                                                 style="height: 450px; object-fit: cover;">
                                        </div>
                                    @endforeach
                                </div>
                                
                                @if(count($festivity->photos) > 1)
                                    <button class="carousel-control-prev" type="button" data-bs-target="#festivityCarousel" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon"></span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#festivityCarousel" data-bs-slide="next">
                                        <span class="carousel-control-next-icon"></span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Vote Section -->
                <div class="content-card mb-4">
                    <div class="card-header-custom">
                        <h2><i class="bi bi-heart me-2"></i>Votar</h2>
                    </div>
                    <div class="card-body-custom">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-heart-fill text-danger me-2" style="font-size: 1.5rem;"></i>
                                <div>
                                    <div class="fw-bold text-primary" style="font-size: 1.25rem;">{{ $festivity->votes_count }}</div>
                                    <small class="text-muted">{{ Str::plural('voto', $festivity->votes_count) }}</small>
                                </div>
                            </div>
                            
                            @auth
                                @if(!($votingEnabled ?? true))
                                    <button type="button" class="btn btn-outline-secondary btn-sm" disabled>
                                        <i class="bi bi-pause-circle me-1"></i>Votaciones pausadas
                                    </button>
                                @elseif($userVotedToday)
                                    <button type="button" class="btn btn-outline-secondary btn-sm" disabled>
                                        <i class="bi bi-check-circle me-1"></i>Ya votaste hoy
                                    </button>
                                @else
                                    <form action="{{ route('votes.store', $festivity) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-lg" style="background-color: #FEB101; border: none; padding: 0.75rem 2rem;">
                                            <i class="bi bi-heart-fill me-2"></i>Votar
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg" style="border-color: #FEB101; color: #FEB101; padding: 0.75rem 2rem;">
                                    <i class="bi bi-heart me-2"></i>Inicia sesión para votar
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>

                <!-- Google Maps Section -->
                @if($festivity->latitude && $festivity->longitude)
                    <div class="content-card mb-4">
                        <div class="card-header-custom">
                            <h2><i class="bi bi-geo-alt-fill me-2"></i>Ubicación</h2>
                        </div>
                        <div class="card-body-custom">
                            <x-google-map 
                                :latitude="$festivity->latitude" 
                                :longitude="$festivity->longitude"
                                :title="$festivity->name"
                                height="400px"
                            />
                            @if($festivity->google_maps_url)
                                <div class="mt-3">
                                    <a href="{{ $festivity->google_maps_url }}" target="_blank" rel="noopener" class="btn btn-outline-primary" style="border-color: #1FA4A9; color: #1FA4A9;">
                                        <i class="bi bi-box-arrow-up-right me-2"></i>Abrir en Google Maps
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Events Section -->
                <div class="content-card mb-4">
                    <div class="card-header-custom d-flex justify-content-between align-items-center">
                        <h2><i class="bi bi-calendar-event me-2"></i>Eventos Programados</h2>
                        <a href="{{ route('events.index', $festivity) }}" class="btn btn-primary btn-sm" style="background-color: #FEB101; border: none;">
                            <i class="bi bi-eye me-2"></i>Ver Todos
                        </a>
                    </div>
                    <div class="card-body-custom">
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
                                        <a href="{{ route('events.create', $festivity) }}" class="btn btn-primary btn-sm mt-2" style="background-color: #FEB101; border: none;">
                                            <i class="bi bi-plus-circle me-2"></i>Crear Primer Evento
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Mobile Ads -->
                <div class="d-lg-none mb-4">
                    @include('ads.secondary_banner', ['ads' => $secondaryAdvertisements, 'orientation' => 'inline', 'newAdParams' => $adCreationParams])
                </div>

                <!-- Comments Section -->
                <div class="content-card">
                    <div class="card-header-custom">
                        <h2><i class="bi bi-chat-dots me-2"></i>Comentarios</h2>
                    </div>
                    <div class="card-body-custom">
                        <!-- Comment Form -->
                        @auth
                            <div class="mb-4">
                                <form method="POST" action="{{ route('comments.store', $festivity) }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="content" class="form-label fw-semibold">
                                            <i class="bi bi-chat-quote me-1"></i>Comparte tu experiencia
                                        </label>
                                        <textarea name="content" id="content" rows="4" 
                                                class="form-control @error('content') is-invalid @enderror"
                                                placeholder="Cuéntanos sobre tu experiencia en esta festividad..." required></textarea>
                                        @error('content')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="photo" class="form-label fw-semibold">
                                            <i class="bi bi-image me-1"></i>Foto (opcional)
                                        </label>
                                        <input type="file" 
                                               name="photo" 
                                               id="photo" 
                                               class="form-control @error('photo') is-invalid @enderror"
                                               accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                        <small class="form-text text-muted">
                                            Formatos permitidos: JPEG, PNG, JPG, GIF, WEBP. Tamaño máximo: 5MB
                                        </small>
                                        @error('photo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div id="photo-preview" class="mt-2" style="display: none;">
                                            <img id="preview-image" src="" alt="Vista previa" class="img-thumbnail" style="max-width: 300px; max-height: 300px;">
                                            <button type="button" class="btn btn-sm btn-danger mt-2" onclick="clearPhotoPreview()">
                                                <i class="bi bi-x-circle me-1"></i>Eliminar foto
                                            </button>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary" style="background-color: #FEB101; border: none; padding: 0.75rem 1.5rem;">
                                        <i class="bi bi-send me-2"></i>Publicar Comentario
                                    </button>
                                </form>
                                <div class="alert alert-info mt-3" role="alert">
                                    <i class="bi bi-info-circle me-2"></i>Tu comentario será revisado antes de ser publicado.
                                </div>
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
                                                    <p class="comment-text mb-0">{{ $comment->content }}</p>
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

            <!-- Sidebar -->
            <div class="col-lg-4 d-none d-lg-block">
                @include('ads.secondary_banner', ['ads' => $secondaryAdvertisements, 'orientation' => 'sidebar', 'newAdParams' => $adCreationParams])
            </div>
        </div>
    </div>

    <style>
        /* Hero Section */
        .festivity-hero {
            position: relative;
            height: 450px;
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
            filter: brightness(0.8);
        }
        
        .hero-gradient {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #FEB101 0%, #F59E0B 50%, #D97706 100%);
        }
        
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, rgba(254, 177, 1, 0.7), rgba(254, 177, 1, 0.95));
        }
        
        .hero-content-wrapper {
            position: relative;
            z-index: 10;
            height: 100%;
            display: flex;
            align-items: flex-end;
            padding-bottom: 3rem;
        }
        
        .festivity-header-content {
            color: white;
        }
        
        .province-badge {
            display: inline-block;
            background: rgba(255,255,255,0.25);
            backdrop-filter: blur(10px);
            padding: 0.5rem 1.25rem;
            border-radius: 25px;
            font-size: 0.9375rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            border: 1px solid rgba(255,255,255,0.3);
        }
        
        .festivity-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin: 0.5rem 0;
            text-shadow: 0 2px 12px rgba(0,0,0,0.3);
            line-height: 1.2;
        }
        
        .festivity-meta {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
            margin-top: 1rem;
        }
        
        .meta-item {
            font-size: 1rem;
            opacity: 0.95;
        }
        
        .meta-item a:hover {
            text-decoration: underline !important;
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
            border-radius: 20px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: box-shadow 0.3s ease;
        }
        
        .content-card:hover {
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
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
        
        /* Responsive */
        @media (max-width: 991px) {
            .festivity-hero {
                height: 350px;
            }
            
            .festivity-title {
                font-size: 2.5rem;
            }
            
            .hero-content-wrapper {
                padding-bottom: 2rem;
            }
            
            .action-buttons {
                margin-top: 1rem;
                justify-content: flex-start;
            }
        }
        
        @media (max-width: 767px) {
            .festivity-hero {
                height: 300px;
                margin-top: 0;
            }
            
            .festivity-title {
                font-size: 2rem;
            }
            
            .hero-content-wrapper {
                padding-bottom: 1.5rem;
            }
            
            .festivity-meta {
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
        }
        
        @media (max-width: 575px) {
            .festivity-hero {
                height: 220px;
            }
            
            .festivity-title {
                font-size: 1.5rem;
            }
            
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }
        
        /* AdSense Wrapper - Hide if empty */
        .adsense-wrapper {
            min-height: 0;
        }
        
        /* Hide wrapper if AdSense doesn't load content */
        .adsense-wrapper:has(ins:empty) {
            display: none;
            margin: 0;
            padding: 0;
        }
    </style>
    
</x-app-layout>
