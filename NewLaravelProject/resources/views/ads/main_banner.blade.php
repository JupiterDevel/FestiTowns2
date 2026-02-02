@php
    $ad = $ad ?? null;
    $isPremium = $ad && $ad->premium;
    $isAdSense = $ad && $ad->is_adsense;
    $imageUrl = ($ad && $ad->image && !$isAdSense) ? $ad->image_url : null;
    $adminCanManageAds = auth()->check() && auth()->user()->isAdmin();
    $createParams = array_filter($newAdParams ?? []);
@endphp

<section class="mb-4 main-banner-container" id="mainBannerSection">
    <div class="card border-0 shadow-sm overflow-hidden position-relative">
        @if($adminCanManageAds)
            <div class="position-absolute top-0 start-0 end-0 m-2 d-flex justify-content-between align-items-center" style="z-index: 10;">
                @if($isPremium && $ad?->id && $ad->name)
                    <a href="{{ route('admin.panel', ['tab' => 'advertisements']) }}" 
                       class="ad-name-link text-white text-decoration-none fw-semibold" 
                       style="text-shadow: 0 1px 3px rgba(0,0,0,0.5); background: rgba(0,0,0,0.4); padding: 0.25rem 0.75rem; border-radius: 6px; backdrop-filter: blur(5px);"
                       onclick="event.stopPropagation();"
                       title="Ver en panel de administración">
                        {{ $ad->name }}
                    </a>
                @else
                    <span></span>
                @endif
                <div class="d-flex gap-2">
                    @if($isPremium && $ad?->id)
                        <a href="{{ route('advertisements.edit', $ad) }}" class="btn btn-light btn-sm" title="Editar anuncio" onclick="event.stopPropagation();">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <form method="POST" action="{{ route('advertisements.destroy', $ad) }}" onsubmit="return confirm('¿Eliminar este anuncio?')" onclick="event.stopPropagation();">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-light btn-sm" title="Eliminar anuncio">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('advertisements.create', $createParams) }}" class="btn btn-primary btn-sm" title="Crear anuncio" onclick="event.stopPropagation();">
                            <i class="bi bi-plus-circle me-1"></i>Nuevo
                        </a>
                    @endif
                </div>
            </div>
        @endif
        <!-- Close Button with Countdown -->
        <button type="button" 
                class="btn btn-close-banner position-absolute" 
                id="closeBannerBtn"
                onclick="closeMainBanner()"
                style="top: 10px; right: 10px; z-index: 11; width: 40px; height: 40px; border-radius: 50%; background: rgba(0, 0, 0, 0.6); border: 2px solid rgba(255, 255, 255, 0.8); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 16px; cursor: not-allowed; transition: all 0.3s ease; backdrop-filter: blur(5px); pointer-events: auto;"
                title="Espera 5 segundos...">
            <span id="bannerCountdown">5</span>
            <span id="bannerCloseIcon" style="display: none; font-size: 20px;">&times;</span>
        </button>
        @php
            $hasUrl = $ad && !empty($ad->url);
            $isRealAd = $ad && ($ad->name || $ad->url || $ad->image || ($ad->is_adsense && $ad->adsense_client_id && $ad->adsense_slot_id));
        @endphp
        @if($hasUrl)
            <a href="{{ $ad->url }}" 
               class="card-body p-0 text-decoration-none d-block"
               target="_blank"
               rel="noopener noreferrer"
               style="cursor: pointer;">
        @else
            <div class="card-body p-0">
        @endif
            @if($imageUrl)
                <img src="{{ $imageUrl }}"
                     alt="{{ $ad->name ?? 'Publicidad principal' }}"
                     class="w-100"
                     style="max-height: 320px; object-fit: cover;">
            @elseif($isAdSense && $ad && $ad->adsense_client_id && $ad->adsense_slot_id)
                <div class="p-0">
                    <x-adsense-ad 
                        :clientId="$ad->adsense_client_id" 
                        :slotId="$ad->adsense_slot_id"
                        type="{{ $ad->adsense_type ?? 'display' }}"
                        style="display:block; min-height: 180px;"
                        format="auto"
                    />
                </div>
            @elseif(!$isRealAd && config('services.google.adsense_enabled') && config('services.google.adsense_client_id'))
                {{-- AdSense usuario real (variables en .env) --}}
                <div class="p-0" style="min-height: 280px; width: 100%;">
                    <x-adsense-ad 
                        :clientId="config('services.google.adsense_client_id')" 
                        :slotId="config('services.google.adsense_default_slot')"
                        type="display"
                        style="display:block; min-height: 280px; width: 100%;"
                        format="auto"
                        :testMode="config('services.google.adsense_test_mode')"
                    />
                </div>
            @else
                <div class="p-4 text-center bg-light border border-2 border-secondary border-opacity-25" style="min-height: 180px; border-style: dashed;">
                    <div class="h5 text-uppercase text-muted mb-2">Google Ads</div>
                    <p class="mb-0 text-muted">
                        Espacio reservado para el banner principal (800x400).<br>
                        @if($adminCanManageAds)
                            <a href="{{ route('advertisements.create', $createParams) }}" class="btn btn-sm btn-primary mt-2" onclick="event.stopPropagation();">
                                <i class="bi bi-plus-circle me-1"></i>Crear anuncio
                            </a>
                        @else
                            Próximamente se integrará el script de Google Ads.
                        @endif
                    </p>
                </div>
            @endif
        @if($hasUrl)
            </a>
        @else
            </div>
        @endif
    </div>
</section>

<style>
    .btn-close-banner:hover {
        background: rgba(0, 0, 0, 0.8) !important;
        transform: scale(1.1);
    }
    
    .btn-close-banner:active {
        transform: scale(0.95);
    }
    
    .main-banner-container.hidden {
        display: none !important;
    }
</style>

<script>
    (function() {
        let countdown = 5;
        const countdownElement = document.getElementById('bannerCountdown');
        const closeIcon = document.getElementById('bannerCloseIcon');
        const closeBtn = document.getElementById('closeBannerBtn');

        // Disable button during countdown
        if (closeBtn) {
            closeBtn.disabled = true;
        }

        // Start countdown
        const countdownInterval = setInterval(function() {
            countdown--;
            if (countdownElement) {
                countdownElement.textContent = countdown;
            }

            if (countdown <= 0) {
                clearInterval(countdownInterval);
                // Show close icon and enable button
                if (countdownElement) {
                    countdownElement.style.display = 'none';
                }
                if (closeIcon) {
                    closeIcon.style.display = 'inline';
                }
                if (closeBtn) {
                    closeBtn.disabled = false;
                    closeBtn.style.cursor = 'pointer';
                    closeBtn.title = 'Cerrar publicidad';
                }
            }
        }, 1000);

        // Make closeBanner function globally available
        window.closeMainBanner = function() {
            if (countdown > 0) {
                return; // Don't close if countdown hasn't finished
            }

            const bannerSection = document.getElementById('mainBannerSection');
            if (bannerSection) {
                bannerSection.style.transition = 'opacity 0.3s ease, height 0.3s ease';
                bannerSection.style.opacity = '0';
                bannerSection.style.height = bannerSection.offsetHeight + 'px';
                
                setTimeout(function() {
                    bannerSection.classList.add('hidden');
                }, 300);
            }
        };
    })();
</script>

