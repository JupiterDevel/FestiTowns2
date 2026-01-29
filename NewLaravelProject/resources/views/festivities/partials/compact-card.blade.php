@php
    $today = now();
    $endOfWeek = now()->endOfWeek();
    
    $isActive = $festivity->start_date <= $endOfWeek &&
                ($festivity->end_date === null || $festivity->end_date >= $today);
    
    $votesCount = $festivity->votes()->count();
@endphp

<div class="col-md-4 mb-4">
        <div class="card festivity-card-modern h-100" style="border-radius: 16px;">
        <a href="{{ route('festivities.show', $festivity) }}" class="text-decoration-none position-relative">
            @if(!empty($festivity->photos) && is_array($festivity->photos) && count($festivity->photos) > 0)
                <div style="position: relative; height: 220px; overflow: hidden;">
                    <img src="{{ $festivity->photos[0] }}" 
                         class="w-100 h-100" 
                         alt="{{ $festivity->name }}"
                         style="object-fit: cover; transition: transform 0.4s ease;">
                    <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 80px; background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);"></div>
                    @if($festivity->province)
                        <span class="badge position-absolute top-0 end-0 m-3"
                              style="background: linear-gradient(135deg, #FEB101 0%, #F59E0B 100%); color: #FFFFFF; font-weight: 600; padding: 0.5rem 0.75rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(254, 177, 1, 0.4);">
                            {{ $festivity->province }}
                        </span>
                    @endif
                    @if($isActive)
                        <span class="badge compact-badge fw-semibold position-absolute top-0 start-0 m-3"
                              style="background-color: #198754; color: #FFFFFF; font-size: 0.78rem; padding: 0.35rem 0.9rem; border-radius: 999px;">
                            Activa ahora
                        </span>
                    @endif
                </div>
            @else
                <div class="d-flex align-items-center justify-content-center" style="height: 220px; background: linear-gradient(135deg, #FEB101 0%, #F59E0B 100%);">
                    <i class="bi bi-calendar-event text-white" style="font-size: 4rem; opacity: 0.8;"></i>
                </div>
            @endif
        </a>
        
        <div class="card-body" style="padding: 1.1rem; display: flex; flex-direction: column;">
            <a href="{{ route('festivities.show', $festivity) }}" class="text-decoration-none">
                <h5 class="card-title mb-2" style="font-size: 1.1rem; font-weight: 700; color: #1F2937; line-height: 1.3; margin-bottom: 0.75rem;">
                    {{ $festivity->name }}
                </h5>
            </a>
            
            <div class="mb-3" style="display: flex; flex-direction: column; gap: 0.5rem;">
                <p class="mb-0" style="color: #6B7280; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="bi bi-geo-alt-fill" style="color: #1FA4A9; font-size: 0.95rem;"></i>
                    <span>{{ $festivity->locality->name ?? 'Sin localidad' }}</span>
                </p>
                <p class="mb-0" style="color: #6B7280; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="bi bi-calendar3" style="color: #FEB101; font-size: 0.95rem;"></i>
                    <span>
                        {{ $festivity->start_date->format('d M Y') }}
                        @if($festivity->end_date)
                            - {{ $festivity->end_date->format('d M Y') }}
                        @endif
                    </span>
                </p>
            </div>
            
            @if($festivity->description)
                <p class="card-text mb-3" style="color: #6B7280; font-size: 0.9rem; line-height: 1.55; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                    {{ Str::limit($festivity->description, 140) }}
                </p>
            @endif
            
            <div class="mt-auto d-flex align-items-center justify-content-between" style="padding-top: 0.85rem; border-top: 1px solid #F3F4F6;">
                <a href="{{ route('festivities.show', $festivity) }}" class="text-decoration-none" style="color: #FEB101; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em;">
                    Ver más <i class="bi bi-arrow-right ms-1"></i>
                </a>
                <span class="badge" style="background: linear-gradient(135deg, #FEB101 0%, #F59E0B 100%); color: #FFFFFF; font-weight: 600; font-size: 0.75rem; padding: 0.3rem 0.6rem; border-radius: 6px; box-shadow: 0 2px 6px rgba(254, 177, 1, 0.3);">
                    <i class="bi bi-heart-fill me-1" style="font-size: 0.7rem;"></i>{{ $votesCount }} {{ $votesCount === 1 ? 'Voto' : 'Votos' }}
                </span>
            </div>
            
            @auth
                @if(auth()->user()->isAdmin())
                    <div class="mt-3 pt-3" style="border-top: 1px solid #F3F4F6;">
                        <div class="d-flex gap-2">
                            <a href="{{ route('festivities.edit', $festivity) }}" 
                               class="btn btn-sm btn-outline-secondary flex-fill"
                               title="Editar"
                               style="border-radius: 8px;">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                            <form method="POST" 
                                  action="{{ route('festivities.destroy', $festivity) }}" 
                                  class="d-inline flex-fill"
                                  onsubmit="return confirm('¿Eliminar esta festividad?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-sm btn-outline-danger w-100"
                                        title="Eliminar"
                                        style="border-radius: 8px;">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            @endauth
        </div>
    </div>
    
    <style>
        .festivity-card-modern:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.15);
        }
        
        .festivity-card-modern:hover img {
            transform: scale(1.1);
        }
    </style>
</div>

