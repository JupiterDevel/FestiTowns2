@php
    $today = now();
    $endOfWeek = now()->endOfWeek();
    
    $isActive = $festivity->start_date <= $endOfWeek &&
                ($festivity->end_date === null || $festivity->end_date >= $today);
    
    $votesCount = $festivity->votes()->count();
@endphp

<div class="col-md-4">
    <div class="card compact-festivity-card h-100">
        <a href="{{ route('festivities.show', $festivity) }}" class="text-decoration-none">
            @if(!empty($festivity->photos) && is_array($festivity->photos) && count($festivity->photos) > 0)
                <img src="{{ $festivity->photos[0] }}" 
                     class="card-img-top compact-card-img" 
                     alt="{{ $festivity->name }}">
            @else
                <div class="card-img-top compact-card-img bg-gradient d-flex align-items-center justify-content-center">
                    <i class="bi bi-calendar-event text-white display-4"></i>
                </div>
            @endif
        </a>
        
        <div class="card-body compact-card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <a href="{{ route('festivities.show', $festivity) }}" class="text-decoration-none">
                    <h5 class="card-title compact-title mb-0">{{ $festivity->name }}</h5>
                </a>
                @if($festivity->province)
                    <span class="badge bg-secondary compact-badge">{{ $festivity->province }}</span>
                @endif
            </div>
            
            <div class="mb-2">
                <p class="text-muted small mb-1">
                    <i class="bi bi-geo-alt me-1"></i>
                    {{ $festivity->locality->name ?? 'Sin localidad' }}
                </p>
                <p class="text-muted small mb-0">
                    <i class="bi bi-calendar me-1"></i>
                    {{ $festivity->start_date->format('d M Y') }}
                    @if($festivity->end_date)
                        - {{ $festivity->end_date->format('d M Y') }}
                    @endif
                </p>
            </div>
            
            @if($festivity->description)
                <p class="card-text text-muted small mb-3" style="line-height: 1.4;">
                    {{ Str::limit($festivity->description, 160) }}
                </p>
            @endif
            
            <div class="mt-auto mb-2">
                @if($isActive)
                    <div>
                        <span class="badge bg-success compact-badge me-1">¡Activa ahora!</span>
                        <small class="text-muted">
                            <i class="bi bi-heart me-1"></i>{{ $votesCount }} {{ $votesCount === 1 ? 'voto' : 'votos' }}
                        </small>
                    </div>
                @else
                    <div>
                        <small class="text-muted">
                            <i class="bi bi-heart me-1"></i>{{ $votesCount }} {{ $votesCount === 1 ? 'voto' : 'votos' }}
                        </small>
                    </div>
                @endif
            </div>
            
            @auth
                @if(auth()->user()->isAdmin())
                    <div>
                        <div class="d-flex gap-1">
                            <a href="{{ route('festivities.edit', $festivity) }}" 
                               class="btn btn-sm btn-outline-secondary flex-fill"
                               title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" 
                                  action="{{ route('festivities.destroy', $festivity) }}" 
                                  class="d-inline flex-fill"
                                  onsubmit="return confirm('¿Eliminar esta festividad?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-sm btn-outline-danger w-100"
                                        title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            @endauth
        </div>
    </div>
</div>

