<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="display-6 fw-bold text-primary mb-0">
                <i class="bi bi-star-fill me-2"></i>Las Más Votadas
            </h1>
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

        <div class="alert alert-info mb-4" role="alert">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Regla de votación:</strong> Cada usuario puede votar una sola vez al día por cualquier festividad. Los administradores pueden votar múltiples veces al día.
        </div>

        <!-- Sección Nacional -->
        <div class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h3 fw-bold text-primary">
                    <i class="bi bi-flag-fill me-2"></i>Nacional
                </h2>
                <span class="badge bg-primary fs-6">{{ $nationalFestivities->count() }} festividades</span>
            </div>
            <p class="text-muted mb-3">Ranking de las 7 mejores festividades de toda España</p>
            
            @if($nationalFestivities->count() > 0)
                <div class="row">
                    @foreach($nationalFestivities as $festivity)
                        <div class="col-lg-4 col-md-6 mb-4">
                            @include('festivities.partials.festivity-card', ['festivity' => $festivity])
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>No hay festividades votadas a nivel nacional.
                </div>
            @endif
        </div>

        <hr class="my-5">

        <!-- Sección Regional -->
        <div class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h3 fw-bold text-primary">
                    <i class="bi bi-map-fill me-2"></i>Regional
                </h2>
            </div>
            <p class="text-muted mb-3">Ranking de festividades según la comunidad autónoma seleccionada</p>
            
            <div class="mb-4">
                <form method="GET" action="{{ route('festivities.most-voted') }}" class="row g-3">
                    <div class="col-md-6">
                        <label for="community" class="form-label fw-bold">
                            <i class="bi bi-geo-alt me-1"></i>Comunidad Autónoma
                        </label>
                        <select name="community" id="community" class="form-select" onchange="this.form.submit()">
                            <option value="">Selecciona una comunidad autónoma</option>
                            @foreach($communities as $community)
                                <option value="{{ $community }}" {{ $selectedCommunity == $community ? 'selected' : '' }}>
                                    {{ $community }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if($selectedProvince)
                        <input type="hidden" name="province" value="{{ $selectedProvince }}">
                    @endif
                </form>
            </div>

            @if($selectedCommunity)
                @if($regionalFestivities->count() > 0)
                    <div class="alert alert-success mb-3">
                        <i class="bi bi-check-circle me-2"></i>
                        Mostrando las festividades más votadas de <strong>{{ $selectedCommunity }}</strong>
                    </div>
                    <div class="row">
                        @foreach($regionalFestivities as $festivity)
                            <div class="col-lg-4 col-md-6 mb-4">
                                @include('festivities.partials.festivity-card', ['festivity' => $festivity])
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        No hay festividades votadas en <strong>{{ $selectedCommunity }}</strong>.
                    </div>
                @endif
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Selecciona una comunidad autónoma para ver el ranking regional.
                </div>
            @endif
        </div>

        <hr class="my-5">

        <!-- Sección Provincial -->
        <div class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h3 fw-bold text-primary">
                    <i class="bi bi-geo-alt-fill me-2"></i>Provincial
                </h2>
            </div>
            <p class="text-muted mb-3">Ranking de festividades según la provincia seleccionada</p>
            
            <div class="mb-4">
                <form method="GET" action="{{ route('festivities.most-voted') }}" class="row g-3">
                    <div class="col-md-6">
                        <label for="province" class="form-label fw-bold">
                            <i class="bi bi-map me-1"></i>Provincia
                        </label>
                        <select name="province" id="province" class="form-select" onchange="this.form.submit()">
                            <option value="">Selecciona una provincia</option>
                            @foreach($provinces as $province)
                                <option value="{{ $province }}" {{ $selectedProvince == $province ? 'selected' : '' }}>
                                    {{ $province }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if($selectedCommunity)
                        <input type="hidden" name="community" value="{{ $selectedCommunity }}">
                    @endif
                </form>
            </div>

            @if($selectedProvince)
                @if($provincialFestivities->count() > 0)
                    <div class="alert alert-success mb-3">
                        <i class="bi bi-check-circle me-2"></i>
                        Mostrando las festividades más votadas de <strong>{{ $selectedProvince }}</strong>
                    </div>
                    <div class="row">
                        @foreach($provincialFestivities as $festivity)
                            <div class="col-lg-4 col-md-6 mb-4">
                                @include('festivities.partials.festivity-card', ['festivity' => $festivity])
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        No hay festividades votadas en <strong>{{ $selectedProvince }}</strong>.
                    </div>
                @endif
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Selecciona una provincia para ver el ranking provincial.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
