@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Premium Advertisements</h5>
    <a href="{{ route('advertisements.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle me-1"></i>Nuevo Anuncio
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.panel') }}" class="row g-3 align-items-end">
            <input type="hidden" name="tab" value="advertisements">
            <div class="col-md-10">
                <label class="form-label fw-bold">Buscar</label>
                <input type="text" name="search" class="form-control" placeholder="Nombre o URL"
                       value="{{ $search ?? '' }}">
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
                    $baseQuery = array_merge(request()->except(['page']), ['tab' => 'advertisements']);
                    $currentSort = $sort ?? 'created_at';
                    $currentDirection = $direction ?? 'desc';
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
                    <th>Imagen</th>
                    <th>
                        <a href="{{ $sortLink('name') }}" class="text-decoration-none d-inline-flex align-items-center gap-1 text-dark">
                            Nombre {!! $sortIcon('name') !!}
                        </a>
                    </th>
                    <th>Contexto</th>
                    <th>
                        <a href="{{ $sortLink('priority') }}" class="text-decoration-none d-inline-flex align-items-center gap-1 text-dark">
                            Prioridad {!! $sortIcon('priority') !!}
                        </a>
                    </th>
                    <th>
                        <a href="{{ $sortLink('active') }}" class="text-decoration-none d-inline-flex align-items-center gap-1 text-dark">
                            Activo {!! $sortIcon('active') !!}
                        </a>
                    </th>
                    <th>
                        <a href="{{ $sortLink('created_at') }}" class="text-decoration-none d-inline-flex align-items-center gap-1 text-dark">
                            Creado {!! $sortIcon('created_at') !!}
                        </a>
                    </th>
                    <th>
                        <a href="{{ $sortLink('end_date') }}" class="text-decoration-none d-inline-flex align-items-center gap-1 text-dark">
                            Fecha Fin {!! $sortIcon('end_date') !!}
                        </a>
                    </th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($advertisements ?? [] as $ad)
                    <tr>
                        <td style="width: 120px;">
                            @if($ad->image)
                                <img src="{{ \Illuminate\Support\Str::startsWith($ad->image, ['http://', 'https://']) ? $ad->image : asset($ad->image) }}"
                                     alt="{{ $ad->name }}"
                                     class="img-fluid rounded"
                                     style="max-height: 70px; object-fit: cover;">
                            @else
                                <div class="text-muted small">Sin imagen</div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $ad->name }}</div>
                            @if($ad->url)
                                <a href="{{ $ad->url }}" target="_blank" rel="noopener" class="small text-muted">
                                    {{ $ad->url }}
                                </a>
                            @endif
                        </td>
                        <td>
                            <div class="small">
                                @if($ad->festivity)
                                    <div>
                                        <a href="{{ route('festivities.show', $ad->festivity) }}" class="text-decoration-none text-primary">
                                            <i class="bi bi-calendar-event me-1"></i>{{ $ad->festivity->name }}
                                        </a>
                                    </div>
                                @endif
                                @if($ad->locality)
                                    <div class="text-muted">
                                        <i class="bi bi-geo-alt me-1"></i>{{ $ad->locality->name }}
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="badge {{ $ad->priority === 'principal' ? 'bg-primary' : 'bg-secondary' }}">
                                {{ ucfirst($ad->priority) }}
                            </span>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('advertisements.toggle', $ad) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="active" value="0">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" name="active" value="1"
                                           onchange="this.form.submit()" {{ $ad->active ? 'checked' : '' }}>
                                </div>
                            </form>
                        </td>
                        <td>{{ $ad->created_at->format('d/m/Y') }}</td>
                        <td>
                            @if($ad->end_date)
                                {{ $ad->end_date->format('d/m/Y') }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('advertisements.edit', $ad) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('advertisements.destroy', $ad) }}"
                                      onsubmit="return confirm('¿Eliminar este anuncio?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="bi bi-badge-ad display-5 text-muted d-block mb-3"></i>
                            <p class="mb-3 text-muted">No hay anuncios premium todavía.</p>
                            <a href="{{ route('advertisements.create') }}" class="btn btn-primary">
                                Crear primer anuncio
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(isset($advertisements) && $advertisements instanceof \Illuminate\Pagination\LengthAwarePaginator && $advertisements->hasPages())
        <div class="card-footer">
            {{ $advertisements->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

