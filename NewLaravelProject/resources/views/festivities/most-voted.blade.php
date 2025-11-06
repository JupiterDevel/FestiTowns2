<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="display-6 fw-bold text-primary mb-0">
                <i class="bi bi-star-fill me-2"></i>Las Más Votadas
            </h1>
            <div class="text-muted">
                <span class="badge bg-primary fs-6">{{ $mostVotedFestivities->count() }}</span> festividades
            </div>
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

        @if($mostVotedFestivities->count() > 0)
            <div class="alert alert-info mb-4" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Regla de votación:</strong> Cada usuario puede votar una sola vez al día por cualquier festividad.
            </div>
            
            <div class="row">
                @foreach($mostVotedFestivities as $festivity)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            @if($festivity->photos && count($festivity->photos) > 0)
                                <img src="{{ $festivity->photos[0] }}" 
                                     class="card-img-top" 
                                     alt="{{ $festivity->name }}" 
                                     style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                </div>
                            @endif
                            
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold text-primary">{{ $festivity->name }}</h5>
                                <p class="text-muted mb-2">
                                    <i class="bi bi-geo-alt me-1"></i>{{ $festivity->locality->name }}
                                </p>
                                
                                @if($festivity->description)
                                    <p class="card-text text-muted small mb-3">{{ Str::limit($festivity->description, 100) }}</p>
                                @endif
                                
                                <div class="mt-auto">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-star-fill text-warning me-2"></i>
                                            <span class="fw-bold text-primary fs-5">{{ $festivity->votes_count }}</span>
                                            <span class="text-muted ms-1">{{ Str::plural('voto', $festivity->votes_count) }}</span>
                                        </div>
                                        

                                    </div>
                                    
                                    <div class="d-grid">
                                        <a href="{{ route('festivities.show', $festivity) }}" 
                                           class="btn btn-primary btn-sm">
                                            <i class="bi bi-eye me-1"></i>Ver detalles
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <div class="card border-0">
                    <div class="card-body">
                        <i class="bi bi-star display-1 text-muted mb-3"></i>
                        <h3 class="card-title">No hay festividades votadas</h3>
                        <p class="card-text text-muted">¡Sé el primero en votar por tu festividad favorita!</p>
                        <a href="{{ route('festivities.index') }}" class="btn btn-primary">
                            <i class="bi bi-calendar-event me-1"></i>Ver todas las festividades
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
