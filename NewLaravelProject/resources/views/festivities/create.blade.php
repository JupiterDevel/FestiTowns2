<x-app-layout>
    <x-slot name="header">
        <h1 class="display-6 fw-bold text-primary mb-0">
            <i class="bi bi-plus-circle me-2"></i>Create New Festivity
        </h1>
    </x-slot>

    <div class="container">
        {{-- Session messages handled by toast system --}}
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-body">
                        <form method="POST" action="{{ route('festivities.store') }}" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="locality_name" class="form-label fw-bold">
                                    <i class="bi bi-geo-alt me-1"></i>Localidad
                                </label>
                                <input type="text" name="locality_name" id="locality_name" 
                                       value="{{ old('locality_name', $locality->name ?? '') }}" 
                                       class="form-control @error('locality_name') is-invalid @enderror" required
                                       placeholder="Introduce el nombre de la localidad"
                                       {{ $locality ? 'readonly' : '' }}>
                                @if($locality)
                                    <small class="form-text text-muted">
                                        <i class="bi bi-info-circle me-1"></i>Localidad pre-seleccionada desde la página de detalle
                                    </small>
                                @endif
                                @error('locality_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="province" class="form-label fw-bold">
                                    <i class="bi bi-map me-1"></i>Provincia
                                </label>
                                <select name="province" id="province" 
                                        class="form-select @error('province') is-invalid @enderror" required
                                        {{ $locality ? 'disabled' : '' }}>
                                    <option value="">Selecciona una provincia</option>
                                    @foreach(config('provinces.provinces') as $province)
                                        <option value="{{ $province }}" 
                                                {{ old('province', $locality->province ?? '') == $province ? 'selected' : '' }}>
                                            {{ $province }}
                                        </option>
                                    @endforeach
                                </select>
                                @if($locality)
                                    <input type="hidden" name="province" value="{{ $locality->province }}">
                                    <small class="form-text text-muted">
                                        <i class="bi bi-info-circle me-1"></i>Provincia pre-seleccionada desde la página de detalle
                                    </small>
                                @endif
                                @error('province')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">
                                    <i class="bi bi-calendar-event me-1"></i>Festivity Name
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" 
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
                                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" 
                                           class="form-control @error('start_date') is-invalid @enderror" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="end_date" class="form-label fw-bold">
                                        <i class="bi bi-calendar-x me-1"></i>End Date <small class="text-muted">(optional)</small>
                                    </label>
                                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" 
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
                                          class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="google_maps_url" class="form-label fw-bold">
                                    <i class="bi bi-geo-alt-fill me-1"></i>Google Maps URL <small class="text-muted">(opcional)</small>
                                </label>
                                <input type="url" name="google_maps_url" id="google_maps_url" 
                                       value="{{ old('google_maps_url') }}" 
                                       class="form-control @error('google_maps_url') is-invalid @enderror"
                                       placeholder="https://maps.google.com/?q=40.7128,-74.0060">
                                <small class="form-text text-muted">
                                    <i class="bi bi-info-circle me-1"></i>Pega la URL de Google Maps de la ubicación de la festividad. Las coordenadas se extraerán automáticamente.
                                </small>
                                @error('google_maps_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="photos" class="form-label fw-bold">
                                    <i class="bi bi-images me-1"></i>Fotos (máximo 10)
                                </label>
                                <input type="file" 
                                       name="photos[]" 
                                       id="photos" 
                                       class="form-control @error('photos.*') is-invalid @enderror"
                                       accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                       multiple>
                                <small class="form-text text-muted">
                                    Puedes seleccionar hasta 10 imágenes. Formatos permitidos: JPEG, PNG, JPG, GIF, WEBP. Tamaño máximo por imagen: 5MB
                                </small>
                                @error('photos.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="photos-preview" class="mt-3 row g-2"></div>
                            </div>

                            <div class="d-flex justify-content-end gap-3">
                                <a href="{{ route('festivities.index') }}" class="btn btn-secondary btn-custom">
                                    <i class="bi bi-arrow-left me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary btn-custom">
                                    <i class="bi bi-plus-circle me-1"></i>Create Festivity
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('photos').addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            const preview = document.getElementById('photos-preview');
            preview.innerHTML = '';
            
            if (files.length > 10) {
                alert('Solo puedes subir un máximo de 10 imágenes');
                e.target.value = '';
                return;
            }
            
            files.forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const col = document.createElement('div');
                        col.className = 'col-md-3 col-sm-4 col-6';
                        col.innerHTML = `
                            <div class="position-relative">
                                <img src="${e.target.result}" class="img-thumbnail" style="width: 100%; height: 150px; object-fit: cover;">
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" onclick="removePhoto(${index})">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        `;
                        preview.appendChild(col);
                    }
                    reader.readAsDataURL(file);
                }
            });
        });
        
        function removePhoto(index) {
            const input = document.getElementById('photos');
            const dt = new DataTransfer();
            const files = Array.from(input.files);
            files.splice(index, 1);
            files.forEach(file => dt.items.add(file));
            input.files = dt.files;
            input.dispatchEvent(new Event('change'));
        }
    </script>
</x-app-layout>
