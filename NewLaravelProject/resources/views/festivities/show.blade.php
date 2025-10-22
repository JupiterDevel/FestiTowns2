<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="display-6 fw-bold text-primary mb-0">
                <i class="bi bi-calendar-event me-2"></i>{{ $festivity->name }}
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
                                        <img src="{{ $photo }}" class="d-block w-100" alt="{{ $festivity->name }}" style="height: 400px; object-fit: cover;">
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
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="card-title h4 fw-bold mb-3">{{ $festivity->name }}</h3>
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
                        <h4 class="h5 fw-bold mb-3">About this Festivity</h4>
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
        </div>

        <!-- Comments Section -->
        <div class="card">
            <div class="card-body">
                <h3 class="card-title h4 fw-bold mb-4">
                    <i class="bi bi-chat-dots me-2"></i>Comments
                </h3>

                <!-- Comment Form -->
                @auth
                    <div class="mb-4">
                        <form method="POST" action="{{ route('comments.store', $festivity) }}">
                            @csrf
                            <div class="mb-3">
                                <label for="content" class="form-label fw-bold">
                                    <i class="bi bi-chat-quote me-1"></i>Share your experience
                                </label>
                                <textarea name="content" id="content" rows="4" 
                                        class="form-control @error('content') is-invalid @enderror"
                                        placeholder="Tell us about your experience at this festivity..." required></textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary btn-custom">
                                <i class="bi bi-send me-1"></i>Post Comment
                            </button>
                        </form>
                        <div class="alert alert-info mt-3" role="alert">
                            <i class="bi bi-info-circle me-2"></i>Your comment will be reviewed before being published.
                        </div>
                    </div>
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
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-subtitle mb-1">
                                            <i class="bi bi-person-circle me-1"></i>{{ $comment->user->name }}
                                        </h6>
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>{{ $comment->created_at->format('M j, Y') }}
                                        </small>
                                    </div>
                                    <p class="card-text">{{ $comment->content }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-chat-square-text display-4 text-muted"></i>
                        <p class="text-muted mt-3">No comments yet. Be the first to share your experience!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

</x-app-layout>
