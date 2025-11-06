<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="display-6 fw-bold text-primary mb-0">
                <i class="bi bi-calendar-check me-2"></i>{{ $event->name }}
            </h1>
            <div class="d-flex gap-2">
                <a href="{{ route('events.index', $festivity) }}" class="btn btn-outline-secondary btn-custom">
                    <i class="bi bi-arrow-left me-1"></i>Volver a Eventos
                </a>
                <a href="{{ route('events.edit', [$festivity, $event]) }}" class="btn btn-warning btn-custom">
                    <i class="bi bi-pencil me-1"></i>Editar
                </a>
                <form method="POST" action="{{ route('events.destroy', [$festivity, $event]) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-custom" 
                            onclick="return confirm('¿Estás seguro de que quieres eliminar este evento?')">
                        <i class="bi bi-trash me-1"></i>Eliminar
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="mb-4">
                            <h4 class="text-muted">Festividad: 
                                <a href="{{ route('festivities.show', $festivity) }}" class="text-decoration-none">
                                    <strong>{{ $festivity->name }}</strong>
                                </a>
                            </h4>
                            <p class="text-muted mb-0">
                                <i class="bi bi-geo-alt me-1"></i>{{ $festivity->locality->name }}
                            </p>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="card-title h4 fw-bold mb-3">{{ $event->name }}</h3>
                                
                                @if($event->start_time || $event->end_time)
                                    <div class="mb-4">
                                        @if($event->start_time)
                                            <p class="text-muted mb-2">
                                                <i class="bi bi-clock me-2"></i>
                                                <strong>Hora de Inicio:</strong><br>
                                                <span class="fs-5">{{ $event->start_time->format('d/m/Y H:i') }}</span>
                                            </p>
                                        @endif
                                        @if($event->end_time)
                                            <p class="text-muted mb-2">
                                                <i class="bi bi-clock-fill me-2"></i>
                                                <strong>Hora de Fin:</strong><br>
                                                <span class="fs-5">{{ $event->end_time->format('d/m/Y H:i') }}</span>
                                            </p>
                                        @endif
                                    </div>
                                @else
                                    <div class="mb-4">
                                        <span class="badge bg-secondary fs-6">
                                            <i class="bi bi-question-circle me-1"></i>Sin horario definido
                                        </span>
                                    </div>
                                @endif

                                @if($event->location)
                                    <p class="text-muted mb-3">
                                        <i class="bi bi-geo-alt me-2"></i>
                                        <strong>Ubicación:</strong><br>
                                        <span class="fs-5">{{ $event->location }}</span>
                                    </p>
                                @endif
                            </div>
                            
                            <div class="col-md-6">
                                @if($event->description)
                                    <h4 class="h5 fw-bold mb-3">Descripción</h4>
                                    <p class="card-text">{{ $event->description }}</p>
                                @else
                                    <div class="text-center py-4">
                                        <i class="bi bi-chat-text display-4 text-muted"></i>
                                        <p class="text-muted mt-3">No hay descripción disponible para este evento.</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                <small>
                                    <i class="bi bi-calendar-plus me-1"></i>
                                    Creado el {{ $event->created_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('events.edit', [$festivity, $event]) }}" class="btn btn-warning btn-custom">
                                    <i class="bi bi-pencil me-1"></i>Editar Evento
                                </a>
                                <form method="POST" action="{{ route('events.destroy', [$festivity, $event]) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-custom" 
                                            onclick="return confirm('¿Estás seguro de que quieres eliminar este evento?')">
                                        <i class="bi bi-trash me-1"></i>Eliminar Evento
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

