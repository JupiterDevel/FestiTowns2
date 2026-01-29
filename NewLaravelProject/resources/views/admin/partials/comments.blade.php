{{-- Session messages handled by toast system --}}

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Moderación de comentarios</h5>
</div>

@if(isset($comments))
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.panel') }}" class="row g-3 align-items-end">
                <input type="hidden" name="tab" value="comments">
                <div class="col-md-10">
                    <label class="form-label fw-bold">Buscar</label>
                    <input type="text" name="search" class="form-control" placeholder="Usuario, email, contenido o festividad"
                           value="{{ $comments_search ?? '' }}">
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
                        $baseQuery = array_merge(request()->except(['page']), ['tab' => 'comments']);
                        $currentSort = $comments_sort ?? 'created_at';
                        $currentDirection = $comments_direction ?? 'desc';
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
                            <a href="{{ $sortLink('user_name') }}" class="text-decoration-none d-inline-flex align-items-center gap-1 text-dark">
                                Usuario {!! $sortIcon('user_name') !!}
                            </a>
                        </th>
                        <th>
                            <a href="{{ $sortLink('content') }}" class="text-decoration-none d-inline-flex align-items-center gap-1 text-dark">
                                Comentario {!! $sortIcon('content') !!}
                            </a>
                        </th>
                        <th>
                            <a href="{{ $sortLink('festivity_name') }}" class="text-decoration-none d-inline-flex align-items-center gap-1 text-dark">
                                Festividad {!! $sortIcon('festivity_name') !!}
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
                    @if(($comments instanceof \Illuminate\Pagination\LengthAwarePaginator ? $comments->count() > 0 : $comments->count() > 0))
                        @foreach($comments as $comment)
                            <tr>
                                <td style="width: 80px;">
                                    @if($comment->photo)
                                        <img src="{{ asset($comment->photo) }}" 
                                             alt="Foto del comentario" 
                                             class="img-fluid rounded"
                                             style="max-height: 60px; max-width: 60px; object-fit: cover; cursor: pointer;"
                                             onclick="openImageModal('{{ asset($comment->photo) }}')">
                                    @else
                                        <div class="text-muted small">Sin foto</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            <img src="{{ $comment->user->getPhotoUrl() }}" 
                                                 alt="{{ $comment->user->name }}" 
                                                 class="rounded-circle border border-2 border-primary"
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        </div>
                                        <div>
                                            <div class="fw-semibold">
                                                <a href="{{ route('users.show', $comment->user) }}" class="text-decoration-none text-dark">
                                                    {{ $comment->user->name }}
                                                </a>
                                            </div>
                                            <div class="small text-muted">{{ $comment->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 300px;" title="{{ $comment->content }}">
                                        {{ Str::limit($comment->content, 100) }}
                                    </div>
                                </td>
                                <td>
                                    <div class="small">
                                        <a href="{{ route('festivities.show', $comment->festivity) }}" class="text-decoration-none text-primary">
                                            <i class="bi bi-calendar-event me-1"></i>{{ $comment->festivity->name }}
                                        </a>
                                        <div class="text-muted">
                                            <i class="bi bi-geo-alt me-1"></i>{{ $comment->festivity->locality->name }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small">{{ $comment->created_at->format('d/m/Y') }}</div>
                                    <div class="text-muted small">{{ $comment->created_at->format('H:i') }}</div>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <form method="POST" action="{{ route('comments.approve', $comment) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" title="Aprobar">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('comments.reject', $comment) }}" class="d-inline"
                                              onsubmit="return confirm('¿Estás seguro de que quieres rechazar este comentario?')">
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
                                <i class="bi bi-search display-5 text-muted d-block mb-3"></i>
                                <p class="mb-0 text-muted">
                                    @if(!empty($comments_search))
                                        No se encontraron comentarios que coincidan con la búsqueda "{{ $comments_search }}".
                                    @else
                                        No hay comentarios pendientes de moderación.
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        @if($comments instanceof \Illuminate\Pagination\LengthAwarePaginator && $comments->hasPages())
            <div class="card-footer">
                {{ $comments->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@else
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-chat-dots display-1 text-muted d-block mb-3"></i>
            <h3 class="mt-3 text-muted">No hay comentarios pendientes</h3>
            <p class="text-muted">Todos los comentarios han sido moderados. Vuelve más tarde para ver nuevas solicitudes.</p>
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
                <h5 class="modal-title">Imagen del comentario</h5>
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
