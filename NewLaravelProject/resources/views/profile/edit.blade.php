<x-app-layout>
    <x-slot name="header">
        <h1 class="h2 mb-0 fw-bold" style="color:#1F2937;">
            <i class="bi bi-person-gear me-2"></i>Mi perfil
        </h1>
    </x-slot>

    <div style="background: radial-gradient(circle at top, rgba(254,177,1,0.12), #F3F4F6); margin: -1.5rem 0 -3rem 0; padding: 2rem 0 3rem 0;">
        <div class="container">
        <!-- Profile Header -->
        <div class="card border-0 mb-4" style="overflow:hidden;">
            <div class="position-relative" style="background: linear-gradient(135deg, rgba(15,23,42,0.95) 0%, rgba(31,64,104,0.9) 100%);">
                <div class="position-absolute top-0 end-0 opacity-25" style="width: 200px; height: 200px; background: radial-gradient(circle at top, rgba(254,177,1,0.4), transparent 60%);"></div>
                <div class="position-absolute bottom-0 start-0 opacity-20" style="width: 180px; height: 180px; background: radial-gradient(circle at bottom, rgba(31,164,169,0.5), transparent 60%);"></div>

                <div class="row align-items-center g-3 p-3 p-md-4 position-relative" style="z-index: 1;">
                    <div class="col-md-8 order-2 order-md-1">
                        <h2 class="h3 mb-1" style="color:#F9FAFB; font-weight:700;">
                            {{ auth()->user()->name }}
                        </h2>
                        <p class="mb-2" style="color:rgba(249,250,251,0.9); font-size:0.95rem;">
                            <i class="bi bi-envelope me-2"></i>{{ auth()->user()->email }}
                        </p>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            @php
                                $roleLabel = match(auth()->user()->role) {
                                    'admin' => 'ADMIN',
                                    'townhall' => 'AYUNTAMIENTO',
                                    'visitor' => 'VISITANTE',
                                    default => strtoupper(auth()->user()->role),
                                };
                            @endphp
                            <span class="badge text-uppercase" style="background-color:#111827; color:#F9FAFB; font-size:0.7rem; letter-spacing:0.06em; padding:0.3rem 0.75rem; border-radius:999px;">
                                {{ $roleLabel }}
                            </span>

                            @if(auth()->user()->isVisitor())
                                @php
                                    $rankClass = match(auth()->user()->rank) {
                                        'gold' => 'bg-warning text-dark',
                                        'silver' => 'bg-secondary text-white',
                                        'bronze' => 'bg-secondary text-white',
                                        default => 'bg-secondary text-white',
                                    };
                                @endphp
                                <span class="badge {{ $rankClass }}" style="font-size:0.75rem; padding:0.35rem 0.85rem; border-radius:999px;">
                                    {{ auth()->user()->getRankIcon() }} {{ auth()->user()->getRankDisplayName() }}
                                </span>
                                <span class="badge" style="background-color:#1FA4A9; color:#FFFFFF; font-size:0.75rem; padding:0.35rem 0.85rem; border-radius:999px;">
                                    <i class="bi bi-star-fill me-1"></i>{{ auth()->user()->points }} pts
                                </span>
                            @endif

                            @if(auth()->user()->locality)
                                <span class="badge" style="background-color:#10B981; color:#FFFFFF; font-size:0.75rem; padding:0.35rem 0.85rem; border-radius:999px;">
                                    <i class="bi bi-geo-alt me-1"></i>{{ auth()->user()->locality->name }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 text-center text-md-end order-1 order-md-2">
                        <div class="d-inline-block position-relative">
                            <img src="{{ auth()->user()->getPhotoUrl() }}" 
                                 alt="{{ auth()->user()->name }}" 
                                 class="rounded-circle border border-3 mx-auto d-block shadow"
                                 style="width: 110px; height: 110px; object-fit: cover; border-color: rgba(255,255,255,0.9);">
                            @if(auth()->user()->isVisitor())
                                <span class="position-absolute bottom-0 end-0 translate-middle p-1 rounded-circle" style="background:#FEB101; box-shadow:0 0 0 3px rgba(15,23,42,0.9);">
                                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width:28px; height:28px; background:#1FA4A9; color:#FFFFFF; font-size:1.05rem;">
                                        {{ auth()->user()->getRankIcon() }}
                                    </span>
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
                    <div class="card border-0 mb-3" style="background: radial-gradient(circle at top, rgba(254,177,1,0.16), #111827); color:#F9FAFB;">
                        <div class="card-body text-center">
                            <div class="mb-2">
                                <span style="font-size:3.2rem;">{{ auth()->user()->getRankIcon() }}</span>
                            </div>
                            <h5 class="fw-bold mb-3">{{ auth()->user()->getRankDisplayName() }}</h5>
                            @if(auth()->user()->rank === 'bronze')
                                <div class="mb-2">
                                    <small class="d-block mb-1" style="color:rgba(249,250,251,0.8);">Próximo: 🥈 Plata (200 puntos)</small>
                                    <div class="progress" style="height: 6px; background-color:rgba(15,23,42,0.7);">
                                        <div class="progress-bar" role="progressbar" 
                                             style="width: {{ min(100, (auth()->user()->points / 200) * 100) }}%; background: linear-gradient(90deg,#F97316,#F59E0B);"></div>
                                    </div>
                                    <small style="color:rgba(249,250,251,0.8);">{{ auth()->user()->points }} / 200 puntos</small>
                                </div>
                            @elseif(auth()->user()->rank === 'silver')
                                <div class="mb-2">
                                    <small class="d-block mb-1" style="color:rgba(249,250,251,0.8);">Próximo: 🥇 Oro (500 puntos)</small>
                                    <div class="progress" style="height: 6px; background-color:rgba(15,23,42,0.7);">
                                        <div class="progress-bar" role="progressbar" 
                                             style="width: {{ min(100, ((auth()->user()->points - 200) / 300) * 100) }}%; background: linear-gradient(90deg,#9CA3AF,#E5E7EB);"></div>
                                    </div>
                                    <small style="color:rgba(249,250,251,0.8);">{{ auth()->user()->points }} / 500 puntos</small>
                                </div>
                            @else
                                <p class="small mb-0 fw-bold" style="color:#6EE7B7;">¡Rango máximo!</p>
                            @endif
                        </div>
                    </div>

                    <!-- Points Info -->
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3" style="color:#111827;">
                                <i class="bi bi-info-circle me-1" style="color:#FEB101;"></i>Cómo ganar puntos
                            </h6>
                            <ul class="list-unstyled mb-0 small" style="color:#4B5563;">
                                <li class="mb-2">
                                    <i class="bi bi-chat-dots me-2" style="color:#1FA4A9;"></i>
                                    Comentar: <strong>2 pts</strong>
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-hand-thumbs-up me-2" style="color:#10B981;"></i>
                                    Votar: <strong>10 pts</strong>
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-calendar-check me-2" style="color:#F59E0B;"></i>
                                    Login diario: <strong>1 pt</strong>
                                </li>
                                <li>
                                    <i class="bi bi-geo-alt me-2" style="color:#FEB101;"></i>
                                    Visitar otras: <strong>1 pt/día</strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Main Content with Tabs -->
            <div class="col-lg-9">
                <div class="bg-white rounded-3 shadow-sm p-3 p-md-4">
                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs profile-tabs mb-4" id="profileTabs" role="tablist">
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
                            <h5 class="fw-bold mb-4" style="color:#111827;">
                                <i class="bi bi-person me-2" style="color:#FEB101;"></i>Información del perfil
                            </h5>
                            @include('profile.partials.update-profile-information-form')
                        </div>

                        <!-- Password Tab -->
                        <div class="tab-pane fade" id="password" role="tabpanel">
                            <h5 class="fw-bold mb-4" style="color:#111827;">
                                <i class="bi bi-lock me-2" style="color:#FEB101;"></i>Actualizar contraseña
                            </h5>
                            @include('profile.partials.update-password-form')
                        </div>

                        <!-- Delete Account Tab -->
                        <div class="tab-pane fade" id="danger" role="tabpanel">
                            <h5 class="fw-bold text-danger mb-4">
                                <i class="bi bi-exclamation-triangle me-2"></i>Eliminar cuenta
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
