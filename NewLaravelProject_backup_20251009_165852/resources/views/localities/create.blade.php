<x-app-layout>
    <x-slot name="header">
        <h1 class="display-6 fw-bold text-primary mb-0">
            <i class="bi bi-plus-circle me-2"></i>Create New Locality
        </h1>
    </x-slot>

    <div class="container">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-body">
                        <form method="POST" action="{{ route('localities.store') }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">
                                    <i class="bi bi-building me-1"></i>Name
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                       class="form-control @error('name') is-invalid @enderror" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label fw-bold">
                                    <i class="bi bi-geo-alt me-1"></i>Address
                                </label>
                                <input type="text" name="address" id="address" value="{{ old('address') }}" 
                                       class="form-control @error('address') is-invalid @enderror" required>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold">
                                    <i class="bi bi-info-circle me-1"></i>Description
                                </label>
                                <textarea name="description" id="description" rows="4" 
                                          class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="places_of_interest" class="form-label fw-bold">
                                    <i class="bi bi-star me-1"></i>Places of Interest
                                </label>
                                <textarea name="places_of_interest" id="places_of_interest" rows="4" 
                                          class="form-control @error('places_of_interest') is-invalid @enderror" required>{{ old('places_of_interest') }}</textarea>
                                @error('places_of_interest')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="monuments" class="form-label fw-bold">
                                    <i class="bi bi-building me-1"></i>Monuments
                                </label>
                                <textarea name="monuments" id="monuments" rows="4" 
                                          class="form-control @error('monuments') is-invalid @enderror" required>{{ old('monuments') }}</textarea>
                                @error('monuments')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end gap-3">
                                <a href="{{ route('localities.index') }}" class="btn btn-secondary btn-custom">
                                    <i class="bi bi-arrow-left me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary btn-custom">
                                    <i class="bi bi-plus-circle me-1"></i>Create Locality
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
