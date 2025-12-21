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

<footer class="border-top mt-4 py-3 footer-enhanced" style="background-color: #FEB101;">
    <div class="container">
        <!-- Información de Contacto y Redes Sociales -->
        <div class="row align-items-center mb-2">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <span class="brand-text d-block d-md-inline" style="color: #E5483B; font-size: 1.35rem;">Elalmadelafiesta</span>
                <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start gap-3">
                    @if($contactEmail)
                        <a href="mailto:{{ $contactEmail }}" class="text-decoration-none" style="color: #F9FAFB; font-weight: 500; transition: color 0.2s ease;" onmouseover="this.style.color='#FFFFFF'" onmouseout="this.style.color='#F9FAFB'">
                            <i class="bi bi-envelope me-2" style="color: #FFFFFF;"></i>{{ $contactEmail }}
                        </a>
                    @endif
                    @if($contactPhone)
                        @if($contactEmail)
                            <span style="color: #FBBF24; display: none;" class="d-md-inline">|</span>
                        @endif
                        <a href="tel:{{ $contactPhone }}" class="text-decoration-none" style="color: #F9FAFB; font-weight: 500; transition: color 0.2s ease;" onmouseover="this.style.color='#FFFFFF'" onmouseout="this.style.color='#F9FAFB'">
                            <i class="bi bi-telephone me-2" style="color: #FFFFFF;"></i>{{ $contactPhone }}
                        </a>
                    @endif
                </div>
            </div>
            
            <div class="col-md-6 text-center text-md-end">
                <div class="d-flex justify-content-center justify-content-md-end align-items-center gap-2">
                    @if($facebookUrl)
                        <a href="{{ $facebookUrl }}" target="_blank" rel="noopener noreferrer" class="text-decoration-none" title="Facebook" style="color: #1F2937; transition: all 0.2s ease; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; border-radius: 999px; background: #F9FAFB;" onmouseover="this.style.background='#1FA4A9'; this.style.color='white'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='#F9FAFB'; this.style.color='#1F2937'; this.style.transform='translateY(0)'">
                            <i class="bi bi-facebook" style="font-size: 1.5rem;"></i>
                        </a>
                    @endif
                    @if($twitterUrl)
                        <a href="{{ $twitterUrl }}" target="_blank" rel="noopener noreferrer" class="text-decoration-none" title="Twitter" style="color: #1F2937; transition: all 0.2s ease; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; border-radius: 999px; background: #F9FAFB;" onmouseover="this.style.background='#1FA4A9'; this.style.color='white'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='#F9FAFB'; this.style.color='#1F2937'; this.style.transform='translateY(0)'">
                            <i class="bi bi-twitter" style="font-size: 1.5rem;"></i>
                        </a>
                    @endif
                    @if($instagramUrl)
                        <a href="{{ $instagramUrl }}" target="_blank" rel="noopener noreferrer" class="text-decoration-none" title="Instagram" style="color: #1F2937; transition: all 0.2s ease; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; border-radius: 999px; background: #F9FAFB;" onmouseover="this.style.background='#1FA4A9'; this.style.color='white'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='#F9FAFB'; this.style.color='#1F2937'; this.style.transform='translateY(0)'">
                            <i class="bi bi-instagram" style="font-size: 1.5rem;"></i>
                        </a>
                    @endif
                    @if($youtubeUrl)
                        <a href="{{ $youtubeUrl }}" target="_blank" rel="noopener noreferrer" class="text-decoration-none" title="YouTube" style="color: #1F2937; transition: all 0.2s ease; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; border-radius: 999px; background: #F9FAFB;" onmouseover="this.style.background='#1FA4A9'; this.style.color='white'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='#F9FAFB'; this.style.color='#1F2937'; this.style.transform='translateY(0)'">
                            <i class="bi bi-youtube" style="font-size: 1.5rem;"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
       
        <!-- Enlaces Legales -->
        <div class="row mt-2">
            <div class="col-12 text-center text-md-end">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item">
                        <a href="{{ route('legal.index') }}#terms" class="text-decoration-none" style="color: #F9FAFB; font-weight: 500; transition: color 0.2s ease;" onmouseover="this.style.color='#FFFFFF'" onmouseout="this.style.color='#F9FAFB'">
                            <i class="bi bi-file-earmark-text me-1"></i>Términos
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <span style="color: #E5E7EB;">|</span>
                    </li>
                    <li class="list-inline-item">
                        <a href="{{ route('legal.index') }}#cookies" class="text-decoration-none" style="color: #F9FAFB; font-weight: 500; transition: color 0.2s ease;" onmouseover="this.style.color='#FFFFFF'" onmouseout="this.style.color='#F9FAFB'">
                            <i class="bi bi-cookie me-1"></i>Cookies
                        </a>
                    </li>
                    @if($displayEmail)
                        <li class="list-inline-item">
                            <span style="color: #E5E7EB;">|</span>
                        </li>
                        <li class="list-inline-item">
                            <a href="mailto:{{ $displayEmail }}" class="text-decoration-none" style="color: #F9FAFB; font-weight: 500; transition: color 0.2s ease;" onmouseover="this.style.color='#FFFFFF'" onmouseout="this.style.color='#F9FAFB'">
                                <i class="bi bi-envelope me-1"></i>Contacto
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</footer>

