@php
    $ads = collect($ads ?? [])->take(2);
    $orientation = $orientation ?? 'sidebar';
    $adminCanManageAds = auth()->check() && auth()->user()->isAdmin();
    $createParams = array_filter($newAdParams ?? []);
@endphp

<div class="d-flex flex-column gap-3 {{ $orientation === 'inline' ? '' : 'position-sticky' }}" style="{{ $orientation === 'inline' ? '' : 'top: 1rem;' }}">
    @foreach($ads as $index => $ad)
        @php
            $isPremium = $ad && $ad->premium;
            $isAdSense = $ad && $ad->is_adsense;
            $imageUrl = $isPremium && $ad->image && !$isAdSense
                ? (\Illuminate\Support\Str::startsWith($ad->image, ['http://', 'https://']) ? $ad->image : asset($ad->image))
                : null;
        @endphp
        <div class="card shadow-sm border-0 h-100 position-relative">
            @if($adminCanManageAds)
                <div class="position-absolute top-0 end-0 m-2 d-flex gap-2">
                    @if($isPremium && $ad?->id)
                        <a href="{{ route('advertisements.edit', $ad) }}" class="btn btn-light btn-sm" title="Editar anuncio">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <form method="POST" action="{{ route('advertisements.destroy', $ad) }}" onsubmit="return confirm('¿Eliminar este anuncio?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-light btn-sm" title="Eliminar anuncio">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('advertisements.create', $createParams) }}" class="btn btn-primary btn-sm" title="Crear anuncio">
                            <i class="bi bi-plus-circle me-1"></i>Nuevo
                        </a>
                    @endif
                </div>
            @endif
            @if($isPremium && $imageUrl)
                <a href="{{ $ad->url ?? '#' }}"
                   class="text-decoration-none"
                   target="{{ $ad->url ? '_blank' : '_self' }}"
                   rel="noopener">
                    <img src="{{ $imageUrl }}"
                         alt="{{ $ad->name ?? 'Publicidad secundaria' }}"
                         class="w-100"
                         style="height: 180px; object-fit: cover;">
                </a>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="badge bg-primary text-uppercase small">Premium</span>
                        <span class="text-muted small">{{ ucfirst($ad->priority) }}</span>
                    </div>
                    <p class="mb-0 fw-semibold text-dark">{{ $ad->name }}</p>
                </div>
            @elseif($isAdSense && $ad->adsense_client_id && $ad->adsense_slot_id)
                <div class="card-body p-0">
                    <x-adsense-ad 
                        :clientId="$ad->adsense_client_id" 
                        :slotId="$ad->adsense_slot_id"
                        type="{{ $ad->adsense_type ?? 'display' }}"
                        style="display:block; min-height: 180px;"
                        format="auto"
                    />
                </div>
            @else
                <div class="card-body text-center bg-light border border-2 border-secondary border-opacity-25" style="border-style: dashed;">
                    <div class="text-muted small text-uppercase mb-2">Google Ads</div>
                    <p class="mb-1 fw-semibold">Bloque publicitario {{ $index + 1 }}</p>
                    <p class="mb-0 text-muted small">
                        Placeholder responsive para anuncios secundarios.<br>
                        @if($adminCanManageAds)
                            <a href="{{ route('advertisements.create', $createParams) }}" class="btn btn-sm btn-primary mt-2">
                                <i class="bi bi-plus-circle me-1"></i>Crear anuncio
                            </a>
                        @else
                            Se insertará código de Google Ads.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    @endforeach
</div>

