<x-app-layout>
    <x-slot name="header">
        <h1 class="display-6 fw-bold text-primary mb-0">Discover Spanish Festivals</h1>
    </x-slot>

    <div class="container">
        <!-- Hero Section -->
        <div class="hero-section rounded-3 p-5 mb-5">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-3">🎉 FestiTowns</h1>
                    <p class="lead mb-4">Discover the most vibrant and exciting festivals across Spain</p>
                    <p class="h5">From the Running of the Bulls in Pamplona to the spectacular Fallas in Valencia, explore the rich cultural heritage of Spanish festivities.</p>
                </div>
                <div class="col-lg-4 text-center">
                    <i class="display-1">🇪🇸</i>
                </div>
            </div>
        </div>

        <!-- Upcoming Festivities -->
        <div class="mb-5">
            <h2 class="display-6 fw-bold text-dark mb-4">
                <i class="bi bi-calendar-event me-2"></i>Upcoming Festivities
            </h2>
            <div class="row g-4">
                @foreach($upcomingFestivities as $festivity)
                    <div class="col-md-6 col-lg-4">
                        <div class="card festivity-card card-hover h-100">
                            @if($festivity->photos && count($festivity->photos) > 0)
                                <img src="{{ $festivity->photos[0] }}" class="card-img-top" alt="{{ $festivity->name }}">
                            @endif
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $festivity->name }}</h5>
                                <p class="text-muted mb-2">
                                    <i class="bi bi-geo-alt me-1"></i>{{ $festivity->locality->name }}
                                </p>
                                <p class="text-muted small mb-3">
                                    <i class="bi bi-calendar me-1"></i>
                                    {{ $festivity->start_date->format('M j') }}
                                    @if($festivity->end_date)
                                        - {{ $festivity->end_date->format('M j, Y') }}
                                    @endif
                                </p>
                                <p class="card-text flex-grow-1">{{ Str::limit($festivity->description, 100) }}</p>
                                <a href="{{ route('festivities.show', $festivity) }}" class="btn btn-primary btn-custom">
                                    Learn More <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Localities Section -->
        <div class="mb-5">
            <h2 class="display-6 fw-bold text-dark mb-4">
                <i class="bi bi-map me-2"></i>Explore Localities
            </h2>
            <div class="row g-4">
                @foreach($localities as $locality)
                    <div class="col-md-6 col-lg-4">
                        <div class="card locality-card card-hover h-100">
                            @if($locality->photos && count($locality->photos) > 0)
                                <img src="{{ $locality->photos[0] }}" class="card-img-top" alt="{{ $locality->name }}">
                            @endif
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $locality->name }}</h5>
                                <p class="text-muted mb-2">
                                    <i class="bi bi-geo-alt me-1"></i>{{ $locality->address }}
                                </p>
                                <p class="card-text flex-grow-1">{{ Str::limit($locality->description, 120) }}</p>
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <span class="badge bg-info">
                                        {{ $locality->festivities->count() }} 
                                        {{ Str::plural('festivity', $locality->festivities->count()) }}
                                    </span>
                                    <a href="{{ route('localities.show', $locality) }}" class="btn btn-success btn-custom">
                                        Explore <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Call to Action -->
        @guest
            <div class="card bg-primary text-white text-center">
                <div class="card-body py-5">
                    <h2 class="display-6 fw-bold mb-4">Join Our Community</h2>
                    <p class="lead mb-4">Register to share your festival experiences and connect with other festival enthusiasts!</p>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="{{ route('register') }}" class="btn btn-light btn-custom btn-lg">
                            <i class="bi bi-person-plus me-2"></i>Sign Up
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-custom btn-lg">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login
                        </a>
                    </div>
                </div>
            </div>
        @endguest
    </div>
</x-app-layout>
