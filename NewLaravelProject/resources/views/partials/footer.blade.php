@php
    $contactEmail = \Illuminate\Support\Facades\Cache::get('contact_email', '');
    $contactPhone = \Illuminate\Support\Facades\Cache::get('contact_phone', '');
    $facebookUrl = \Illuminate\Support\Facades\Cache::get('social_facebook', '');
    $twitterUrl = \Illuminate\Support\Facades\Cache::get('social_twitter', '');
    $instagramUrl = \Illuminate\Support\Facades\Cache::get('social_instagram', '');
    $youtubeUrl = \Illuminate\Support\Facades\Cache::get('social_youtube', '');
    
    // Si no hay email configurado, usar el por defecto
    $displayEmail = $contactEmail ?: 'almadelasfiestas2000@gmail.com';
@endphp

<footer class="bg-light border-top mt-5 py-4">
    <div class="container">
        <!-- Información de Contacto y Redes Sociales -->
        <div class="row mb-3">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start gap-2">
                    @if($contactEmail)
                        <a href="mailto:{{ $contactEmail }}" class="text-decoration-none text-muted">
                            <i class="bi bi-envelope me-1"></i>{{ $contactEmail }}
                        </a>
                    @endif
                    @if($contactPhone)
                        @if($contactEmail)
                            <span class="text-muted d-none d-md-inline">|</span>
                        @endif
                        <a href="tel:{{ $contactPhone }}" class="text-decoration-none text-muted">
                            <i class="bi bi-telephone me-1"></i>{{ $contactPhone }}
                        </a>
                    @endif
                </div>
            </div>
            
            <div class="col-md-6 text-center text-md-end">
                <div class="d-flex justify-content-center justify-content-md-end align-items-center gap-2">
                    @if($facebookUrl)
                        <a href="{{ $facebookUrl }}" target="_blank" rel="noopener noreferrer" class="text-decoration-none text-primary" title="Facebook">
                            <i class="bi bi-facebook" style="font-size: 1.5rem;"></i>
                        </a>
                    @endif
                    @if($twitterUrl)
                        <a href="{{ $twitterUrl }}" target="_blank" rel="noopener noreferrer" class="text-decoration-none text-info" title="Twitter">
                            <i class="bi bi-twitter" style="font-size: 1.5rem;"></i>
                        </a>
                    @endif
                    @if($instagramUrl)
                        <a href="{{ $instagramUrl }}" target="_blank" rel="noopener noreferrer" class="text-decoration-none text-danger" title="Instagram">
                            <i class="bi bi-instagram" style="font-size: 1.5rem;"></i>
                        </a>
                    @endif
                    @if($youtubeUrl)
                        <a href="{{ $youtubeUrl }}" target="_blank" rel="noopener noreferrer" class="text-decoration-none text-danger" title="YouTube">
                            <i class="bi bi-youtube" style="font-size: 1.5rem;"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
        
        <hr class="my-3">
        
        <!-- Copyright y Enlaces Legales -->
        <div class="row">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <p class="mb-0 text-muted">
                    &copy; {{ date('Y') }} {{ config('app.name', 'El Alma de las Fiestas') }} — Todos los derechos reservados
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item">
                        <a href="{{ route('legal.index') }}#terms" class="text-decoration-none text-muted">
                            <i class="bi bi-file-earmark-text me-1"></i>Términos
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <span class="text-muted">|</span>
                    </li>
                    <li class="list-inline-item">
                        <a href="{{ route('legal.index') }}#cookies" class="text-decoration-none text-muted">
                            <i class="bi bi-cookie me-1"></i>Cookies
                        </a>
                    </li>
                    @if($displayEmail)
                        <li class="list-inline-item">
                            <span class="text-muted">|</span>
                        </li>
                        <li class="list-inline-item">
                            <a href="mailto:{{ $displayEmail }}" class="text-decoration-none text-muted">
                                <i class="bi bi-envelope me-1"></i>Contacto
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</footer>

