<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="display-6 fw-bold text-primary mb-0">
                <i class="bi bi-calendar-event me-2" aria-hidden="true"></i>{{ $festivity->name }}
            </h1>
            @auth
                <div class="d-flex gap-2">
                    @can('update', $festivity)
                        <a href="{{ route('festivities.edit', $festivity) }}" class="btn btn-warning btn-custom">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </a>
                    @endcan
                    @can('delete', $festivity)
                        <form method="POST" action="{{ route('festivities.destroy', $festivity) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-custom" 
                                    onclick="return confirm('Are you sure you want to delete this festivity?')">
                                <i class="bi bi-trash me-1"></i>Delete
                            </button>
                        </form>
                    @endcan
                </div>
            @endauth
        </div>
    </x-slot>

    <div class="container">
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

        <!-- Photos Carousel -->
        @if($festivity->photos && count($festivity->photos) > 0)
            <div class="mb-4">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div id="festivityCarousel" class="carousel slide shadow-lg rounded-3" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                @foreach($festivity->photos as $index => $photo)
                                    <button type="button" data-bs-target="#festivityCarousel" data-bs-slide-to="{{ $index }}" 
                                            class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                            aria-label="Slide {{ $index + 1 }}"></button>
                                @endforeach
                            </div>
                            
                            <div class="carousel-inner">
                                @foreach($festivity->photos as $index => $photo)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ $photo }}" 
                                             class="d-block w-100" 
                                             alt="{{ $festivity->name }} - Imagen {{ $index + 1 }}" 
                                             loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                                             style="height: 400px; object-fit: cover;"
                                             width="1200"
                                             height="400">
                                    </div>
                                @endforeach
                            </div>
                            
                            @if(count($festivity->photos) > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#festivityCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#festivityCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Basic Information -->
        <article class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="card-title h4 fw-bold mb-3">{{ $festivity->name }}</h2>
                        <p class="text-muted mb-3">
                            <i class="bi bi-geo-alt me-2"></i><strong>Location:</strong> 
                            <a href="{{ route('localities.show', $festivity->locality) }}" class="btn btn-outline-primary btn-sm ms-2">
                                <i class="bi bi-eye me-1"></i>{{ $festivity->locality->name }}
                            </a>
                        </p>
                        <p class="text-muted mb-3">
                            <i class="bi bi-calendar me-2"></i><strong>Date:</strong> 
                            {{ $festivity->start_date->format('F j, Y') }}
                            @if($festivity->end_date && $festivity->end_date != $festivity->start_date)
                                - {{ $festivity->end_date->format('F j, Y') }}
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h3 class="h5 fw-bold mb-3">Sobre esta Festividad</h3>
                        <p class="card-text">{{ $festivity->description }}</p>
                        
                        <!-- Vote Section -->
                        <div class="mt-4 p-3 bg-light rounded">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-star-fill text-warning me-2"></i>
                                    <span class="fw-bold text-primary">{{ $festivity->votes_count }}</span>
                                    <span class="text-muted ms-1">{{ Str::plural('vote', $festivity->votes_count) }}</span>
                                </div>
                                
                                @auth
                                    @if($userVotedToday)
                                        <button type="button" class="btn btn-outline-secondary btn-sm" disabled>
                                            <i class="bi bi-check-circle me-1"></i>Ya votaste hoy
                                        </button>
                                    @else
                                        <form action="{{ route('votes.store', $festivity) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-heart me-1"></i>Votar
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="bi bi-heart me-1"></i>Inicia sesión para votar
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article>

        <!-- Events Section -->
        <section class="card mb-4" aria-label="Eventos programados">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="card-title h4 fw-bold mb-0">
                        <i class="bi bi-calendar-event me-2" aria-hidden="true"></i>Eventos Programados
                    </h2>
                    <a href="{{ route('events.index', $festivity) }}" class="btn btn-outline-primary btn-custom">
                        <i class="bi bi-eye me-1"></i>Ver Todos los Eventos
                    </a>
                </div>

                @if($festivity->events->count() > 0)
                    <div class="row">
                        @foreach($festivity->events->take(6) as $event)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100 border-0 bg-light">
                                    <div class="card-body p-3">
                                        <h6 class="card-title fw-bold mb-2">
                                            <i class="bi bi-calendar-check me-1"></i>{{ $event->name }}
                                        </h6>
                                        
                                        @if($event->start_time || $event->end_time)
                                            @if($event->start_time)
                                                <p class="text-muted small mb-1">
                                                    <i class="bi bi-clock me-1"></i>
                                                    <strong>Inicio:</strong> {{ $event->start_time->format('d/m H:i') }}
                                                </p>
                                            @endif
                                            @if($event->end_time)
                                                <p class="text-muted small mb-1">
                                                    <i class="bi bi-clock-fill me-1"></i>
                                                    <strong>Fin:</strong> {{ $event->end_time->format('d/m H:i') }}
                                                </p>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary small">
                                                <i class="bi bi-question-circle me-1"></i>Sin horario
                                            </span>
                                        @endif

                                        @if($event->location)
                                            <p class="text-muted small mb-2">
                                                <i class="bi bi-geo-alt me-1"></i>{{ $event->location }}
                                            </p>
                                        @endif

                                        @if($event->description)
                                            <p class="card-text small text-muted">{{ Str::limit($event->description, 60) }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($festivity->events->count() > 6)
                        <div class="text-center mt-3">
                            <a href="{{ route('events.index', $festivity) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-arrow-right me-1"></i>Ver {{ $festivity->events->count() - 6 }} eventos más
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-calendar-x display-4 text-muted"></i>
                        <p class="text-muted mt-3">No hay eventos programados para esta festividad.</p>
                        <a href="{{ route('events.create', $festivity) }}" class="btn btn-primary btn-custom">
                            <i class="bi bi-plus-circle me-1"></i>Crear Primer Evento
                        </a>
                    </div>
                @endif
            </div>
        </section>

        <!-- Comments Section -->
        <section class="card" aria-label="Comentarios">
            <div class="card-body">
                <h2 class="card-title h4 fw-bold mb-4">
                    <i class="bi bi-chat-dots me-2" aria-hidden="true"></i>Comentarios
                </h2>

                <!-- Comment Form -->
                @auth
                    <div class="mb-4">
                        <form method="POST" action="{{ route('comments.store', $festivity) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="content" class="form-label fw-bold">
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
                                <label for="photo" class="form-label fw-bold">
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
                            <button type="submit" class="btn btn-primary btn-custom">
                                <i class="bi bi-send me-1"></i>Publicar Comentario
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
                        Please <a href="{{ route('login') }}" class="alert-link">login</a> to share your comments about this festivity.
                    </div>
                @endauth

                <!-- Approved Comments -->
                @if($festivity->approvedComments->count() > 0)
                    <div class="mt-4">
                        <h4 class="h5 fw-bold mb-3">
                            <i class="bi bi-chat-square-text me-2"></i>
                            {{ $festivity->approvedComments->count() }} {{ Str::plural('Comment', $festivity->approvedComments->count()) }}
                        </h4>
                        @foreach($festivity->approvedComments as $comment)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row g-3">
                                        @if($comment->photo)
                                            <div class="col-auto">
                                                <img src="{{ asset($comment->photo) }}" 
                                                     alt="Foto del comentario de {{ $comment->user->name }}" 
                                                     class="rounded shadow-sm"
                                                     style="width: 120px; height: 120px; object-fit: cover; cursor: pointer;"
                                                     onclick="openImageModal('{{ asset($comment->photo) }}')"
                                                     onerror="this.style.display='none';">
                                            </div>
                                        @endif
                                        <div class="col">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="card-subtitle mb-1">
                                                    <i class="bi bi-person-circle me-1"></i>{{ $comment->user->name }}
                                                </h6>
                                                <small class="text-muted">
                                                    <i class="bi bi-clock me-1"></i>{{ $comment->created_at->format('M j, Y') }}
                                                </small>
                                            </div>
                                            <p class="card-text mb-0">{{ $comment->content }}</p>
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
                    <div class="text-center py-4">
                        <i class="bi bi-chat-square-text display-4 text-muted"></i>
                        <p class="text-muted mt-3">No comments yet. Be the first to share your experience!</p>
                    </div>
                @endif
            </div>
        </section>
    </div>

</x-app-layout>
