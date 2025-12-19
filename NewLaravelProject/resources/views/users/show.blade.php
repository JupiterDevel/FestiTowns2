<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 mb-1 fw-bold" style="color: #1F2937;">
                    <i class="bi bi-person-circle me-2"></i>Perfil de usuario
                </h1>
                <p class="mb-0 text-muted" style="font-size: 0.9rem;">
                    Detalle del perfil dentro de <span style="color:#E5483B; font-weight:600;">Elalmadelafiesta</span>
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('users.edit', $user) }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-pencil me-1"></i>Editar
                </a>
                <a href="{{ route('users.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="container my-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Profile Header Card -->
                <div class="card border-0 mb-4" style="overflow: hidden;">
                    <div class="position-relative" style="background: linear-gradient(135deg, rgba(15,23,42,0.95) 0%, rgba(31,64,104,0.9) 100%);">
                        <div class="position-absolute top-0 end-0 opacity-25" style="width: 220px; height: 220px; background: radial-gradient(circle at top, rgba(254,177,1,0.4), transparent 60%);"></div>
                        <div class="position-absolute bottom-0 start-0 opacity-20" style="width: 200px; height: 200px; background: radial-gradient(circle at bottom, rgba(31,164,169,0.5), transparent 60%);"></div>

                        <div class="row align-items-center g-4 p-4 p-md-5 position-relative" style="z-index: 1;">
                            <div class="col-md-4 text-center text-md-start">
                                <div class="d-inline-block position-relative">
                                    <img src="{{ $user->getPhotoUrl() }}" 
                                         alt="{{ $user->name }}" 
                                         class="rounded-circle border border-3 mx-auto d-block shadow"
                                         style="width: 120px; height: 120px; object-fit: cover; border-color: rgba(255,255,255,0.9);">
                                    @if($user->isVisitor())
                                        <span class="position-absolute bottom-0 end-0 translate-middle p-1 rounded-circle" style="background: #FEB101; box-shadow: 0 0 0 3px rgba(15,23,42,0.9);">
                                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 30px; height: 30px; background: #1FA4A9; color:#FFFFFF; font-size: 1.05rem;">
                                                {{ $user->getRankIcon() }}
                                            </span>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-8 text-center text-md-start">
                                <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start justify-content-between gap-3">
                                    <div>
                                        <h2 class="h3 mb-1" style="color:#F9FAFB; font-weight:700;">
                                            {{ $user->name }}
                                        </h2>
                                        <p class="mb-2" style="color: rgba(249,250,251,0.9); font-size:0.95rem;">
                                            <i class="bi bi-envelope me-2"></i>{{ $user->email }}
                                        </p>
                                        <div class="d-flex flex-wrap align-items-center gap-2">
                                            @if($user->isAdmin() || $user->isTownHall())
                                                <span class="badge text-uppercase" style="background-color: #111827; color:#F9FAFB; font-size: 0.7rem; letter-spacing: 0.06em; padding: 0.3rem 0.75rem; border-radius:999px;">
                                                    {{ $user->isAdmin() ? 'Admin' : 'Town Hall' }}
                                                </span>
                                            @endif

                                            @if($user->isVisitor())
                                                <span class="badge" style="background-color:#1FA4A9; color:#FFFFFF; font-size:0.75rem; padding:0.35rem 0.85rem; border-radius:999px;">
                                                    {{ $user->getRankIcon() }} {{ $user->points }} pts
                                                </span>
                                            @endif

                                            @if($user->province)
                                                <span class="badge" style="background: linear-gradient(135deg, #FEB101 0%, #F59E0B 100%); color:#FFFFFF; font-weight:600; padding:0.35rem 0.85rem; border-radius:999px;">
                                                    {{ $user->province }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-md-end">
                                        <p class="mb-1 text-uppercase" style="font-size:0.7rem; letter-spacing:0.08em; color:rgba(249,250,251,0.7);">
                                            Miembro desde
                                        </p>
                                        <p class="mb-0" style="color:#F9FAFB; font-size:0.9rem;">
                                            {{ $user->created_at->format('d M Y') }}
                                        </p>
                                        @if($user->last_login_at)
                                            <p class="mb-0" style="color:rgba(249,250,251,0.8); font-size:0.8rem;">
                                                Último acceso: {{ $user->last_login_at->format('d/m/Y H:i') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Information Card -->
                <div class="card border-0">
                    <div class="card-header bg-white border-0 pb-0">
                        <h5 class="mb-1 fw-bold" style="color:#1F2937;">
                            <i class="bi bi-info-circle me-2" style="color:#FEB101;"></i>Información del perfil
                        </h5>
                        <p class="text-muted mb-3" style="font-size:0.9rem;">
                            Datos básicos y actividad reciente del usuario.
                        </p>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3 rounded-3" style="background-color:#F9FAFB; border:1px solid #E5E7EB;">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(254,177,1,0.12);">
                                                <i class="bi bi-shield-check" style="color:#FEB101; font-size:1.2rem;"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="fw-semibold mb-1" style="font-size:0.95rem; color:#111827;">Rol</h6>
                                            <p class="mb-0 text-muted" style="font-size:0.9rem;">
                                                {{ ucfirst($user->role) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($user->isVisitor())
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3" style="background-color:#F9FAFB; border:1px solid #E5E7EB;">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(31,164,169,0.12); font-size:1.3rem;">
                                                    {{ $user->getRankIcon() }}
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="fw-semibold mb-1" style="font-size:0.95rem; color:#111827;">Rango de fiesta</h6>
                                                <p class="mb-0 text-muted" style="font-size:0.9rem;">
                                                    {{ $user->getRankDisplayName() }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 rounded-3" style="background-color:#F9FAFB; border:1px solid #E5E7EB;">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(245,158,11,0.12);">
                                                    <i class="bi bi-star-fill" style="color:#F59E0B; font-size:1.1rem;"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="fw-semibold mb-1" style="font-size:0.95rem; color:#111827;">Puntos acumulados</h6>
                                                <p class="mb-0 text-muted" style="font-size:0.9rem;">
                                                    {{ $user->points }} puntos
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($user->locality)
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3" style="background-color:#F9FAFB; border:1px solid #E5E7EB;">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(16,185,129,0.12);">
                                                    <i class="bi bi-geo-alt-fill" style="color:#10B981; font-size:1.1rem;"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="fw-semibold mb-1" style="font-size:0.95rem; color:#111827;">Localidad asociada</h6>
                                                <a href="{{ route('localities.show', $user->locality) }}" 
                                                   class="text-decoration-none"
                                                   style="color:#1F2937; font-weight:600;">
                                                    {{ $user->locality->name }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($user->province)
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3" style="background-color:#F9FAFB; border:1px solid #E5E7EB;">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(37,99,235,0.08);">
                                                    <i class="bi bi-map" style="color:#2563EB; font-size:1.1rem;"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="fw-semibold mb-1" style="font-size:0.95rem; color:#111827;">Provincia</h6>
                                                <p class="mb-0 text-muted" style="font-size:0.9rem;">
                                                    {{ $user->province }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-6">
                                <div class="p-3 rounded-3" style="background-color:#F9FAFB; border:1px solid #E5E7EB;">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(17,24,39,0.06);">
                                                <i class="bi bi-calendar-check" style="color:#111827; font-size:1.1rem;"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="fw-semibold mb-1" style="font-size:0.95rem; color:#111827;">Fecha de registro</h6>
                                            <p class="mb-0 text-muted" style="font-size:0.9rem;">
                                                {{ $user->created_at->format('d/m/Y') }} a las {{ $user->created_at->format('H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($user->last_login_at)
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3" style="background-color:#F9FAFB; border:1px solid #E5E7EB;">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(16,185,129,0.12);">
                                                    <i class="bi bi-box-arrow-in-right" style="color:#10B981; font-size:1.1rem;"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="fw-semibold mb-1" style="font-size:0.95rem; color:#111827;">Último acceso</h6>
                                                <p class="mb-0 text-muted" style="font-size:0.9rem;">
                                                    {{ $user->last_login_at->format('d/m/Y') }} a las {{ $user->last_login_at->format('H:i') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($user->google_id)
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3" style="background-color:#F9FAFB; border:1px solid #E5E7EB;">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(229,72,59,0.08);">
                                                    <i class="bi bi-google" style="color:#E5483B; font-size:1.15rem;"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="fw-semibold mb-1" style="font-size:0.95rem; color:#111827;">Autenticación</h6>
                                                <p class="mb-0 text-muted" style="font-size:0.9rem;">
                                                    Conectado con <span style="color:#E5483B; font-weight:600;">Google OAuth</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


