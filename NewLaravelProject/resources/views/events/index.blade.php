<x-app-layout>
    @push('head')
        @if(isset($events) && method_exists($events, 'previousPageUrl'))
            @if($events->previousPageUrl())
                <link rel="prev" href="{{ $events->previousPageUrl() }}">
            @endif
            @if($events->nextPageUrl())
                <link rel="next" href="{{ $events->nextPageUrl() }}">
            @endif
        @endif
    @endpush
    <!-- Header Section -->
    <div class="header-events">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="header-content">
                        <h1 class="header-title">
                            <i class="bi bi-calendar-event me-2"></i>Eventos de {{ $festivity->name }}
                        </h1>
                        <p class="context-info mt-2 mb-0">
                            <i class="bi bi-calendar-check me-1"></i>Eventos programados para esta festividad.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="header-actions">
            <a href="{{ route('festivities.show', $festivity) }}" class="btn-back" title="Volver a Festividad">
                <i class="bi bi-arrow-left"></i>
            </a>
            @auth
                @php
                    $user = auth()->user();
                    $canCreate = false;
                    if ($user->isAdmin()) {
                        $canCreate = true;
                    } elseif ($user->isTownHall() && $user->locality_id && $festivity->locality_id === $user->locality_id) {
                        $canCreate = true;
                    }
                @endphp
                @if($canCreate)
                    <a href="{{ route('events.create', $festivity) }}" class="btn-add-event" title="Añadir Evento">
                        <i class="bi bi-plus-lg"></i>
                    </a>
                @endif
            @endauth
        </div>
    </div>

    <div class="container" style="padding-top: 2rem; padding-bottom: 0;">

        @if($events->count() > 0)
            <div class="row g-3 mb-4">
                @foreach($events as $event)
                    <div class="col-md-6 col-lg-4 event-fade-in">
                        <div class="card compact-event-card h-100">
                            <!-- Card Body -->
                            <div class="card-body compact-card-body">
                                <a href="{{ route('events.show', [$festivity, $event]) }}" class="text-decoration-none">
                                    <h5 class="card-title compact-title mb-3">{{ $event->name }}</h5>
                                </a>
                                
                                <!-- Time Information -->
                                @if($event->start_time || $event->end_time)
                                    <div class="mb-3">
                                        @if($event->start_time)
                                            <p class="text-muted small mb-2" style="display: flex; align-items: center; gap: 0.5rem;">
                                                <i class="bi bi-clock" style="color: #FEB101; font-size: 0.95rem;"></i>
                                                <span><strong>Inicio:</strong> {{ $event->start_time->format('d/m/Y H:i') }}</span>
                                            </p>
                                        @endif
                                        @if($event->end_time)
                                            <p class="text-muted small mb-0" style="display: flex; align-items: center; gap: 0.5rem;">
                                                <i class="bi bi-clock-fill" style="color: #F59E0B; font-size: 0.95rem;"></i>
                                                <span><strong>Fin:</strong> {{ $event->end_time->format('d/m/Y H:i') }}</span>
                                            </p>
                                        @endif
                                    </div>
                                @else
                                    <div class="mb-3">
                                        <span class="badge compact-badge" style="background-color: #6B7280; color: #FFFFFF; font-size: 0.78rem; padding: 0.35rem 0.9rem; border-radius: 6px;">
                                            <i class="bi bi-question-circle me-1"></i>Sin horario definido
                                        </span>
                                    </div>
                                @endif

                                <!-- Location -->
                                @if($event->location)
                                    <p class="text-muted small mb-3" style="display: flex; align-items: center; gap: 0.5rem;">
                                        <i class="bi bi-geo-alt-fill" style="color: #1FA4A9; font-size: 0.95rem;"></i>
                                        <span>{{ $event->location }}</span>
                                    </p>
                                @endif

                                <!-- Description -->
                                @if($event->description)
                                    <p class="card-text text-muted small mb-3" style="line-height: 1.55; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ Str::limit($event->description, 120) }}
                                    </p>
                                @endif
                                
                                <!-- Footer Actions -->
                                <div class="mt-auto d-flex align-items-center justify-content-between" style="padding-top: 0.85rem; border-top: 1px solid #F3F4F6;">
                                    <a href="{{ route('events.show', [$festivity, $event]) }}" class="text-decoration-none" style="color: #FEB101; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em;">
                                        Ver detalles <i class="bi bi-arrow-right ms-1"></i>
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
                                            <div class="d-flex gap-1">
                                                @if($canEdit)
                                                    <a href="{{ route('events.edit', [$festivity, $event]) }}" class="btn btn-sm btn-outline-secondary" title="Editar" style="border-radius: 8px; padding: 0.25rem 0.5rem;">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                @endif
                                                @if($canDelete)
                                                    <form method="POST" action="{{ route('events.destroy', [$festivity, $event]) }}" class="d-inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este evento?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar" style="border-radius: 8px; padding: 0.25rem 0.5rem;">
                                                            <i class="bi bi-trash"></i>
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
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 gap-3">
                <div class="text-muted small">
                    Mostrando {{ $events->firstItem() ?? 0 }} - {{ $events->lastItem() ?? 0 }} de {{ $events->total() }} resultados
                </div>
                <div id="paginationContainer">
                    {{ $events->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-calendar-x display-1 text-muted"></i>
                <h3 class="mt-3 text-muted">No hay eventos programados</h3>
                <p class="text-muted">Esta festividad aún no tiene eventos programados.</p>
                @auth
                    @php
                        $user = auth()->user();
                        $canCreate = false;
                        if ($user->isAdmin()) {
                            $canCreate = true;
                        } elseif ($user->isTownHall() && $user->locality_id && $festivity->locality_id === $user->locality_id) {
                            $canCreate = true;
                        }
                    @endphp
                    @if($canCreate)
                        <a href="{{ route('events.create', $festivity) }}" class="btn btn-primary btn-custom mt-3">
                            <i class="bi bi-plus-circle me-1"></i>Crear Primer Evento
                        </a>
                    @endif
                @endauth
            </div>
        @endif
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
        
        /* Header Events Section */
        .header-events {
            position: relative;
            padding: 3rem 0 2rem;
            margin: 0;
            overflow: hidden;
            background-color: #0f172a;
        }
        
        .header-events::before {
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
        
        .header-events::after {
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
        .btn-add-event {
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
        }
        
        .btn-add-event {
            color: #1FA4A9;
        }
        
        .btn-back:hover {
            background: #FEB101;
            color: white;
            transform: translateX(-3px);
            box-shadow: 0 2px 6px rgba(254, 177, 1, 0.4);
        }
        
        .btn-add-event:hover {
            background: #1FA4A9;
            color: white;
            transform: rotate(90deg);
            box-shadow: 0 2px 6px rgba(31, 164, 169, 0.4);
        }
        
        /* Compact Event Cards */
        .compact-event-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.25) !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .compact-event-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.3) !important;
        }
        
        /* Override Bootstrap card shadows */
        .card {
            box-shadow: 0 2px 8px rgba(0,0,0,0.25) !important;
        }
        
        .card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.3) !important;
        }
        
        .compact-card-body {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
        }
        
        .compact-title {
            font-size: 1.375rem;
            font-weight: 700;
            color: #1F2937;
            transition: color 0.2s ease;
        }
        
        a:hover .compact-title {
            color: #FEB101;
        }
        
        .compact-badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
        }
        
        .event-fade-in {
            animation: fadeInUp 0.4s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Pagination Styling */
        #paginationContainer .pagination {
            gap: 0.375rem;
            margin: 0;
            flex-wrap: wrap;
        }
        
        #paginationContainer .page-item {
            margin: 0;
        }
        
        #paginationContainer .page-link {
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            color: #FEB101;
            padding: 0.375rem 0.75rem;
            font-weight: 600;
            font-size: 0.875rem;
            line-height: 1.25;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
        }
        
        #paginationContainer .page-link:hover {
            background: #FEB101;
            color: white;
            border-color: #FEB101;
            transform: translateY(-1px);
            box-shadow: 0 1px 4px rgba(254, 177, 1, 0.4);
        }
        
        #paginationContainer .page-item.active .page-link {
            background: #FEB101;
            border-color: #FEB101;
            color: white;
            font-weight: 700;
        }
        
        #paginationContainer .page-item.disabled .page-link {
            color: #9ca3af;
            background: #f9fafb;
            border-color: #e5e7eb;
            cursor: not-allowed;
        }
        
        #paginationContainer .page-item.disabled .page-link:hover {
            transform: none;
            box-shadow: none;
        }
        
        /* Hide default Laravel pagination text */
        #paginationContainer p,
        #paginationContainer .hidden {
            display: none !important;
        }
        
        /* Responsive */
        @media (max-width: 767px) {
            .header-events {
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
            .btn-add-event {
                width: 38px;
                height: 38px;
                font-size: 1.1rem;
            }
            
            .context-info {
                font-size: 0.8rem;
            }
            
            #paginationContainer .page-link {
                padding: 0.3rem 0.6rem;
                font-size: 0.8rem;
                min-width: 32px;
                height: 32px;
            }
            
            #paginationContainer .pagination {
                gap: 0.25rem;
            }
        }
    </style>
    @endpush
</x-app-layout>
