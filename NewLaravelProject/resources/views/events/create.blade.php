<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="display-6 fw-bold text-primary mb-0">
                <i class="bi bi-plus-circle me-2"></i>Crear Nuevo Evento
            </h1>
            <div class="d-flex gap-2">
                <a href="{{ route('events.index', $festivity) }}" class="btn btn-outline-secondary btn-custom">
                    <i class="bi bi-arrow-left me-1"></i>Volver a Eventos
                </a>
            </div>
        </div>
    </x-slot>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="mb-4">
                            <h4 class="text-muted">Festividad: <strong>{{ $festivity->name }}</strong></h4>
                            <p class="text-muted mb-0">{{ $festivity->locality->name }}</p>
                        </div>

                        <form method="POST" action="{{ route('events.store', $festivity) }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">
                                    <i class="bi bi-calendar-event me-1"></i>Nombre del Evento *
                                </label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold">
                                    <i class="bi bi-chat-text me-1"></i>Descripción
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="4" 
                                          placeholder="Describe el evento...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="location" class="form-label fw-bold">
                                    <i class="bi bi-geo-alt me-1"></i>Ubicación
                                </label>
                                <input type="text" 
                                       class="form-control @error('location') is-invalid @enderror" 
                                       id="location" 
                                       name="location" 
                                       value="{{ old('location') }}" 
                                       placeholder="Ej: Plaza Mayor, Calle Principal...">
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="start_time" class="form-label fw-bold">
                                            <i class="bi bi-clock me-1"></i>Hora de Inicio
                                        </label>
                                        <input type="datetime-local" 
                                               class="form-control @error('start_time') is-invalid @enderror" 
                                               id="start_time" 
                                               name="start_time" 
                                               value="{{ old('start_time') }}">
                                        @error('start_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="end_time" class="form-label fw-bold">
                                            <i class="bi bi-clock-fill me-1"></i>Hora de Fin
                                        </label>
                                        <input type="datetime-local" 
                                               class="form-control @error('end_time') is-invalid @enderror" 
                                               id="end_time" 
                                               name="end_time" 
                                               value="{{ old('end_time') }}">
                                        @error('end_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info" role="alert">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Nota:</strong> Las horas de inicio y fin son opcionales. Los eventos sin horario definido aparecerán al principio de la lista.
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('events.index', $festivity) }}" class="btn btn-outline-secondary btn-custom">
                                    <i class="bi bi-x-circle me-1"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary btn-custom">
                                    <i class="bi bi-check-circle me-1"></i>Crear Evento
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

