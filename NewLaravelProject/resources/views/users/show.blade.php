<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="display-6 fw-bold text-primary mb-0">
                <i class="bi bi-person-circle me-2"></i>Perfil de Usuario
            </h1>
            <div class="d-flex gap-2">
                <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                    <i class="bi bi-pencil me-1"></i>Editar
                </a>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="container my-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Profile Header Card -->
                <div class="card shadow-lg border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-3 text-center mb-3 mb-md-0">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 shadow-sm" 
                                     style="width: 120px; height: 120px;">
                                    <i class="bi bi-person-fill" style="font-size: 4rem;"></i>
                                </div>
                                <h3 class="fw-bold mb-1">{{ $user->name }}</h3>
                                <p class="text-muted mb-0">
                                    <i class="bi bi-envelope me-1"></i>{{ $user->email }}
                                </p>
                            </div>
                            <div class="col-md-9">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center p-3 bg-light rounded">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                                <i class="bi bi-shield-check text-primary" style="font-size: 1.5rem;"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block">Rol</small>
                                                <span class="badge bg-secondary fs-6">{{ ucfirst($user->role) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($user->isVisitor())
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                                <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                                                    <span style="font-size: 1.5rem;">{{ $user->getRankIcon() }}</span>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Rango</small>
                                                    <span class="badge bg-warning text-dark fs-6">{{ $user->getRankDisplayName() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                                <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                                                    <i class="bi bi-star-fill text-info" style="font-size: 1.5rem;"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Puntos</small>
                                                    <span class="badge bg-info fs-6">{{ $user->points }} puntos</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if($user->locality)
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                                <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                                    <i class="bi bi-geo-alt-fill text-success" style="font-size: 1.5rem;"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Localidad</small>
                                                    <a href="{{ route('localities.show', $user->locality) }}" 
                                                       class="text-decoration-none fw-bold">
                                                        {{ $user->locality->name }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Information Card -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-info-circle me-2"></i>Información Detallada
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                                            <i class="bi bi-calendar-check text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="fw-bold mb-1">Fecha de Registro</h6>
                                        <p class="text-muted mb-0">
                                            <i class="bi bi-clock me-1"></i>
                                            {{ $user->created_at->format('d/m/Y') }} a las {{ $user->created_at->format('H:i') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            @if($user->last_login_at)
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-shrink-0">
                                            <div class="bg-success bg-opacity-10 rounded-circle p-2">
                                                <i class="bi bi-box-arrow-in-right text-success"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="fw-bold mb-1">Último Acceso</h6>
                                            <p class="text-muted mb-0">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ $user->last_login_at->format('d/m/Y') }} a las {{ $user->last_login_at->format('H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($user->google_id)
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-shrink-0">
                                            <div class="bg-danger bg-opacity-10 rounded-circle p-2">
                                                <i class="bi bi-google text-danger"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="fw-bold mb-1">Autenticación</h6>
                                            <p class="text-muted mb-0">
                                                <span class="badge bg-danger">Google OAuth</span>
                                            </p>
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


