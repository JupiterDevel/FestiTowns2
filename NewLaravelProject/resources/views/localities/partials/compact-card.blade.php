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
            <div style="position: relative;">
                @if($locality->photos && count($locality->photos) > 0)
                    <img src="{{ $locality->photos[0] }}" 
                         class="card-img-top compact-card-img" 
                         alt="{{ $locality->name }}">
                @else
                    <div class="card-img-top compact-card-img bg-gradient d-flex align-items-center justify-content-center">
                        <i class="bi bi-geo-alt text-white display-4"></i>
                    </div>
                @endif

                {{-- Badges superpuestos sobre la imagen --}}
                @if($activeFestivitiesCount > 0)
                    <span class="badge bg-success compact-badge position-absolute top-0 start-0 m-3"
                          style="font-weight: 600; padding: 0.4rem 0.75rem; border-radius: 999px;">
                        ¡De Fiesta!
                    </span>
                @endif

                @if($locality->province)
                    <span class="badge compact-badge position-absolute top-0 end-0 m-3"
                          style="background: linear-gradient(135deg, #FEB101 0%, #F59E0B 100%); color: #FFFFFF; font-weight: 600; padding: 0.5rem 0.75rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(254, 177, 1, 0.4);">
                        {{ $locality->province }}
                    </span>
                @endif
            </div>
        </a>
        
        <div class="card-body compact-card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <a href="{{ route('localities.show', $locality) }}" class="text-decoration-none">
                    <h5 class="card-title compact-title mb-1">{{ $locality->name }}</h5>
                </a>
            </div>

            @if($locality->description)
                <p class="card-text text-muted mb-3" style="font-size: 0.9rem; line-height: 1.55;">
                    {{ Str::limit($locality->description, 160) }}
                </p>
            @endif

            <div class="mt-auto d-flex align-items-center justify-content-between" style="padding-top: 0.85rem; border-top: 1px solid #F3F4F6;">
                <div class="d-flex align-items-center gap-2">
                    @if($nextFestivity && $activeFestivitiesCount === 0)
                        <span class="badge compact-badge fw-semibold"
                              style="background-color: #1FA4A9; color: #FFFFFF; max-width: 100%; overflow: hidden; text-overflow: ellipsis; font-size: 0.8rem; padding: 0.25rem 0.6rem; border-radius: 6px; white-space: nowrap; line-height: 1;">
                            ▶▶ {{ Str::limit($nextFestivity->name, 36) }}
                        </span>
                    @endif
                </div>

                <a href="{{ route('localities.show', $locality) }}"
                   class="text-decoration-none"
                   style="color: #FEB101; font-size: 0.83rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; line-height: 1;">
                    Ver más →
                </a>
            </div>
            
            @auth
                @if(auth()->user()->isAdmin())
                    <div class="mt-3 pt-3" style="border-top: 1px solid #E5E7EB;">
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

