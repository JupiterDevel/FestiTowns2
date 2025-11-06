<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="display-6 fw-bold text-primary mb-0">
                <i class="bi bi-map me-2"></i>Localities
            </h1>
            @auth
                @can('create', App\Models\Locality::class)
                    <a href="{{ route('localities.create') }}" class="btn btn-primary btn-custom">
                        <i class="bi bi-plus-circle me-1"></i>Add Locality
                    </a>
                @else
                    <div class="alert alert-warning">
                        <small>You don't have permission to create localities. Current role: {{ auth()->user()->role }} (Only administrators can create localities)</small>
                    </div>
                @endcan
            @else
                <div class="alert alert-info">
                    <small>Please <a href="{{ route('login') }}">login</a> to add localities</small>
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
            @foreach($localities as $locality)
                <div class="col-md-6 col-lg-4">
                    <div class="card locality-card card-hover h-100">
                        @if($locality->photos && count($locality->photos) > 0)
                            <img src="{{ $locality->photos[0] }}" class="card-img-top" alt="{{ $locality->name }}">
                        @else
                            <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                                <div class="text-center text-muted">
                                    <i class="bi bi-image display-4"></i>
                                    <p class="mt-2 mb-0">No image available</p>
                                </div>
                            </div>
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $locality->name }}</h5>
                            <p class="text-muted mb-2">
                                <i class="bi bi-geo-alt me-1"></i>{{ $locality->address }}
                                @if($locality->province)
                                    <br><small><i class="bi bi-map me-1"></i>{{ $locality->province }}</small>
                                @endif
                            </p>
                            <p class="card-text flex-grow-1">{{ Str::limit($locality->description, 120) }}</p>
                            
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-success">
                                        <i class="bi bi-calendar-event me-1"></i>
                                        {{ $locality->festivities->count() }} 
                                        {{ Str::plural('festivity', $locality->festivities->count()) }}
                                    </span>
                                </div>
                                
                                <div class="d-flex gap-2 flex-wrap">
                                    <a href="{{ route('localities.show', $locality) }}" class="btn btn-primary btn-sm flex-fill">
                                        <i class="bi bi-eye me-1"></i>View
                                    </a>
                                    @auth
                                        @can('update', $locality)
                                            <a href="{{ route('localities.edit', $locality) }}" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil me-1"></i>Edit
                                            </a>
                                        @endcan
                                        @can('delete', $locality)
                                            <form method="POST" action="{{ route('localities.destroy', $locality) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" 
                                                        onclick="return confirm('Are you sure you want to delete this locality?')">
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

        @if($localities->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-map display-1 text-muted"></i>
                <h3 class="mt-3 text-muted">No localities found</h3>
                <p class="text-muted">Be the first to add a locality!</p>
                @auth
                    @can('create', App\Models\Locality::class)
                        <a href="{{ route('localities.create') }}" class="btn btn-primary btn-custom mt-3">
                            <i class="bi bi-plus-circle me-1"></i>Add First Locality
                        </a>
                    @endcan
                @endauth
            </div>
        @endif
    </div>
</x-app-layout>
