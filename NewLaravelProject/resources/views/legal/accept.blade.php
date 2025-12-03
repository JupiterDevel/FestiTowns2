<x-app-layout>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <h2 class="card-title text-center mb-4 fw-bold">
                            <i class="bi bi-shield-check me-2"></i>Aceptación de Términos Legales
                        </h2>

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="alert alert-info" role="alert">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Importante:</strong> Para continuar utilizando El Alma de las Fiestas, debe aceptar nuestros 
                            Términos y Condiciones y nuestra Política de Cookies.
                        </div>

                        <p class="mb-4">
                            Por favor, lea cuidadosamente nuestros términos legales antes de continuar. Puede acceder a 
                            la información completa haciendo clic en los siguientes enlaces:
                        </p>

                        <div class="mb-4">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <a href="{{ route('legal.index') }}#terms" target="_blank" class="text-decoration-none">
                                        <i class="bi bi-file-earmark-text me-2"></i>Términos y Condiciones
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="{{ route('legal.index') }}#cookies" target="_blank" class="text-decoration-none">
                                        <i class="bi bi-cookie me-2"></i>Política de Cookies
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('legal.index') }}#contact" target="_blank" class="text-decoration-none">
                                        <i class="bi bi-envelope me-2"></i>Información de Contacto
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <form method="POST" action="{{ route('legal.accept') }}">
                            @csrf

                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input @error('accepted_legal') is-invalid @enderror" 
                                           type="checkbox" name="accepted_legal" id="accepted_legal" 
                                           value="1" required>
                                    <label class="form-check-label" for="accepted_legal">
                                        He leído y acepto los <a href="{{ route('legal.index') }}#terms" target="_blank" class="text-decoration-none">Términos y Condiciones</a> 
                                        y la <a href="{{ route('legal.index') }}#cookies" target="_blank" class="text-decoration-none">Política de Cookies</a> de El Alma de las Fiestas.
                                    </label>
                                    @error('accepted_legal')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-check-circle me-2"></i>Aceptar y Continuar
                                </button>
                                <a href="{{ route('legal.index') }}" class="btn btn-outline-secondary" target="_blank">
                                    <i class="bi bi-file-text me-2"></i>Leer Términos Completos
                                </a>
                            </div>
                        </form>

                        <div class="mt-4 text-center">
                            <p class="text-muted small mb-0">
                                Si no acepta estos términos, no podrá utilizar los servicios de El Alma de las Fiestas.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

