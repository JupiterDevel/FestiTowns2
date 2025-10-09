<x-app-layout>
    <x-slot name="header">
        <h1 class="display-6 fw-bold text-primary mb-0">
            <i class="bi bi-pencil me-2"></i>Edit Festivity: {{ $festivity->name }}
        </h1>
    </x-slot>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-body">
                        <form method="POST" action="{{ route('festivities.update', $festivity) }}">
                    @csrf
                    @method('PUT')
                    
                            <div class="mb-3">
                                <label for="locality_id" class="form-label fw-bold">
                                    <i class="bi bi-geo-alt me-1"></i>Locality
                                </label>
                                <select name="locality_id" id="locality_id" 
                                        class="form-select @error('locality_id') is-invalid @enderror" required>
                                    <option value="">Select a locality</option>
                                    @foreach($localities as $locality)
                                        <option value="{{ $locality->id }}" 
                                                {{ old('locality_id', $festivity->locality_id) == $locality->id ? 'selected' : '' }}>
                                            {{ $locality->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('locality_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">
                                    <i class="bi bi-calendar-event me-1"></i>Festivity Name
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name', $festivity->name) }}" 
                                       class="form-control @error('name') is-invalid @enderror" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="start_date" class="form-label fw-bold">
                                        <i class="bi bi-calendar-check me-1"></i>Start Date
                                    </label>
                                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $festivity->start_date->format('Y-m-d')) }}" 
                                           class="form-control @error('start_date') is-invalid @enderror" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="end_date" class="form-label fw-bold">
                                        <i class="bi bi-calendar-x me-1"></i>End Date <small class="text-muted">(optional)</small>
                                    </label>
                                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $festivity->end_date ? $festivity->end_date->format('Y-m-d') : '') }}" 
                                           class="form-control @error('end_date') is-invalid @enderror">
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="description" class="form-label fw-bold">
                                    <i class="bi bi-chat-text me-1"></i>Description
                                </label>
                                <textarea name="description" id="description" rows="4" 
                                          class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $festivity->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end gap-3">
                                <a href="{{ route('festivities.show', $festivity) }}" class="btn btn-secondary btn-custom">
                                    <i class="bi bi-arrow-left me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary btn-custom">
                                    <i class="bi bi-check-circle me-1"></i>Update Festivity
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
