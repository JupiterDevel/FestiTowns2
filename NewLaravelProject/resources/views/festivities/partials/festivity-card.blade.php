<div class="card h-100 text-decoration-none" style="border-radius: 16px; color: inherit; position: relative;">
    @if($festivity->photos && count($festivity->photos) > 0)
        <div style="position: relative; height: 220px; overflow: hidden;">
            <img src="{{ $festivity->photos[0] }}" 
                 class="w-100 h-100" 
                 alt="{{ $festivity->name }}" 
                 style="object-fit: cover; transition: transform 0.4s ease;">
            <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 80px; background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);"></div>
            @if($festivity->province)
                <span class="badge position-absolute top-0 end-0 m-3" style="background: linear-gradient(135deg, #FEB101 0%, #F59E0B 100%); color: white; font-weight: 600; padding: 0.5rem 0.75rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(254, 177, 1, 0.4);">
                    {{ $festivity->province }}
                </span>
            @endif
        </div>
    @else
        <div class="d-flex align-items-center justify-content-center" style="height: 220px; background: linear-gradient(135deg, #FEB101 0%, #F59E0B 100%);">
            <i class="bi bi-calendar-event text-white" style="font-size: 4rem; opacity: 0.8;"></i>
        </div>
    @endif
    
    <div class="card-body d-flex flex-column" style="padding: 1.1rem;">
        <h5 class="card-title fw-bold mb-2" style="font-size: 1.1rem; color: #1F2937; line-height: 1.3;">{{ $festivity->name }}</h5>
        <p class="mb-2" style="color: #6B7280; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem;">
            <i class="bi bi-geo-alt-fill" style="color: #1FA4A9;"></i>
            <span>{{ $festivity->locality->name ?? 'Sin localidad' }}</span>
        </p>
        
        @if($festivity->description)
            <p class="card-text mb-3" style="color: #6B7280; font-size: 0.9rem; line-height: 1.55; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">{{ Str::limit($festivity->description, 120) }}</p>
        @endif
        
        <div class="mt-auto d-flex align-items-center justify-content-between" style="padding-top: 0.85rem; border-top: 1px solid #F3F4F6;">
            <div class="d-flex align-items-center" style="gap: 0.4rem;">
                <i class="bi bi-heart-fill" style="color: #F59E0B; font-size: 1rem;"></i>
                <span class="fw-bold" style="color: #1F2937; font-size: 1rem;">{{ $festivity->votes_count }}</span>
                <span style="color: #6B7280; font-size: 0.8rem;">{{ Str::plural('voto', $festivity->votes_count) }}</span>
            </div>
        </div>
        
        {{-- Stretched link to make entire card clickable without big button --}}
        <a href="{{ route('festivities.show', $festivity) }}" class="stretched-link"></a>
    </div>
    
    <style>
        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.15);
        }
        
        .card:hover img {
            transform: scale(1.1);
        }
    </style>
</div>

