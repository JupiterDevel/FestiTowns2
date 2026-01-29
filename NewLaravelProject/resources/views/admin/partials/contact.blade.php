{{-- Session messages handled by toast system --}}

<div class="card">
    <div class="card-body">
        <h5 class="card-title mb-4">
            <i class="bi bi-envelope-fill me-2"></i>Información de Contacto
        </h5>
        
        <p class="text-muted mb-4">
            Gestiona la información de contacto y redes sociales que aparecerá en el footer de la página. 
            Los iconos de redes sociales solo se mostrarán si tienen una URL configurada.
        </p>
        
        @php
            $contactEmail = \Illuminate\Support\Facades\Cache::get('contact_email', '');
            $contactPhone = \Illuminate\Support\Facades\Cache::get('contact_phone', '');
            $facebookUrl = \Illuminate\Support\Facades\Cache::get('social_facebook', '');
            $twitterUrl = \Illuminate\Support\Facades\Cache::get('social_twitter', '');
            $instagramUrl = \Illuminate\Support\Facades\Cache::get('social_instagram', '');
            $youtubeUrl = \Illuminate\Support\Facades\Cache::get('social_youtube', '');
        @endphp
        
        <form method="POST" action="{{ route('admin.contact.update') }}">
            @csrf
            
            <!-- Información de Contacto -->
            <div class="mb-4">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-telephone me-2"></i>Información de Contacto
                </h6>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="contact-email" class="form-label fw-bold">Email:</label>
                        <input 
                            type="email" 
                            class="form-control" 
                            id="contact-email" 
                            name="email" 
                            value="{{ old('email', $contactEmail) }}"
                            placeholder="ejemplo@email.com"
                        >
                        <small class="form-text text-muted">
                            Este email aparecerá en el footer como enlace de contacto.
                        </small>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="contact-phone" class="form-label fw-bold">Teléfono:</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="contact-phone" 
                            name="phone" 
                            value="{{ old('phone', $contactPhone) }}"
                            placeholder="+34 123 456 789"
                        >
                        <small class="form-text text-muted">
                            Número de teléfono de contacto.
                        </small>
                    </div>
                </div>
            </div>
            
            <hr class="my-4">
            
            <!-- Redes Sociales -->
            <div class="mb-4">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-share me-2"></i>Redes Sociales
                </h6>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="social-facebook" class="form-label fw-bold">
                            <i class="bi bi-facebook text-primary me-1"></i>Facebook:
                        </label>
                        <input 
                            type="url" 
                            class="form-control" 
                            id="social-facebook" 
                            name="facebook" 
                            value="{{ old('facebook', $facebookUrl) }}"
                            placeholder="https://www.facebook.com/tu-pagina"
                        >
                    </div>
                    
                    <div class="col-md-6">
                        <label for="social-twitter" class="form-label fw-bold">
                            <i class="bi bi-twitter text-info me-1"></i>Twitter:
                        </label>
                        <input 
                            type="url" 
                            class="form-control" 
                            id="social-twitter" 
                            name="twitter" 
                            value="{{ old('twitter', $twitterUrl) }}"
                            placeholder="https://twitter.com/tu-usuario"
                        >
                    </div>
                    
                    <div class="col-md-6">
                        <label for="social-instagram" class="form-label fw-bold">
                            <i class="bi bi-instagram text-danger me-1"></i>Instagram:
                        </label>
                        <input 
                            type="url" 
                            class="form-control" 
                            id="social-instagram" 
                            name="instagram" 
                            value="{{ old('instagram', $instagramUrl) }}"
                            placeholder="https://www.instagram.com/tu-usuario"
                        >
                    </div>
                    
                    <div class="col-md-6">
                        <label for="social-youtube" class="form-label fw-bold">
                            <i class="bi bi-youtube text-danger me-1"></i>YouTube:
                        </label>
                        <input 
                            type="url" 
                            class="form-control" 
                            id="social-youtube" 
                            name="youtube" 
                            value="{{ old('youtube', $youtubeUrl) }}"
                            placeholder="https://www.youtube.com/c/tu-canal"
                        >
                    </div>
                </div>
                
                <div class="alert alert-info mt-3 mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    <small>
                        <strong>Nota:</strong> Solo se mostrarán los iconos de las redes sociales que tengan una URL configurada. 
                        Si dejas un campo vacío, ese icono no aparecerá en el footer.
                    </small>
                </div>
            </div>
            
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-save me-1"></i>Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

