@props(['latitude', 'longitude', 'title' => null, 'height' => '400px'])

@php
    $mapId = 'google-map-' . uniqid();
@endphp

@if($latitude && $longitude)
    <div class="google-map-container mb-4">
        <div id="{{ $mapId }}" style="width: 100%; height: {{ $height }}; border-radius: 8px; overflow: hidden; background-color: #f0f0f0;"></div>
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
                        mapTypeControl: true,
                        streetViewControl: true,
                        fullscreenControl: true,
                    });
                    
                    const marker = new google.maps.Marker({
                        position: location,
                        map: map,
                        title: title,
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

