<div>
    <p class="text-muted mb-4">
        {{ __('Ensure your account is using a long, random password to stay secure.') }}
    </p>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="mb-3">
            <label for="update_password_current_password" class="form-label fw-bold">
                <i class="bi bi-lock me-1"></i>{{ __('Current Password') }}
            </label>
            <input id="update_password_current_password" name="current_password" type="password" 
                   class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" 
                   autocomplete="current-password" placeholder="••••••••">
            @error('current_password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="update_password_password" class="form-label fw-bold">
                <i class="bi bi-key me-1"></i>{{ __('New Password') }}
            </label>
            <input id="update_password_password" name="password" type="password" 
                   class="form-control @error('password', 'updatePassword') is-invalid @enderror" 
                   autocomplete="new-password" placeholder="••••••••">
            @error('password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="update_password_password_confirmation" class="form-label fw-bold">
                <i class="bi bi-key-fill me-1"></i>{{ __('Confirm Password') }}
            </label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                   class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" 
                   autocomplete="new-password" placeholder="••••••••">
            @error('password_confirmation', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>{{ __('Save') }}
            </button>

            @if (session('status') === 'password-updated')
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
