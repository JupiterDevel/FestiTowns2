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
            <i class="bi bi-geo-alt me-1"></i>{{ $festivity->locality->name ?? 'Sin localidad' }}
            @if($festivity->province)
                <span class="text-muted">({{ $festivity->province }})</span>
            @endif
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

