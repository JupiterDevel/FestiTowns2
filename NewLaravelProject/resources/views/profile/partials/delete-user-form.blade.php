<div>
    <p class="text-muted mb-4">
        Una vez elimines tu cuenta, todos tus datos y recursos se borrarán de forma permanente. Descarga antes cualquier información que quieras conservar.
    </p>

    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmUserDeletion">
        <i class="bi bi-trash me-1"></i>Eliminar cuenta
    </button>

    <!-- Bootstrap Modal -->
    <div class="modal fade" id="confirmUserDeletion" tabindex="-1" aria-labelledby="confirmUserDeletionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-danger">
                    <h5 class="modal-title text-danger fw-bold" id="confirmUserDeletionLabel">
                        <i class="bi bi-exclamation-triangle me-2"></i>Eliminar cuenta
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')
                    
                    <div class="modal-body">
                        <p class="fw-bold mb-3">
                            ¿Seguro que quieres eliminar tu cuenta?
                        </p>
                        <p class="text-muted mb-4">
                            Una vez eliminada, todos tus datos se borrarán de forma permanente. Introduce tu contraseña para confirmar que deseas eliminar tu cuenta.
                        </p>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">
                                <i class="bi bi-lock me-1"></i>Contraseña
                            </label>
                            <input id="password" name="password" type="password" 
                                   class="form-control @error('password', 'userDeletion') is-invalid @enderror" 
                                   placeholder="Contraseña" required>
                            @error('password', 'userDeletion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i>Eliminar cuenta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($errors->userDeletion->isNotEmpty())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('confirmUserDeletion'));
                modal.show();
            });
        </script>
    @endif
</div>
