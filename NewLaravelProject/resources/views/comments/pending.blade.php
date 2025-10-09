<x-app-layout>
    <x-slot name="header">
        <h1 class="display-6 fw-bold text-primary mb-0">
            <i class="bi bi-chat-dots me-2"></i>Pending Comments
        </h1>
    </x-slot>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($comments->count() > 0)
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
                                    <h6 class="fw-bold text-dark mb-2">
                                        <i class="bi bi-chat-quote me-2"></i>Comment:
                                    </h6>
                                    <p class="mb-0 text-dark">{{ $comment->content }}</p>
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
    </div>
</x-app-layout>
