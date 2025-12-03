@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(isset($comments) && $comments->count() > 0)
    <div class="row g-4">
        @foreach($comments as $comment)
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title mb-1">
                                    <i class="bi bi-person-circle me-2"></i>{{ $comment->user->name }}
                                </h5>
                                <p class="text-muted small mb-1">
                                    <i class="bi bi-envelope me-1"></i>{{ $comment->user->email }}
                                </p>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-clock me-1"></i>{{ $comment->created_at->format('M j, Y g:i A') }}
                                </p>
                            </div>
                            <div class="d-flex gap-2">
                                <form method="POST" action="{{ route('comments.approve', $comment) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="bi bi-check-circle me-1"></i>Approve
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('comments.reject', $comment) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to reject this comment?')">
                                        <i class="bi bi-x-circle me-1"></i>Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="fw-bold text-primary mb-2">
                                <i class="bi bi-calendar-event me-2"></i>Festivity:
                            </h6>
                            <a href="{{ route('festivities.show', $comment->festivity) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye me-1"></i>{{ $comment->festivity->name }} - {{ $comment->festivity->locality->name }}
                            </a>
                        </div>
                        
                        <div class="bg-light rounded p-3">
                            <h6 class="fw-bold text-dark mb-3">
                                <i class="bi bi-chat-quote me-2"></i>Comentario:
                            </h6>
                            <div class="row g-3">
                                @if($comment->photo)
                                    <div class="col-auto">
                                        <div class="mb-2">
                                            <small class="text-muted d-block mb-1">
                                                <i class="bi bi-image me-1"></i>Foto adjunta:
                                            </small>
                                        </div>
                                        <img src="{{ asset($comment->photo) }}" 
                                             alt="Foto del comentario de {{ $comment->user->name }}" 
                                             class="rounded shadow-sm"
                                             style="width: 120px; height: 120px; object-fit: cover; cursor: pointer;"
                                             onclick="openImageModal('{{ asset($comment->photo) }}')"
                                             onerror="this.style.display='none';">
                                    </div>
                                @endif
                                <div class="col">
                                    <p class="mb-0 text-dark">{{ $comment->content }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-5">
        <div class="card">
            <div class="card-body py-5">
                <i class="bi bi-chat-dots display-1 text-muted"></i>
                <h3 class="mt-3 text-muted">No Pending Comments</h3>
                <p class="text-muted">All comments have been moderated. Check back later for new submissions.</p>
                <a href="{{ route('home') }}" class="btn btn-primary btn-custom mt-3">
                    <i class="bi bi-house me-1"></i>Back to Home
                </a>
            </div>
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

