{{-- Session messages handled by toast system --}}

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Gestión de usuarios</h5>
    <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle me-1"></i>Crear Usuario
    </a>
</div>

@if(isset($users))
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.panel') }}" class="row g-3 align-items-end">
                <input type="hidden" name="tab" value="users">
                <div class="col-md-10">
                    <label class="form-label fw-bold">Buscar</label>
                    <input type="text" name="search" class="form-control" placeholder="Nombre, email o localidad"
                           value="{{ $users_search ?? '' }}">
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-outline-primary mt-md-4">
                        <i class="bi bi-search me-1"></i>Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    @php
                        $baseQuery = array_merge(request()->except(['page']), ['tab' => 'users']);
                        $currentSort = $users_sort ?? 'created_at';
                        $currentDirection = $users_direction ?? 'desc';
                        $sortLink = function (string $column) use ($baseQuery, $currentSort, $currentDirection) {
                            $nextDirection = ($currentSort === $column && $currentDirection === 'asc') ? 'desc' : 'asc';
                            return route('admin.panel', array_merge($baseQuery, [
                                'sort' => $column,
                                'direction' => $nextDirection,
                            ]));
                        };
                        $sortIcon = function (string $column) use ($currentSort, $currentDirection) {
                            if ($currentSort !== $column) {
                                return '<i class="bi bi-arrow-down-up text-muted small"></i>';
                            }
                            return $currentDirection === 'asc'
                                ? '<i class="bi bi-caret-up-fill text-primary small"></i>'
                                : '<i class="bi bi-caret-down-fill text-primary small"></i>';
                        };
                    @endphp
                    <tr>
                        <th>Foto</th>
                        <th>
                            <a href="{{ $sortLink('name') }}" class="text-decoration-none d-inline-flex align-items-center gap-1 text-dark">
                                Nombre {!! $sortIcon('name') !!}
                            </a>
                        </th>
                        <th>
                            <a href="{{ $sortLink('email') }}" class="text-decoration-none d-inline-flex align-items-center gap-1 text-dark">
                                Email {!! $sortIcon('email') !!}
                            </a>
                        </th>
                        <th>
                            <a href="{{ $sortLink('role') }}" class="text-decoration-none d-inline-flex align-items-center gap-1 text-dark">
                                Rol {!! $sortIcon('role') !!}
                            </a>
                        </th>
                        <th>
                            <a href="{{ $sortLink('locality_name') }}" class="text-decoration-none d-inline-flex align-items-center gap-1 text-dark">
                                Localidad {!! $sortIcon('locality_name') !!}
                            </a>
                        </th>
                        <th>Información</th>
                        <th>
                            <a href="{{ $sortLink('created_at') }}" class="text-decoration-none d-inline-flex align-items-center gap-1 text-dark">
                                Creado {!! $sortIcon('created_at') !!}
                            </a>
                        </th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if(($users instanceof \Illuminate\Pagination\LengthAwarePaginator ? $users->count() > 0 : $users->count() > 0))
                        @foreach($users as $user)
                            <tr>
                                <td style="width: 60px;">
                                    <img src="{{ $user->getPhotoUrl() }}" 
                                         alt="{{ $user->name }}" 
                                         class="rounded-circle border border-2 border-primary"
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                </td>
                                <td>
                                    <div class="fw-semibold">
                                        <a href="{{ route('users.show', $user) }}" class="text-decoration-none text-dark">
                                            {{ $user->name }}
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <div class="small">{{ $user->email }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span>
                                </td>
                                <td>
                                    @if($user->locality)
                                        <div class="small">
                                            <i class="bi bi-geo-alt me-1"></i>{{ $user->locality->name }}
                                        </div>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @if($user->isVisitor())
                                            <span class="badge bg-warning">{{ $user->getRankIcon() }} {{ $user->getRankDisplayName() }}</span>
                                            <span class="badge bg-info">{{ $user->points }} pts</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="small">{{ $user->created_at->format('d/m/Y') }}</div>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-primary" title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="{{ route('users.destroy', $user) }}" class="d-inline"
                                              onsubmit="return confirm('¿Estás seguro de que quieres eliminar este usuario?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-search display-5 text-muted d-block mb-3"></i>
                                <p class="mb-0 text-muted">
                                    @if(!empty($users_search))
                                        No se encontraron usuarios que coincidan con la búsqueda "{{ $users_search }}".
                                    @else
                                        No hay usuarios en el sistema.
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator && $users->hasPages())
            <div class="card-footer">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@else
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-people display-1 text-muted d-block mb-3"></i>
            <h3 class="mt-3 text-muted">No hay usuarios</h3>
            <p class="text-muted">Comienza creando el primer usuario del sistema.</p>
            <a href="{{ route('users.create') }}" class="btn btn-primary mt-3">
                <i class="bi bi-plus-circle me-1"></i>Crear Usuario
            </a>
        </div>
    </div>
@endif
