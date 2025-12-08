@php
    $today = now();
    $endOfWeek = now()->endOfWeek();
    
    // Get active festivities
    $activeFestivities = $locality->festivities->filter(function ($festivity) use ($today, $endOfWeek) {
        return $festivity->start_date <= $endOfWeek &&
               ($festivity->end_date === null || $festivity->end_date >= $today);
    });
    
    $activeFestivitiesCount = $activeFestivities->count();
    
    // Get next upcoming festivity if no active ones
    $nextFestivity = null;
    if ($activeFestivitiesCount === 0) {
        $nextFestivity = \App\Models\Festivity::where('locality_id', $locality->id)
            ->where('start_date', '>', $today)
            ->orderBy('start_date', 'asc')
            ->first();
    }
@endphp

<div class="col-md-4">
    <div class="card compact-locality-card h-100">
        <a href="{{ route('localities.show', $locality) }}" class="text-decoration-none">
            @if($locality->photos && count($locality->photos) > 0)
                <img src="{{ $locality->photos[0] }}" 
                     class="card-img-top compact-card-img" 
                     alt="{{ $locality->name }}">
            @else
                <div class="card-img-top compact-card-img bg-gradient d-flex align-items-center justify-content-center">
                    <i class="bi bi-geo-alt text-white display-4"></i>
                </div>
            @endif
        </a>
        
        <div class="card-body compact-card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <a href="{{ route('localities.show', $locality) }}" class="text-decoration-none">
                    <h5 class="card-title compact-title mb-0">{{ $locality->name }}</h5>
                </a>
                @if($locality->province)
                    <span class="badge bg-secondary compact-badge">{{ $locality->province }}</span>
                @endif
            </div>

            @if($locality->description)
                <p class="card-text text-muted small mb-3" style="line-height: 1.4;">
                    {{ Str::limit($locality->description, 160) }}
                </p>
            @endif
            
            <div class="mt-auto mb-2">
                @if($activeFestivitiesCount > 0)
                    <div>
                        <span class="badge bg-success compact-badge me-1">¡De Fiesta!</span>
                        <small class="text-muted">
                            {{ $activeFestivitiesCount }} {{ $activeFestivitiesCount === 1 ? 'festividad' : 'festividades' }}
                        </small>
                    </div>
                @elseif($nextFestivity)
                    <div>
                        <small class="text-muted">
                            Próxima: {{ $nextFestivity->name }} - {{ $nextFestivity->start_date->format('d M Y') }}
                        </small>
                    </div>
                @endif
            </div>
            
            @auth
                @if(auth()->user()->isAdmin())
                    <div>
                        <div class="d-flex gap-1">
                            <a href="{{ route('localities.edit', $locality) }}" 
                               class="btn btn-sm btn-outline-secondary flex-fill"
                               title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" 
                                  action="{{ route('localities.destroy', $locality) }}" 
                                  class="d-inline flex-fill"
                                  onsubmit="return confirm('¿Eliminar esta localidad?')">
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

