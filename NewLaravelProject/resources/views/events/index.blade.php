<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="display-6 fw-bold text-primary mb-0">
                <i class="bi bi-calendar-event me-2"></i>Eventos de {{ $festivity->name }}
            </h1>
            <div class="d-flex gap-2">
                <a href="{{ route('festivities.show', $festivity) }}" class="btn btn-outline-secondary btn-custom">
                    <i class="bi bi-arrow-left me-1"></i>Volver a Festividad
                </a>
                <a href="{{ route('events.create', $festivity) }}" class="btn btn-primary btn-custom">
                    <i class="bi bi-plus-circle me-1"></i>Nuevo Evento
                </a>
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

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($events->count() > 0)
            <div class="row">
                @foreach($events as $event)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title fw-bold">
                                    <i class="bi bi-calendar-check me-2"></i>{{ $event->name }}
                                </h5>
                                
                                @if($event->start_time || $event->end_time)
                                    <div class="mb-3">
                                        @if($event->start_time)
                                            <p class="text-muted mb-1">
                                                <i class="bi bi-clock me-2"></i>
                                                <strong>Inicio:</strong> {{ $event->start_time->format('d/m/Y H:i') }}
                                            </p>
                                        @endif
                                        @if($event->end_time)
                                            <p class="text-muted mb-1">
                                                <i class="bi bi-clock-fill me-2"></i>
                                                <strong>Fin:</strong> {{ $event->end_time->format('d/m/Y H:i') }}
                                            </p>
                                        @endif
                                    </div>
                                @else
                                    <div class="mb-3">
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-question-circle me-1"></i>Sin horario definido
                                        </span>
                                    </div>
                                @endif

                                @if($event->location)
                                    <p class="text-muted mb-2">
                                        <i class="bi bi-geo-alt me-2"></i><strong>Ubicación:</strong> {{ $event->location }}
                                    </p>
                                @endif

                                @if($event->description)
                                    <p class="card-text text-muted">{{ Str::limit($event->description, 100) }}</p>
                                @endif
                            </div>
                            
                            <div class="card-footer bg-transparent">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('events.show', [$festivity, $event]) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-eye me-1"></i>Ver Detalles
                                    </a>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('events.edit', [$festivity, $event]) }}" class="btn btn-outline-warning btn-sm">
                                            <i class="bi bi-pencil me-1"></i>Editar
                                        </a>
                                        <form method="POST" action="{{ route('events.destroy', [$festivity, $event]) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                    onclick="return confirm('¿Estás seguro de que quieres eliminar este evento?')">
                                                <i class="bi bi-trash me-1"></i>Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $events->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-calendar-x display-1 text-muted"></i>
                <h3 class="mt-3 text-muted">No hay eventos programados</h3>
                <p class="text-muted">Esta festividad aún no tiene eventos programados.</p>
                <a href="{{ route('events.create', $festivity) }}" class="btn btn-primary btn-custom">
                    <i class="bi bi-plus-circle me-1"></i>Crear Primer Evento
                </a>
            </div>
        @endif
    </div>
</x-app-layout>

