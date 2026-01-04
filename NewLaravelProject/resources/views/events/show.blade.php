<x-app-layout>
    <!-- Header Section -->
    <div class="header-event-detail">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="header-content">
                        <h1 class="header-title">
                            <i class="bi bi-calendar-check me-2"></i>{{ $event->name }}
                        </h1>
                        <p class="context-info mt-2 mb-0">
                            <i class="bi bi-calendar-event me-1"></i>Evento de {{ $festivity->name }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="header-actions">
            <a href="{{ route('events.index', $festivity) }}" class="btn-back" title="Volver a Eventos">
                <i class="bi bi-arrow-left"></i>
            </a>
            @auth
                @php
                    $user = auth()->user();
                    $canEdit = false;
                    $canDelete = false;
                    if ($user->isAdmin()) {
                        $canEdit = true;
                        $canDelete = true;
                    } elseif ($user->isTownHall() && $user->locality_id && $festivity->locality_id === $user->locality_id) {
                        $canEdit = true;
                        $canDelete = true;
                    }
                @endphp
                @if($canEdit || $canDelete)
                    @if($canEdit)
                        <a href="{{ route('events.edit', [$festivity, $event]) }}" class="btn-edit" title="Editar Evento">
                            <i class="bi bi-pencil"></i>
                        </a>
                    @endif
                    @if($canDelete)
                        <form method="POST" action="{{ route('events.destroy', [$festivity, $event]) }}" class="d-inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este evento?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" title="Eliminar Evento">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    @endif
                @endif
            @endauth
        </div>
    </div>

    <div class="container" style="padding-top: 2rem; padding-bottom: 2rem;">

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card detail-event-card">
                    <div class="card-body">
                        <!-- Festivity Link Section -->
                        <div class="mb-4 pb-3 border-bottom">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div>
                                    <p class="text-muted mb-1 small">Festividad:</p>
                                    <a href="{{ route('festivities.show', $festivity) }}" class="text-decoration-none">
                                        <h4 class="mb-0" style="color: #FEB101; font-weight: 600;">
                                            {{ $festivity->name }}
                                        </h4>
                                    </a>
                                </div>
                                <div class="text-end">
                                    <p class="text-muted mb-0 small">
                                        <i class="bi bi-geo-alt-fill" style="color: #1FA4A9;"></i>
                                        {{ $festivity->locality->name ?? 'Sin localidad' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Event Details -->
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="event-title mb-4">{{ $event->name }}</h3>
                                
                                <!-- Time Information -->
                                @if($event->start_time || $event->end_time)
                                    <div class="detail-section mb-4">
                                        @if($event->start_time)
                                            <div class="detail-item mb-3">
                                                <div class="detail-icon">
                                                    <i class="bi bi-clock-fill"></i>
                                                </div>
                                                <div class="detail-content">
                                                    <p class="detail-label">Hora de Inicio</p>
                                                    <p class="detail-value">{{ $event->start_time->format('d/m/Y H:i') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if($event->end_time)
                                            <div class="detail-item mb-3">
                                                <div class="detail-icon">
                                                    <i class="bi bi-clock-fill"></i>
                                                </div>
                                                <div class="detail-content">
                                                    <p class="detail-label">Hora de Fin</p>
                                                    <p class="detail-value">{{ $event->end_time->format('d/m/Y H:i') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="detail-section mb-4">
                                        <span class="badge compact-badge" style="background-color: #6B7280; color: #FFFFFF; font-size: 0.875rem; padding: 0.5rem 1rem; border-radius: 8px;">
                                            <i class="bi bi-question-circle me-1"></i>Sin horario definido
                                        </span>
                                    </div>
                                @endif

                                <!-- Location -->
                                @if($event->location)
                                    <div class="detail-section mb-4">
                                        <div class="detail-item">
                                            <div class="detail-icon">
                                                <i class="bi bi-geo-alt-fill"></i>
                                            </div>
                                            <div class="detail-content">
                                                <p class="detail-label">Ubicación</p>
                                                <p class="detail-value">{{ $event->location }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Description -->
                                <div class="detail-section">
                                    @if($event->description)
                                        <h4 class="section-title mb-3">Descripción</h4>
                                        <div class="description-text">
                                            <p>{{ $event->description }}</p>
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="bi bi-chat-text display-4 text-muted"></i>
                                            <p class="text-muted mt-3">No hay descripción disponible para este evento.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Footer Section -->
                        <div class="mt-4 pt-4 border-top">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div class="text-muted small">
                                    <i class="bi bi-calendar-plus me-1"></i>
                                    Creado el {{ $event->created_at->format('d/m/Y H:i') }}
                                </div>
                                @auth
                                    @php
                                        $user = auth()->user();
                                        $canEdit = false;
                                        $canDelete = false;
                                        if ($user->isAdmin()) {
                                            $canEdit = true;
                                            $canDelete = true;
                                        } elseif ($user->isTownHall() && $user->locality_id && $festivity->locality_id === $user->locality_id) {
                                            $canEdit = true;
                                            $canDelete = true;
                                        }
                                    @endphp
                                    @if($canEdit || $canDelete)
                                        <div class="d-flex gap-2">
                                            @if($canEdit)
                                                <a href="{{ route('events.edit', [$festivity, $event]) }}" class="btn btn-warning btn-sm" style="border-radius: 8px;">
                                                    <i class="bi bi-pencil me-1"></i>Editar Evento
                                                </a>
                                            @endif
                                            @if($canDelete)
                                                <form method="POST" action="{{ route('events.destroy', [$festivity, $event]) }}" class="d-inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este evento?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" style="border-radius: 8px;">
                                                        <i class="bi bi-trash me-1"></i>Eliminar Evento
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <style>
        body {
            background-image: url('/storage/background.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
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
            flex: 1;
        }
        /* Remove only top padding for this page */
        main.py-4 {
            padding-top: 0 !important;
        }
        
        /* Ensure footer stays at bottom */
        footer {
            margin-top: auto;
        }
        
        /* Header Event Detail Section */
        .header-event-detail {
            position: relative;
            padding: 3rem 0 2rem;
            margin: 0;
            overflow: hidden;
            background-color: #0f172a;
        }
        
        .header-event-detail::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url('/storage/hero-2.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.9;
            z-index: 0;
        }
        
        .header-event-detail::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(15,23,42,0.15) 0%, rgba(15,23,42,0.82) 65%, rgba(15,23,42,0.95) 100%);
            z-index: 0;
        }
        
        .header-content {
            position: relative;
            z-index: 1;
            text-align: center;
        }
        
        .header-title {
            font-size: 2rem;
            font-weight: 700;
            color: rgba(255,255,255,0.95);
            margin: 0;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        /* Context Text */
        .context-info {
            text-align: center;
            color: rgba(255,255,255,0.95);
            font-size: 0.9375rem;
            font-weight: 500;
            position: relative;
            z-index: 1;
        }
        
        /* Header Actions */
        .header-actions {
            position: absolute;
            top: 1.5rem;
            right: 1rem;
            display: flex;
            gap: 0.75rem;
            z-index: 10;
        }
        
        .btn-back,
        .btn-edit,
        .btn-delete {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: white;
            color: #FEB101;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 4px rgba(0,0,0,0.3);
            transition: all 0.2s ease;
            text-decoration: none;
            font-size: 1.3rem;
            border: none;
            cursor: pointer;
        }
        
        .btn-edit {
            color: #F59E0B;
        }
        
        .btn-delete {
            color: #DC3545;
        }
        
        .btn-back:hover {
            background: #FEB101;
            color: white;
            transform: translateX(-3px);
            box-shadow: 0 2px 6px rgba(254, 177, 1, 0.4);
        }
        
        .btn-edit:hover {
            background: #F59E0B;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 2px 6px rgba(245, 158, 11, 0.4);
        }
        
        .btn-delete:hover {
            background: #DC3545;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 2px 6px rgba(220, 53, 69, 0.4);
        }
        
        /* Detail Event Card */
        .detail-event-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.25) !important;
        }
        
        .detail-event-card .card-body {
            padding: 2rem;
        }
        
        .event-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1F2937;
            line-height: 1.3;
        }
        
        /* Detail Sections */
        .detail-section {
            margin-bottom: 1.5rem;
        }
        
        .detail-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .detail-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, #FEB101 0%, #F59E0B 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: white;
            font-size: 1.25rem;
        }
        
        .detail-content {
            flex: 1;
        }
        
        .detail-label {
            font-size: 0.875rem;
            color: #6B7280;
            margin: 0 0 0.25rem 0;
            font-weight: 500;
        }
        
        .detail-value {
            font-size: 1.125rem;
            color: #1F2937;
            margin: 0;
            font-weight: 600;
        }
        
        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1F2937;
            margin-bottom: 1rem;
        }
        
        .description-text {
            color: #4B5563;
            line-height: 1.7;
            font-size: 1rem;
        }
        
        .description-text p {
            margin-bottom: 1rem;
        }
        
        .description-text p:last-child {
            margin-bottom: 0;
        }
        
        /* Responsive */
        @media (max-width: 767px) {
            .header-event-detail {
                padding: 1.5rem 0 1rem;
            }
            
            .header-title {
                font-size: 1.5rem;
            }
            
            .header-actions {
                top: 1rem;
                right: 0.5rem;
            }
            
            .btn-back,
            .btn-edit,
            .btn-delete {
                width: 38px;
                height: 38px;
                font-size: 1.1rem;
            }
            
            .context-info {
                font-size: 0.8rem;
            }
            
            .detail-event-card .card-body {
                padding: 1.5rem;
            }
            
            .event-title {
                font-size: 1.5rem;
            }
        }
    </style>
    @endpush
</x-app-layout>
