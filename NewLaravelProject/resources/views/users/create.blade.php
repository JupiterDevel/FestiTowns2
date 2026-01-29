<x-app-layout>
    <x-slot name="header">
        <h1 class="display-6 fw-bold text-primary mb-0">
            <i class="bi bi-person-plus me-2"></i>Crear Usuario
        </h1>
    </x-slot>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- Photo Upload -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-camera me-1"></i>Foto de Perfil (opcional)
                                </label>
                                <div class="d-flex align-items-center gap-4">
                                    <div class="position-relative">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center border border-3 border-primary"
                                             style="width: 120px; height: 120px;"
                                             id="photoPreviewPlaceholder">
                                            <i class="bi bi-person-fill" style="font-size: 3rem;"></i>
                                        </div>
                                        <img src="" 
                                             alt="Preview" 
                                             class="rounded-circle border border-3 border-primary d-none"
                                             style="width: 120px; height: 120px; object-fit: cover;"
                                             id="photoPreview">
                                    </div>
                                    <div class="flex-grow-1">
                                        <input type="file" 
                                               class="form-control @error('photo') is-invalid @enderror" 
                                               id="photo" 
                                               name="photo" 
                                               accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                               onchange="previewPhoto(this)">
                                        @error('photo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            Formatos permitidos: JPEG, PNG, GIF, WEBP. Tamaño máximo: 5MB
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Nombre</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" required>
                            </div>

                            <div class="mb-3">
                                <label for="role" class="form-label">Rol</label>
                                <select class="form-select @error('role') is-invalid @enderror" 
                                        id="role" name="role" required>
                                    <option value="">Seleccionar rol</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="townhall" {{ old('role') == 'townhall' ? 'selected' : '' }}>Ayuntamiento</option>
                                    <option value="visitor" {{ old('role') == 'visitor' ? 'selected' : '' }}>Visitante</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="locality_id" class="form-label">Localidad</label>
                                <select class="form-select @error('locality_id') is-invalid @enderror" 
                                        id="locality_id" name="locality_id">
                                    <option value="">Seleccionar localidad (opcional)</option>
                                    @foreach($localities as $locality)
                                        <option value="{{ $locality->id }}" {{ old('locality_id') == $locality->id ? 'selected' : '' }}>
                                            {{ $locality->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('locality_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="province" class="form-label">Provincia (opcional)</label>
                                <select class="form-select @error('province') is-invalid @enderror" 
                                        id="province" name="province">
                                    <option value="">Seleccionar provincia</option>
                                    @foreach(config('provinces.provinces') as $province)
                                        <option value="{{ $province }}" {{ old('province') == $province ? 'selected' : '' }}>
                                            {{ $province }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('province')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <script>
                            function previewPhoto(input) {
                                const preview = document.getElementById('photoPreview');
                                const placeholder = document.getElementById('photoPreviewPlaceholder');
                                
                                if (input.files && input.files[0]) {
                                    const reader = new FileReader();
                                    reader.onload = function(e) {
                                        preview.src = e.target.result;
                                        preview.classList.remove('d-none');
                                        placeholder.classList.add('d-none');
                                    };
                                    reader.readAsDataURL(input.files[0]);
                                } else {
                                    preview.classList.add('d-none');
                                    placeholder.classList.remove('d-none');
                                }
                            }
                            </script>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i>Crear Usuario
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


