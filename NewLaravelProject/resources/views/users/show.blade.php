<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="display-6 fw-bold text-primary mb-0">
                <i class="bi bi-person me-2"></i>{{ $user->name }}
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

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px;">
                                    <i class="bi bi-person-fill" style="font-size: 3rem;"></i>
                                </div>
                                <h4>{{ $user->name }}</h4>
                                <p class="text-muted">{{ $user->email }}</p>
                            </div>
                            <div class="col-md-8">
                                <h5 class="mb-3">Información del Usuario</h5>
                                
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Rol:</strong></div>
                                    <div class="col-sm-8">
                                        <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span>
                                    </div>
                                </div>

                                @if($user->isVisitor())
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Rango:</strong></div>
                                        <div class="col-sm-8">
                                            <span class="badge bg-warning">{{ $user->getRankIcon() }} {{ $user->getRankDisplayName() }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Puntos:</strong></div>
                                        <div class="col-sm-8">
                                            <span class="badge bg-info">{{ $user->points }} puntos</span>
                                        </div>
                                    </div>
                                @endif

                                @if($user->locality)
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Localidad:</strong></div>
                                        <div class="col-sm-8">
                                            <a href="{{ route('localities.show', $user->locality) }}" class="text-decoration-none">
                                                <i class="bi bi-geo-alt me-1"></i>{{ $user->locality->name }}
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Fecha de registro:</strong></div>
                                    <div class="col-sm-8">{{ $user->created_at->format('d/m/Y H:i') }}</div>
                                </div>

                                @if($user->last_login_at)
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Último login:</strong></div>
                                        <div class="col-sm-8">{{ $user->last_login_at->format('d/m/Y H:i') }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


