<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="display-6 fw-bold text-primary mb-0">
                <i class="bi bi-people me-2"></i>Usuarios
            </h1>
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Crear Usuario
            </a>
        </div>
    </x-slot>

    <div class="container">
        {{-- Session messages handled by toast system --}}

        @if($users->count() > 0)
            <div class="row">
                @foreach($users as $user)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ $user->getPhotoUrl() }}" 
                                         alt="{{ $user->name }}" 
                                         class="rounded-circle border border-2 border-primary me-3"
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                    <div>
                                        <h5 class="card-title mb-0">{{ $user->name }}</h5>
                                        <p class="text-muted mb-0">{{ $user->email }}</p>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <span class="badge bg-secondary me-2">{{ ucfirst($user->role) }}</span>
                                    @if($user->isVisitor())
                                        <span class="badge bg-warning me-2">{{ $user->getRankIcon() }} {{ $user->getRankDisplayName() }}</span>
                                        <span class="badge bg-info">{{ $user->points }} pts</span>
                                    @endif
                                </div>
                                
                                @if($user->locality)
                                    <p class="text-muted small mb-3">
                                        <i class="bi bi-geo-alt me-1"></i>{{ $user->locality->name }}
                                    </p>
                                @endif
                                
                                <div class="mt-auto">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('users.show', $user) }}" class="btn btn-outline-primary btn-sm flex-fill">
                                            <i class="bi bi-eye me-1"></i>Ver
                                        </a>
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-warning btn-sm flex-fill">
                                            <i class="bi bi-pencil me-1"></i>Editar
                                        </a>
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="flex-fill" 
                                              onsubmit="return confirm('¿Estás seguro de que quieres eliminar este usuario?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                                <i class="bi bi-trash me-1"></i>Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <div class="card border-0">
                    <div class="card-body">
                        <i class="bi bi-people display-1 text-muted mb-3"></i>
                        <h3 class="card-title">No hay usuarios</h3>
                        <p class="card-text text-muted">Comienza creando el primer usuario del sistema.</p>
                        <a href="{{ route('users.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i>Crear Usuario
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>


