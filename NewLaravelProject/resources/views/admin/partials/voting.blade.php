{{-- Session messages handled by toast system --}}

<div class="card">
    <div class="card-body">
        <h5 class="card-title mb-4">
            <i class="bi bi-heart-fill text-danger me-2"></i>Control de Votaciones
        </h5>
        
        <div class="row g-3 mb-4">
            <div class="col-md-12">
                <div class="alert alert-info d-flex align-items-center" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    <div>
                        <strong>Estado actual:</strong> 
                        <span id="voting-status" class="fw-bold">
                            @php
                                $votingEnabled = \Illuminate\Support\Facades\Cache::get('voting_enabled', true);
                            @endphp
                            @if($votingEnabled)
                                <span class="text-success">Votaciones Habilitadas</span>
                            @else
                                <span class="text-danger">Votaciones Deshabilitadas</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-4">
                <form method="POST" action="{{ route('admin.voting.enable') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success btn-lg w-100" id="play-btn">
                        <i class="bi bi-play-fill me-2"></i>Play
                    </button>
                </form>
            </div>
            <div class="col-md-4">
                <form method="POST" action="{{ route('admin.voting.disable') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-warning btn-lg w-100" id="pause-btn">
                        <i class="bi bi-pause-fill me-2"></i>Pause
                    </button>
                </form>
            </div>
            <div class="col-md-4">
                <button type="button" class="btn btn-danger btn-lg w-100" data-bs-toggle="modal" data-bs-target="#resetVotesModal">
                    <i class="bi bi-arrow-counterclockwise me-2"></i>Reiniciar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Gestión de Mensaje Informativo -->
<div class="card mt-4">
    <div class="card-body">
        <h5 class="card-title mb-4">
            <i class="bi bi-chat-text me-2"></i>Mensaje Informativo - Las Más Votadas
        </h5>
        
        <p class="text-muted mb-3">
            Este mensaje se mostrará en la página "Las Más Votadas" junto a las reglas de votación. 
            Puedes usarlo para comunicar información importante a los usuarios.
        </p>
        
        <form method="POST" action="{{ route('admin.voting.update-message') }}">
            @csrf
            <div class="mb-3">
                <label for="voting-message" class="form-label fw-bold">Mensaje:</label>
                @php
                    $currentMessage = \Illuminate\Support\Facades\Cache::get('voting_info_message', '');
                @endphp
                <textarea 
                    class="form-control" 
                    id="voting-message" 
                    name="message" 
                    rows="4" 
                    placeholder="Escribe aquí el mensaje que quieres mostrar en la página 'Las Más Votadas'..."
                >{{ old('message', $currentMessage) }}</textarea>
                <small class="form-text text-muted">
                    Puedes usar HTML básico para formatear el texto (por ejemplo: &lt;strong&gt;, &lt;em&gt;, &lt;br&gt;).
                </small>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>Guardar Mensaje
                </button>
                @if(\Illuminate\Support\Facades\Cache::has('voting_info_message'))
                    <form method="POST" action="{{ route('admin.voting.clear-message') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar el mensaje?')">
                            <i class="bi bi-trash me-1"></i>Eliminar Mensaje
                        </button>
                    </form>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Modal de confirmación para reiniciar votos -->
<div class="modal fade" id="resetVotesModal" tabindex="-1" aria-labelledby="resetVotesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetVotesModalLabel">
                    <i class="bi bi-exclamation-triangle text-warning me-2"></i>Confirmar Reinicio de Votos
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">
                    ¿Estás seguro de que deseas reiniciar todas las votaciones?
                </p>
                <div class="alert alert-warning mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Advertencia:</strong> Esta acción eliminará todos los votos de todas las festividades. 
                    Esta operación no se puede deshacer.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Cancelar
                </button>
                <form method="POST" action="{{ route('admin.voting.reset') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-check-circle me-1"></i>Confirmar Reinicio
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

