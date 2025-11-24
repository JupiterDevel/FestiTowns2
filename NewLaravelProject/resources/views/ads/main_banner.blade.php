@php
    $ad = $ad ?? null;
    $isPremium = $ad && $ad->premium;
    $isAdSense = $ad && $ad->is_adsense;
    $imageUrl = $isPremium && $ad->image && !$isAdSense
        ? (\Illuminate\Support\Str::startsWith($ad->image, ['http://', 'https://']) ? $ad->image : asset($ad->image))
        : null;
    $adminCanManageAds = auth()->check() && auth()->user()->isAdmin();
    $createParams = array_filter($newAdParams ?? []);
@endphp

<section class="mb-4">
    <div class="card border-0 shadow-sm overflow-hidden position-relative">
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
        <div class="card-body p-0">
            @if($isPremium && $imageUrl)
                <a href="{{ $ad->url ?? '#' }}"
                   class="d-block text-decoration-none"
                   target="{{ $ad->url ? '_blank' : '_self' }}"
                   rel="noopener">
                    <img src="{{ $imageUrl }}"
                         alt="{{ $ad->name ?? 'Publicidad principal' }}"
                         class="w-100"
                         style="max-height: 320px; object-fit: cover;">
                </a>
                @if($ad->name)
                    <div class="p-3 bg-light d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <span class="badge bg-warning text-dark me-2">Premium</span>
                            <strong>{{ $ad->name }}</strong>
                        </div>
                        @if($ad->url)
                            <span class="text-primary small">
                                <i class="bi bi-box-arrow-up-right me-1"></i>Patrocinado
                            </span>
                        @endif
                    </div>
                @endif
            @elseif($isAdSense && $ad->adsense_client_id && $ad->adsense_slot_id)
                <div class="p-0">
                    <x-adsense-ad 
                        :clientId="$ad->adsense_client_id" 
                        :slotId="$ad->adsense_slot_id"
                        type="{{ $ad->adsense_type ?? 'display' }}"
                        style="display:block; min-height: 180px;"
                        format="auto"
                    />
                </div>
            @else
                <div class="p-4 text-center bg-light border border-2 border-secondary border-opacity-25" style="min-height: 180px; border-style: dashed;">
                    <div class="h5 text-uppercase text-muted mb-2">Google Ads</div>
                    <p class="mb-0 text-muted">
                        Espacio reservado para el banner principal (800x400).<br>
                        @if($adminCanManageAds)
                            <a href="{{ route('advertisements.create', $createParams) }}" class="btn btn-sm btn-primary mt-2">
                                <i class="bi bi-plus-circle me-1"></i>Crear anuncio
                            </a>
                        @else
                            Próximamente se integrará el script de Google Ads.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</section>

