@props(['clientId', 'slotId' => null, 'type' => 'display', 'style' => 'display:block', 'format' => 'auto', 'testMode' => false])

@if($clientId)
    @php
        $uniqueId = 'adsense-' . uniqid();
    @endphp
    <div class="adsense-container" id="container-{{ $uniqueId }}" style="min-height: 100px; width: 100%; display: none; position: relative; box-sizing: border-box;">
        <ins class="adsbygoogle"
             id="ad-{{ $uniqueId }}"
             style="display:block; width: 100%; min-width: 300px; box-sizing: border-box;"
             data-ad-client="{{ $clientId }}"
             @if(isset($slotId) && $slotId)data-ad-slot="{{ $slotId }}"@endif
             data-ad-format="{{ $format }}"
             @if($testMode)data-adtest="on"@endif
             data-full-width-responsive="true"></ins>
    </div>
    <script>
        // Initialize AdSense only when container has valid dimensions
        (function() {
            var adId = 'ad-{{ $uniqueId }}';
            var containerId = 'container-{{ $uniqueId }}';
            var initialized = false;
            var resizeObserver = null;
            var timeoutId = null;
            
            function getElementWidth(element) {
                if (!element) return 0;
                
                // Force layout calculation
                var rect = element.getBoundingClientRect();
                if (rect.width > 0) return rect.width;
                
                // Try computed style
                var style = window.getComputedStyle(element);
                var width = parseInt(style.width);
                if (width > 0 && !isNaN(width)) return width;
                
                // Try offsetWidth (includes padding and border)
                var offsetWidth = element.offsetWidth;
                if (offsetWidth > 0) return offsetWidth;
                
                // Try clientWidth (excludes border)
                var clientWidth = element.clientWidth;
                if (clientWidth > 0) return clientWidth;
                
                // Last resort: try to get from parent
                var parent = element.parentElement;
                if (parent) {
                    var parentWidth = getElementWidth(parent);
                    if (parentWidth > 0) {
                        // Set explicit width and re-check
                        element.style.width = parentWidth + 'px';
                        element.offsetHeight; // Force reflow
                        return getElementWidth(element);
                    }
                }
                
                return 0;
            }
            
            function initAdSense() {
                if (initialized) return;
                
                var container = document.getElementById(containerId);
                var adElement = document.getElementById(adId);
                
                if (!container || !adElement) {
                    return;
                }
                
                // Check if AdSense is loaded
                if (typeof adsbygoogle === 'undefined') {
                    return;
                }
                
                // Get container width - try multiple methods
                var containerWidth = getElementWidth(container);
                
                // If container has no width, get from parent
                if (containerWidth === 0) {
                    var parent = container.parentElement;
                    var maxDepth = 5;
                    var depth = 0;
                    while (parent && containerWidth === 0 && depth < maxDepth) {
                        containerWidth = getElementWidth(parent);
                        if (containerWidth > 0) {
                            // Set explicit width on container
                            container.style.width = containerWidth + 'px';
                            container.style.maxWidth = containerWidth + 'px';
                            container.offsetHeight; // Force reflow
                            break;
                        }
                        parent = parent.parentElement;
                        depth++;
                    }
                }
                
                // Re-check after forcing width
                if (containerWidth > 0) {
                    containerWidth = getElementWidth(container);
                }
                
                // Only initialize if we have valid dimensions (at least 300px)
                if (containerWidth >= 300) {
                    try {
                        // Set explicit dimensions on container first
                        container.style.width = containerWidth + 'px';
                        container.style.minWidth = containerWidth + 'px';
                        container.style.maxWidth = containerWidth + 'px';
                        container.style.display = 'block';
                        
                        // Force reflow
                        container.offsetHeight;
                        
                        // Set explicit dimensions on ad element
                        adElement.style.width = containerWidth + 'px';
                        adElement.style.minWidth = containerWidth + 'px';
                        adElement.style.maxWidth = containerWidth + 'px';
                        adElement.style.display = 'block';
                        
                        // Force multiple reflows to ensure layout is stable
                        adElement.offsetHeight;
                        container.offsetHeight;
                        adElement.getBoundingClientRect();
                        
                        // Wait for layout to stabilize - longer delay for Firefox
                        setTimeout(function() {
                            if (!initialized) {
                                try {
                                    // Triple-check dimensions before initializing
                                    var finalContainerWidth = getElementWidth(container);
                                    var finalAdWidth = getElementWidth(adElement);
                                    
                                    // Use the larger of the two
                                    var finalWidth = Math.max(finalContainerWidth, finalAdWidth);
                                    
                                    if (finalWidth >= 300) {
                                        // One more reflow before pushing
                                        adElement.offsetHeight;
                                        
                                        // Make container visible before initializing
                                        container.style.display = 'block';
                                        container.style.visibility = 'visible';
                                        
                                        (adsbygoogle = window.adsbygoogle || []).push({});
                                        initialized = true;
                                        console.log('AdSense initialized. Container:', finalContainerWidth, 'px, Ad:', finalAdWidth, 'px');
                                        
                                        // Clean up
                                        if (resizeObserver) {
                                            resizeObserver.disconnect();
                                            resizeObserver = null;
                                        }
                                        if (timeoutId) {
                                            clearInterval(timeoutId);
                                            timeoutId = null;
                                        }
                                        
                                        // Monitor if ad loads - if not, hide container after timeout
                                        setTimeout(function() {
                                            var adContent = adElement.querySelector('iframe');
                                            var hasContent = adElement.innerHTML.trim().length > 0;
                                            var hasHeight = adElement.offsetHeight >= 50;
                                            
                                            console.log('AdSense check - Has iframe:', !!adContent, 'Has content:', hasContent, 'Has height:', hasHeight, 'Height:', adElement.offsetHeight);
                                            
                                            if (!adContent && (!hasContent || !hasHeight)) {
                                                console.warn('AdSense: Ad did not load after 5 seconds, hiding container');
                                                container.style.display = 'none';
                                                container.style.visibility = 'hidden';
                                            }
                                        }, 5000);
                                    } else {
                                        console.warn('AdSense: Width too small after setup. Container:', finalContainerWidth, 'px, Ad:', finalAdWidth, 'px');
                                        // Reset initialized flag to try again
                                        initialized = false;
                                    }
                                } catch (e) {
                                    console.error('AdSense push error:', e);
                                }
                            }
                        }, 800); // Longer delay for Firefox
                    } catch (e) {
                        console.error('AdSense initialization error:', e);
                    }
                } else {
                    console.debug('AdSense: Container width too small:', containerWidth, 'px');
                }
            }
            
            function setupObserver() {
                var container = document.getElementById(containerId);
                if (!container) {
                    setTimeout(setupObserver, 100);
                    return;
                }
                
                // Use ResizeObserver to detect when container has dimensions
                if (typeof ResizeObserver !== 'undefined') {
                    resizeObserver = new ResizeObserver(function(entries) {
                        for (var i = 0; i < entries.length; i++) {
                            var entry = entries[i];
                            var width = entry.contentRect.width;
                            if (width >= 300 && !initialized) {
                                setTimeout(initAdSense, 200);
                            }
                        }
                    });
                    
                    resizeObserver.observe(container);
                }
                
                // Fallback: periodic check
                var checkCount = 0;
                var maxChecks = 200;
                timeoutId = setInterval(function() {
                    checkCount++;
                    if (!initialized && checkCount < maxChecks) {
                        initAdSense();
                    } else if (checkCount >= maxChecks) {
                        clearInterval(timeoutId);
                        timeoutId = null;
                    }
                }, 500);
            }
            
            // Start initialization
            function start() {
                // Wait for page to be fully loaded and layout to settle
                setTimeout(function() {
                    requestAnimationFrame(function() {
                        requestAnimationFrame(function() {
                            setupObserver();
                            // Try to initialize after a delay
                            setTimeout(initAdSense, 2000);
                        });
                    });
                }, 1000);
            }
            
            if (document.readyState === 'complete') {
                start();
            } else if (document.readyState === 'interactive') {
                document.addEventListener('DOMContentLoaded', start);
                window.addEventListener('load', start);
            } else {
                document.addEventListener('DOMContentLoaded', start);
                window.addEventListener('load', start);
            }
        })();
    </script>
@endif

