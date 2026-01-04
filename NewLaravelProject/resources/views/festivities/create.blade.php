<x-app-layout>
    <div class="container" style="padding-top: 2rem; padding-bottom: 2rem;">
        {{-- Session messages handled by toast system --}}
        
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="form-card">
                    <div class="form-header">
                        @auth
                            @if(auth()->user()->isVisitor())
                                <h2 class="form-title">
                                    <i class="bi bi-lightbulb me-2"></i>Sugerir una festividad
                                </h2>
                            @else
                                <h2 class="form-title">
                                    <i class="bi bi-plus-circle me-2"></i>Crear Festividad
                                </h2>
                            @endif
                        @else
                            <h2 class="form-title">
                                <i class="bi bi-plus-circle me-2"></i>Crear Festividad
                            </h2>
                        @endauth
                    </div>
                    
                    <div class="form-body">
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

                            @auth
                                @if(auth()->user()->isVisitor())
                                    <div class="alert alert-info-custom mb-4">
                                        <i class="bi bi-info-circle me-2"></i>
                                        <strong>Nota:</strong> Tu sugerencia será revisada por un administrador antes de ser publicada en la plataforma.
                                    </div>
                                @endif
                            @endauth
                            
                            <div class="form-actions">
                                <a href="{{ route('festivities.index') }}" class="btn btn-secondary-custom">
                                    <i class="bi bi-arrow-left me-1"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary-custom">
                                    @auth
                                        @if(auth()->user()->isVisitor())
                                            <i class="bi bi-lightbulb me-1"></i>Sugerir Festividad
                                        @else
                                            <i class="bi bi-plus-circle me-1"></i>Crear Festividad
                                        @endif
                                    @else
                                        <i class="bi bi-plus-circle me-1"></i>Crear Festividad
                                    @endauth
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        body {
            background-image: url('/storage/background.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-color: #f8f9fa;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: white;
            opacity: 0.5;
            z-index: -1;
            pointer-events: none;
        }
        main {
            background-color: transparent;
        }
        main.py-4 {
            padding-top: 0 !important;
        }

        .form-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.25);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .form-header {
            background: linear-gradient(135deg, #FEB101 0%, #F59E0B 100%);
            padding: 2rem;
            text-align: center;
        }

        .form-title {
            color: white;
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .form-body {
            padding: 2.5rem;
        }

        .form-label {
            color: #1F2937;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }

        .form-label i {
            color: #FEB101;
            margin-right: 0.5rem;
        }

        .form-control,
        .form-select {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.2s ease;
            font-size: 0.95rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #FEB101;
            box-shadow: 0 0 0 3px rgba(254, 177, 1, 0.1);
            outline: none;
        }

        .form-control::placeholder {
            color: #9ca3af;
        }

        .form-text {
            font-size: 0.85rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }

        .form-text i {
            color: #FEB101;
        }

        .alert-info-custom {
            background: linear-gradient(120deg, rgba(31,164,169,0.08), #EFF6FF);
            border: 2px solid #BFDBFE;
            border-left: 4px solid #1FA4A9;
            color: #1F2937;
            border-radius: 8px;
            padding: 1rem 1.25rem;
        }

        .alert-info-custom i {
            color: #1FA4A9;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }

        .btn-primary-custom,
        .btn-secondary-custom {
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s ease;
            border: none;
            display: inline-flex;
            align-items: center;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #FEB101 0%, #F59E0B 100%);
            color: white;
            box-shadow: 0 2px 6px rgba(254, 177, 1, 0.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(254, 177, 1, 0.4);
            color: white;
        }

        .btn-secondary-custom {
            background: #6b7280;
            color: white;
        }

        .btn-secondary-custom:hover {
            background: #4b5563;
            transform: translateY(-2px);
            color: white;
        }

        #photos-preview img {
            border-radius: 8px;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .is-invalid {
            border-color: #dc3545;
        }

        .is-invalid:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
        }

        @media (max-width: 768px) {
            .form-body {
                padding: 1.5rem;
            }

            .form-header {
                padding: 1.5rem;
            }

            .form-title {
                font-size: 1.5rem;
            }

            .form-actions {
                flex-direction: column-reverse;
            }

            .btn-primary-custom,
            .btn-secondary-custom {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

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
