{{-- Session messages handled by toast system --}}

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Sugerencias de festividades pendientes</h5>
</div>

@if(isset($festivities))
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.panel') }}" class="row g-3 align-items-end">
                <input type="hidden" name="tab" value="festivities">
                <div class="col-md-10">
                    <label class="form-label fw-bold">Buscar</label>
                    <input type="text" name="search" class="form-control" placeholder="Nombre de festividad, usuario o localidad"
                           value="{{ $festivities_search ?? '' }}">
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
                        $baseQuery = array_merge(request()->except(['page']), ['tab' => 'festivities']);
                        $currentSort = $festivities_sort ?? 'created_at';
                        $currentDirection = $festivities_direction ?? 'desc';
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
                                Festividad {!! $sortIcon('name') !!}
                            </a>
                        </th>
                        <th>
                            <a href="{{ $sortLink('locality_name') }}" class="text-decoration-none d-inline-flex align-items-center gap-1 text-dark">
                                Localidad {!! $sortIcon('locality_name') !!}
                            </a>
                        </th>
                        <th>
                            <a href="{{ $sortLink('user_name') }}" class="text-decoration-none d-inline-flex align-items-center gap-1 text-dark">
                                Usuario {!! $sortIcon('user_name') !!}
                            </a>
                        </th>
                        <th>
                            <a href="{{ $sortLink('created_at') }}" class="text-decoration-none d-inline-flex align-items-center gap-1 text-dark">
                                Fecha {!! $sortIcon('created_at') !!}
                            </a>
                        </th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if(($festivities instanceof \Illuminate\Pagination\LengthAwarePaginator ? $festivities->count() > 0 : $festivities->count() > 0))
                        @foreach($festivities as $festivity)
                            <tr>
                                <td style="width: 80px;">
                                    @if($festivity->photos && count($festivity->photos) > 0)
                                        <img src="{{ $festivity->photos[0] }}" 
                                             alt="{{ $festivity->name }}" 
                                             class="img-fluid rounded"
                                             style="max-height: 60px; max-width: 60px; object-fit: cover; cursor: pointer;"
                                             onclick="openImageModal('{{ $festivity->photos[0] }}')">
                                    @else
                                        <div class="text-muted small">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold">
                                        <a href="{{ route('festivities.show', $festivity) }}" class="text-decoration-none text-dark" target="_blank">
                                            {{ $festivity->name }}
                                            <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                                        </a>
                                    </div>
                                    <div class="small text-muted">
                                        {{ Str::limit($festivity->description, 50) }}
                                    </div>
                                </td>
                                <td>
                                    <div class="small">
                                        <i class="bi bi-geo-alt me-1"></i>{{ $festivity->locality->name ?? 'N/A' }}
                                    </div>
                                    @if($festivity->province)
                                        <div class="text-muted small">{{ $festivity->province }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if($festivity->user)
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                <img src="{{ $festivity->user->getPhotoUrl() }}" 
                                                     alt="{{ $festivity->user->name }}" 
                                                     class="rounded-circle border border-2 border-primary"
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            </div>
                                            <div>
                                                <div class="fw-semibold">
                                                    <a href="{{ route('users.show', $festivity->user) }}" class="text-decoration-none text-dark">
                                                        {{ $festivity->user->name }}
                                                    </a>
                                                </div>
                                                <div class="small text-muted">{{ $festivity->user->email }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-muted small">Usuario eliminado</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="small">{{ $festivity->created_at->format('d/m/Y') }}</div>
                                    <div class="text-muted small">{{ $festivity->created_at->format('H:i') }}</div>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('festivities.show', $festivity) }}" class="btn btn-sm btn-info" title="Ver festividad" target="_blank">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <form method="POST" action="{{ route('festivities.approve', $festivity) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" title="Aprobar">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('festivities.reject', $festivity) }}" class="d-inline"
                                              onsubmit="return confirm('¿Estás seguro de que quieres rechazar esta sugerencia de festividad?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Rechazar">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-calendar-check display-5 text-muted d-block mb-3"></i>
                                <p class="mb-0 text-muted">
                                    @if(!empty($festivities_search))
                                        No se encontraron sugerencias de festividades que coincidan con la búsqueda "{{ $festivities_search }}".
                                    @else
                                        No hay sugerencias de festividades pendientes de aprobación.
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        @if($festivities instanceof \Illuminate\Pagination\LengthAwarePaginator && $festivities->hasPages())
            <div class="card-footer">
                {{ $festivities->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@else
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-calendar-check display-1 text-muted d-block mb-3"></i>
            <h3 class="mt-3 text-muted">No hay sugerencias pendientes</h3>
            <p class="text-muted">Todas las sugerencias de festividades han sido revisadas. Vuelve más tarde para ver nuevas solicitudes.</p>
            <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                <i class="bi bi-house me-1"></i>Volver al inicio
            </a>
        </div>
    </div>
@endif

<!-- Modal para ver imagen en grande -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Imagen de la festividad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modal-image" src="" alt="Imagen ampliada" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script>
    function openImageModal(imageSrc) {
        document.getElementById('modal-image').src = imageSrc;
        const modal = new bootstrap.Modal(document.getElementById('imageModal'));
        modal.show();
    }
</script>

