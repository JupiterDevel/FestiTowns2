@props(['latitude', 'longitude', 'title' => null, 'height' => '400px'])

@php
    $mapId = 'google-map-' . uniqid();
@endphp

@if($latitude && $longitude)
    <div class="google-map-container mb-4">
        <div id="{{ $mapId }}" style="width: 100%; height: {{ $height }}; border-radius: 8px; overflow: hidden; background-color: #f0f0f0;"></div>
        <style>
            /* Hide Google Maps attribution links and controls */
            #{{ $mapId }} a[href*="terms"],
            #{{ $mapId }} a[href*="reportaproblem"],
            #{{ $mapId }} a[href*="mapdata"],
            #{{ $mapId }} a[href*="keyboard"],
            #{{ $mapId }} button[title*="Pantalla completa"],
            #{{ $mapId }} button[title*="Fullscreen"],
            #{{ $mapId }} .gm-style-mtc,
            #{{ $mapId }} [role="button"][aria-label*="Map"],
            #{{ $mapId }} [role="button"][aria-label*="Satellite"],
            #{{ $mapId }} [role="button"][aria-label*="Terrain"],
            #{{ $mapId }} a[href*="help"],
            #{{ $mapId }} .gm-style-cc,
            #{{ $mapId }} .gm-style-cc a {
                display: none !important;
                visibility: hidden !important;
                opacity: 0 !important;
                height: 0 !important;
                width: 0 !important;
                overflow: hidden !important;
            }
            
            /* Hide all links in the map attribution area */
            #{{ $mapId }} .gm-style-cc {
                display: none !important;
            }
        </style>
    </div>

    @push('scripts')
    <script>
        (function() {
            const mapId = '{{ $mapId }}';
            const latitude = {{ $latitude }};
            const longitude = {{ $longitude }};
            const title = @json($title ?? 'Ubicación');
            const mapKey = '{{ config('services.google.maps_key') }}';
            
            function initMapInstance() {
                const mapElement = document.getElementById(mapId);
                if (!mapElement) {
                    console.error('Map element not found:', mapId);
                    return;
                }
                
                const location = { lat: latitude, lng: longitude };
                
                try {
                    const map = new google.maps.Map(mapElement, {
                        zoom: 15,
                        center: location,
                        mapTypeControl: false,
                        streetViewControl: false,
                        fullscreenControl: false,
                        zoomControl: true,
                        disableDefaultUI: false,
                        gestureHandling: 'cooperative',
                        keyboardShortcuts: false,
                    });
                    
                    const marker = new google.maps.Marker({
                        position: location,
                        map: map,
                        title: title,
                    });
                    
                    // Function to hide all unwanted links and controls
                    function hideMapLinks() {
                        // Hide all links in the map
                        const allLinks = mapElement.querySelectorAll('a');
                        allLinks.forEach(link => {
                            const href = link.getAttribute('href') || '';
                            const text = link.textContent || '';
                            
                            // Hide links containing these keywords
                            if (href.includes('terms') || 
                                href.includes('reportaproblem') || 
                                href.includes('mapdata') || 
                                href.includes('keyboard') ||
                                href.includes('help') ||
                                text.includes('Datos del mapa') ||
                                text.includes('Map data') ||
                                text.includes('Notificar') ||
                                text.includes('Report a problem')) {
                                link.style.display = 'none';
                                link.style.visibility = 'hidden';
                                link.style.opacity = '0';
                                link.style.height = '0';
                                link.style.width = '0';
                                link.style.overflow = 'hidden';
                            }
                        });
                        
                        // Hide attribution container
                        const attributionContainers = mapElement.querySelectorAll('.gm-style-cc');
                        attributionContainers.forEach(container => {
                            container.style.display = 'none';
                            container.style.visibility = 'hidden';
                        });
                        
                        // Hide fullscreen button
                        const fullscreenButtons = mapElement.querySelectorAll('button[title*="Pantalla completa"], button[title*="Fullscreen"]');
                        fullscreenButtons.forEach(btn => {
                            btn.style.display = 'none';
                            btn.style.visibility = 'hidden';
                        });
                        
                        // Hide map/satellite toggle
                        const mapTypeControls = mapElement.querySelectorAll('.gm-style-mtc');
                        mapTypeControls.forEach(control => {
                            control.style.display = 'none';
                            control.style.visibility = 'hidden';
                        });
                    }
                    
                    // Hide links immediately and repeatedly
                    hideMapLinks();
                    setTimeout(hideMapLinks, 500);
                    setTimeout(hideMapLinks, 1000);
                    setTimeout(hideMapLinks, 2000);
                    
                    // Use MutationObserver to hide links as they are added
                    const observer = new MutationObserver(function(mutations) {
                        hideMapLinks();
                    });
                    
                    observer.observe(mapElement, {
                        childList: true,
                        subtree: true
                    });
                } catch (error) {
                    console.error('Error initializing Google Maps:', error);
                }
            }
            
            // Check if Google Maps is already loaded
            if (typeof google !== 'undefined' && google.maps) {
                initMapInstance();
            } else {
                // Load Google Maps script
                if (!window.googleMapsScriptLoaded) {
                    window.googleMapsScriptLoaded = true;
                    window.googleMapsCallbacks = [];
                    
                    const script = document.createElement('script');
                    script.src = `https://maps.googleapis.com/maps/api/js?key=${mapKey}&callback=initAllGoogleMaps`;
                    script.async = true;
                    script.defer = true;
                    script.onerror = function() {
                        console.error('Failed to load Google Maps script');
                        document.getElementById(mapId).innerHTML = '<div style="padding: 20px; text-align: center; color: #666;">Error loading Google Maps. Please check your API key.</div>';
                    };
                    document.head.appendChild(script);
                    
                    window.initAllGoogleMaps = function() {
                        window.googleMapsScriptLoaded = true;
                        if (window.googleMapsCallbacks) {
                            window.googleMapsCallbacks.forEach(callback => callback());
                        }
                    };
                }
                
                // Add this map's init function to callbacks
                if (!window.googleMapsCallbacks) {
                    window.googleMapsCallbacks = [];
                }
                window.googleMapsCallbacks.push(initMapInstance);
                
                // Fallback: check periodically if script loaded
                let checkCount = 0;
                const checkInterval = setInterval(function() {
                    checkCount++;
                    if (typeof google !== 'undefined' && google.maps) {
                        clearInterval(checkInterval);
                        initMapInstance();
                    } else if (checkCount > 50) { // 5 seconds timeout
                        clearInterval(checkInterval);
                        console.error('Google Maps script failed to load');
                        const mapElement = document.getElementById(mapId);
                        if (mapElement) {
                            mapElement.innerHTML = '<div style="padding: 20px; text-align: center; color: #666;">Error loading Google Maps. Please check your API key in .env file.</div>';
                        }
                    }
                }, 100);
            }
        })();
    </script>
    @endpush
@endif

