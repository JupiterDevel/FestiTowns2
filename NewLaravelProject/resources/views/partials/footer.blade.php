<footer class="bg-light border-top mt-5 py-4">
    <div class="container">
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
                    <li class="list-inline-item">
                        <span class="text-muted">|</span>
                    </li>
                    <li class="list-inline-item">
                        <a href="mailto:almadelasfiestas2000@gmail.com" class="text-decoration-none text-muted">
                            <i class="bi bi-envelope me-1"></i>Contacto
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>

