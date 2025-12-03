<x-app-layout>
    <x-slot name="header">
        <h1 class="display-6 fw-bold text-primary mb-0">
            <i class="bi bi-person-gear me-2"></i>Mi Perfil
        </h1>
    </x-slot>

    <div class="container my-4">
        <!-- Profile Header -->
        <div class="card shadow-lg border-0 mb-4">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow" 
                             style="width: 100px; height: 100px;">
                            <i class="bi bi-person-fill" style="font-size: 3.5rem;"></i>
                        </div>
                    </div>
                    <div class="col">
                        <h2 class="fw-bold mb-1">{{ auth()->user()->name }}</h2>
                        <p class="text-muted mb-2">
                            <i class="bi bi-envelope me-1"></i>{{ auth()->user()->email }}
                        </p>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-secondary fs-6">{{ ucfirst(auth()->user()->role) }}</span>
                            @if(auth()->user()->isVisitor())
                                <span class="badge bg-warning text-dark fs-6">
                                    {{ auth()->user()->getRankIcon() }} {{ auth()->user()->getRankDisplayName() }}
                                </span>
                                <span class="badge bg-info fs-6">
                                    <i class="bi bi-star-fill me-1"></i>{{ auth()->user()->points }} puntos
                                </span>
                            @endif
                            @if(auth()->user()->locality)
                                <span class="badge bg-success fs-6">
                                    <i class="bi bi-geo-alt me-1"></i>{{ auth()->user()->locality->name }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar with Stats -->
            <div class="col-lg-3 mb-4 mb-lg-0">
                @if(auth()->user()->isVisitor())
                    <!-- Ranking Card -->
                    <div class="card shadow-sm border-0 mb-3">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <span style="font-size: 4rem;">{{ auth()->user()->getRankIcon() }}</span>
                            </div>
                            <h5 class="fw-bold mb-2">{{ auth()->user()->getRankDisplayName() }}</h5>
                            <p class="text-muted small mb-3">Rango actual</p>
                            
                            @if(auth()->user()->rank === 'bronze')
                                <div class="mb-2">
                                    <small class="text-muted d-block mb-1">Próximo: 🥈 Plata (200 puntos)</small>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-warning" role="progressbar" 
                                             style="width: {{ min(100, (auth()->user()->points / 200) * 100) }}%"></div>
                                    </div>
                                    <small class="text-muted">{{ auth()->user()->points }} / 200 puntos</small>
                                </div>
                            @elseif(auth()->user()->rank === 'silver')
                                <div class="mb-2">
                                    <small class="text-muted d-block mb-1">Próximo: 🥇 Oro (500 puntos)</small>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-secondary" role="progressbar" 
                                             style="width: {{ min(100, ((auth()->user()->points - 200) / 300) * 100) }}%"></div>
                                    </div>
                                    <small class="text-muted">{{ auth()->user()->points }} / 500 puntos</small>
                                </div>
                            @else
                                <p class="text-success small mb-0 fw-bold">¡Rango máximo!</p>
                            @endif
                        </div>
                    </div>

                    <!-- Points Info -->
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-info-circle me-1"></i>Cómo ganar puntos
                            </h6>
                            <ul class="list-unstyled mb-0 small">
                                <li class="mb-2">
                                    <i class="bi bi-chat-dots text-primary me-2"></i>
                                    Comentar: <strong>2 pts</strong>
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-hand-thumbs-up text-success me-2"></i>
                                    Votar: <strong>10 pts</strong>
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-calendar-check text-info me-2"></i>
                                    Login diario: <strong>1 pt</strong>
                                </li>
                                <li>
                                    <i class="bi bi-geo-alt text-warning me-2"></i>
                                    Visitar otras: <strong>1 pt/día</strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Main Content with Tabs -->
            <div class="col-lg-9">
                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" 
                                type="button" role="tab">
                            <i class="bi bi-person me-1"></i>Información
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" 
                                type="button" role="tab">
                            <i class="bi bi-lock me-1"></i>Contraseña
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link text-danger" id="danger-tab" data-bs-toggle="tab" data-bs-target="#danger" 
                                type="button" role="tab">
                            <i class="bi bi-exclamation-triangle me-1"></i>Eliminar Cuenta
                        </button>
                    </li>
                </ul>

                <!-- Tabs Content -->
                <div class="tab-content" id="profileTabsContent">
                    <!-- Profile Information Tab -->
                    <div class="tab-pane fade show active" id="profile" role="tabpanel">
                        <div class="card shadow-sm border-0">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-4">
                                    <i class="bi bi-person me-2"></i>Información del Perfil
                                </h5>
                                @include('profile.partials.update-profile-information-form')
                            </div>
                        </div>
                    </div>

                    <!-- Password Tab -->
                    <div class="tab-pane fade" id="password" role="tabpanel">
                        <div class="card shadow-sm border-0">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-4">
                                    <i class="bi bi-lock me-2"></i>Actualizar Contraseña
                                </h5>
                                @include('profile.partials.update-password-form')
                            </div>
                        </div>
                    </div>

                    <!-- Delete Account Tab -->
                    <div class="tab-pane fade" id="danger" role="tabpanel">
                        <div class="card shadow-sm border-0 border-danger">
                            <div class="card-body p-4">
                                <h5 class="fw-bold text-danger mb-4">
                                    <i class="bi bi-exclamation-triangle me-2"></i>Eliminar Cuenta
                                </h5>
                                @include('profile.partials.delete-user-form')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
