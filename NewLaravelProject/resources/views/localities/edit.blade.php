<x-app-layout>
    <x-slot name="header">
        <h1 class="display-6 fw-bold text-primary mb-0">
            <i class="bi bi-pencil me-2"></i>Edit Locality: {{ $locality->name }}
        </h1>
    </x-slot>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-body">
                        <form method="POST" action="{{ route('localities.update', $locality) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">
                                    <i class="bi bi-building me-1"></i>Name
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name', $locality->name) }}" 
                                       class="form-control @error('name') is-invalid @enderror" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label fw-bold">
                                    <i class="bi bi-geo-alt me-1"></i>Address
                                </label>
                                <input type="text" name="address" id="address" value="{{ old('address', $locality->address) }}" 
                                       class="form-control @error('address') is-invalid @enderror" required>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="province" class="form-label fw-bold">
                                    <i class="bi bi-map me-1"></i>Province
                                </label>
                                <select name="province" id="province" 
                                        class="form-select @error('province') is-invalid @enderror" required>
                                    <option value="">Select a province</option>
                                    @foreach(config('provinces.provinces') as $province)
                                        <option value="{{ $province }}" 
                                                {{ old('province', $locality->province) == $province ? 'selected' : '' }}>
                                            {{ $province }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('province')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold">
                                    <i class="bi bi-info-circle me-1"></i>Description
                                </label>
                                <textarea name="description" id="description" rows="4" 
                                          class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $locality->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="places_of_interest" class="form-label fw-bold">
                                    <i class="bi bi-star me-1"></i>Places of Interest
                                </label>
                                <textarea name="places_of_interest" id="places_of_interest" rows="4" 
                                          class="form-control @error('places_of_interest') is-invalid @enderror" required>{{ old('places_of_interest', $locality->places_of_interest) }}</textarea>
                                @error('places_of_interest')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="monuments" class="form-label fw-bold">
                                    <i class="bi bi-building me-1"></i>Monuments
                                </label>
                                <textarea name="monuments" id="monuments" rows="4" 
                                          class="form-control @error('monuments') is-invalid @enderror" required>{{ old('monuments', $locality->monuments) }}</textarea>
                                @error('monuments')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-images me-1"></i>Fotos actuales
                                </label>
                                @if($locality->photos && count($locality->photos) > 0)
                                    <div class="row g-2 mb-3" id="existing-photos">
                                        @foreach($locality->photos as $index => $photo)
                                            <div class="col-md-3 col-sm-4 col-6 existing-photo-item" data-photo-index="{{ $index }}">
                                                <div class="position-relative">
                                                    <img src="{{ filter_var($photo, FILTER_VALIDATE_URL) ? $photo : asset($photo) }}" 
                                                         class="img-thumbnail" 
                                                         style="width: 100%; height: 150px; object-fit: cover;">
                                                    <button type="button" 
                                                            class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" 
                                                            onclick="removeExistingPhoto({{ $index }})">
                                                        <i class="bi bi-x"></i>
                                                    </button>
                                                </div>
                                                <input type="hidden" name="existing_photos[]" value="{{ $photo }}" id="existing_photo_{{ $index }}">
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">No hay fotos actuales</p>
                                @endif
                            </div>

                            <div class="mb-4">
                                <label for="photos" class="form-label fw-bold">
                                    <i class="bi bi-images me-1"></i>Añadir más fotos (máximo {{ 10 - ($locality->photos ? count($locality->photos) : 0) }})
                                </label>
                                <input type="file" 
                                       name="photos[]" 
                                       id="photos" 
                                       class="form-control @error('photos.*') is-invalid @enderror"
                                       accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                       multiple>
                                <small class="form-text text-muted">
                                    Puedes seleccionar hasta {{ 10 - ($locality->photos ? count($locality->photos) : 0) }} imágenes más. Formatos permitidos: JPEG, PNG, JPG, GIF, WEBP. Tamaño máximo por imagen: 5MB
                                </small>
                                @error('photos.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="photos-preview" class="mt-3 row g-2"></div>
                            </div>

                            <div class="d-flex justify-content-end gap-3">
                                <a href="{{ route('localities.show', $locality) }}" class="btn btn-secondary btn-custom">
                                    <i class="bi bi-arrow-left me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary btn-custom">
                                    <i class="bi bi-check-circle me-1"></i>Update Locality
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const maxPhotos = 10;
        const existingPhotosCount = {{ $locality->photos ? count($locality->photos) : 0 }};
        let removedExistingPhotos = [];
        
        document.getElementById('photos').addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            const preview = document.getElementById('photos-preview');
            preview.innerHTML = '';
            
            const currentTotal = existingPhotosCount - removedExistingPhotos.length + files.length;
            if (currentTotal > maxPhotos) {
                alert(`Solo puedes tener un máximo de ${maxPhotos} fotos en total`);
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
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" onclick="removeNewPhoto(${index})">
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
        
        function removeExistingPhoto(index) {
            const item = document.querySelector(`.existing-photo-item[data-photo-index="${index}"]`);
            if (item) {
                removedExistingPhotos.push(index);
                item.remove();
                updatePhotosInput();
            }
        }
        
        function removeNewPhoto(index) {
            const input = document.getElementById('photos');
            const dt = new DataTransfer();
            const files = Array.from(input.files);
            files.splice(index, 1);
            files.forEach(file => dt.items.add(file));
            input.files = dt.files;
            input.dispatchEvent(new Event('change'));
        }
        
        function updatePhotosInput() {
            const existingInputs = document.querySelectorAll('input[name="existing_photos[]"]');
            existingInputs.forEach((input, index) => {
                if (removedExistingPhotos.includes(parseInt(input.id.replace('existing_photo_', '')))) {
                    input.remove();
                }
            });
        }
    </script>
</x-app-layout>
