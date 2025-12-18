<div>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="row">
            <div class="col-md-7">
                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">
                        <i class="bi bi-person me-1"></i>Nombre
                    </label>
                    <input id="name" name="name" type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name', $user->name) }}" 
                           required autofocus autocomplete="name">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label fw-bold">
                        <i class="bi bi-envelope me-1"></i>{{ __('Email') }}
                    </label>
                    <input id="email" name="email" type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email', $user->email) }}" 
                           required autocomplete="username">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="alert alert-warning mt-2">
                            <p class="mb-2">
                                {{ __('Your email address is unverified.') }}
                            </p>
                            <button form="send-verification" class="btn btn-sm btn-warning">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>

                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 mb-0 text-success">
                                    <i class="bi bi-check-circle me-1"></i>
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="province" class="form-label fw-bold">
                        <i class="bi bi-geo-alt me-1"></i>Provincia (opcional)
                    </label>
                    @if($user->isTownHall())
                        <input type="text" 
                               class="form-control" 
                               value="{{ $user->province ?? 'No asignada' }}" 
                               disabled
                               readonly>
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>Los usuarios con rol de ayuntamiento no pueden modificar su provincia.
                        </div>
                    @else
                        <select id="province" name="province" 
                                class="form-select @error('province') is-invalid @enderror">
                            <option value="">Seleccionar provincia</option>
                            @foreach(config('provinces.provinces') as $province)
                                <option value="{{ $province }}" {{ old('province', $user->province) == $province ? 'selected' : '' }}>
                                    {{ $province }}
                                </option>
                            @endforeach
                        </select>
                        @error('province')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    @endif
                </div>
            </div>

            <div class="col-md-5">
                <div class="mb-4">
                    <label class="form-label fw-bold">
                        <i class="bi bi-camera me-1"></i>Foto de Perfil
                    </label>
                    <div class="d-flex flex-column align-items-start gap-3">
                        <div class="position-relative">
                            @if($user->photo)
                                <img src="{{ $user->getPhotoUrl() }}" 
                                     alt="{{ $user->name }}" 
                                     class="rounded-circle border border-3 border-primary"
                                     style="width: 120px; height: 120px; object-fit: cover;"
                                     id="photoPreview">
                            @else
                                <img src="{{ $user->getPhotoUrl() }}" 
                                     alt="{{ $user->name }}" 
                                     class="rounded-circle border border-3 border-primary"
                                     style="width: 120px; height: 120px; object-fit: cover;"
                                     id="photoPreview">
                            @endif
                        </div>
                        <div class="w-100">
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
                            @if($user->photo)
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="remove_photo" id="remove_photo" value="1">
                                    <label class="form-check-label text-danger" for="remove_photo">
                                        <i class="bi bi-trash me-1"></i>Eliminar foto actual
                                    </label>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function previewPhoto(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('photoPreview').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        </script>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>Guardar
            </button>

            @if (session('status') === 'profile-updated')
                <div class="alert alert-success mb-0 py-2 px-3" 
                     x-data="{ show: true }"
                     x-show="show"
                     x-transition
                     x-init="setTimeout(() => show = false, 2000)">
                    <i class="bi bi-check-circle me-1"></i>{{ __('Saved.') }}
                </div>
            @endif
        </div>
    </form>
</div>
