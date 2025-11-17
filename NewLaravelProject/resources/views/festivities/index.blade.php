<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="display-6 fw-bold text-primary mb-0">
                <i class="bi bi-calendar-event me-2"></i>Festivities
            </h1>
            @auth
                @can('create', App\Models\Festivity::class)
                    @if(!auth()->user()->isTownHall())
                        <a href="{{ route('festivities.create') }}" class="btn btn-primary btn-custom">
                            <i class="bi bi-plus-circle me-1"></i>Add Festivity
                        </a>
                    @else
                        <div class="alert alert-info">
                            <small><i class="bi bi-info-circle me-1"></i>Los usuarios con rol de ayuntamiento solo pueden añadir festividades desde la vista de su localidad.</small>
                        </div>
                    @endif
                @else
                    <div class="alert alert-warning">
                        <small>You don't have permission to create festivities. Current role: {{ auth()->user()->role }}</small>
                    </div>
                @endcan
            @else
                <div class="alert alert-info">
                    <small>Please <a href="{{ route('login') }}">login</a> to add festivities</small>
                </div>
            @endauth
        </div>
    </x-slot>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            @foreach($festivities as $festivity)
                <div class="col-md-6 col-lg-4">
                    <div class="card festivity-card card-hover h-100">
                        @if($festivity->photos && count($festivity->photos) > 0)
                            <img src="{{ $festivity->photos[0] }}" class="card-img-top" alt="{{ $festivity->name }}">
                        @else
                            <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                                <div class="text-center text-muted">
                                    <i class="bi bi-image display-4"></i>
                                    <p class="mt-2 mb-0">No image available</p>
                                </div>
                            </div>
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $festivity->name }}</h5>
                            <p class="text-muted mb-2">
                                <i class="bi bi-geo-alt me-1"></i>{{ $festivity->locality->name }}
                                @if($festivity->province)
                                    <br><small><i class="bi bi-map me-1"></i>{{ $festivity->province }}</small>
                                @endif
                            </p>
                            <p class="text-muted small mb-3">
                                <i class="bi bi-calendar me-1"></i>
                                {{ $festivity->start_date->format('M j, Y') }}
                                @if($festivity->end_date)
                                    - {{ $festivity->end_date->format('M j, Y') }}
                                @endif
                            </p>
                            <p class="card-text flex-grow-1">{{ Str::limit($festivity->description, 120) }}</p>
                            
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-info">
                                        <i class="bi bi-chat-dots me-1"></i>
                                        {{ $festivity->approvedComments->count() }} 
                                        {{ Str::plural('comment', $festivity->approvedComments->count()) }}
                                    </span>
                                </div>
                                
                                <div class="d-flex gap-2 flex-wrap">
                                    <a href="{{ route('festivities.show', $festivity) }}" class="btn btn-primary btn-sm flex-fill">
                                        <i class="bi bi-eye me-1"></i>View
                                    </a>
                                    @auth
                                        @can('update', $festivity)
                                            <a href="{{ route('festivities.edit', $festivity) }}" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil me-1"></i>Edit
                                            </a>
                                        @endcan
                                        @can('delete', $festivity)
                                            <form method="POST" action="{{ route('festivities.destroy', $festivity) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" 
                                                        onclick="return confirm('Are you sure you want to delete this festivity?')">
                                                    <i class="bi bi-trash me-1"></i>Delete
                                                </button>
                                            </form>
                                        @endcan
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($festivities->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-calendar-x display-1 text-muted"></i>
                <h3 class="mt-3 text-muted">No festivities found</h3>
                <p class="text-muted">Be the first to add a festivity!</p>
                @auth
                    @can('create', App\Models\Festivity::class)
                        @if(!auth()->user()->isTownHall())
                            <a href="{{ route('festivities.create') }}" class="btn btn-primary btn-custom mt-3">
                                <i class="bi bi-plus-circle me-1"></i>Add First Festivity
                            </a>
                        @endif
                    @endcan
                @endauth
            </div>
        @endif
    </div>
</x-app-layout>
