<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="display-6 fw-bold text-primary mb-0">
                <i class="bi bi-geo-alt me-2" aria-hidden="true"></i>{{ $locality->name }}
            </h1>
            @auth
                <div class="d-flex gap-2">
                    @can('update', $locality)
                        <a href="{{ route('localities.edit', $locality) }}" class="btn btn-warning btn-custom">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </a>
                    @endcan
                    @can('delete', $locality)
                        <form method="POST" action="{{ route('localities.destroy', $locality) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-custom" 
                                    onclick="return confirm('Are you sure you want to delete this locality?')">
                                <i class="bi bi-trash me-1"></i>Delete
                            </button>
                        </form>
                    @endcan
                </div>
            @endauth
        </div>
    </x-slot>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @include('ads.main_banner', ['ad' => $mainAdvertisement, 'newAdParams' => $adCreationParams])

        <div class="row g-4">
            <div class="col-lg-8">
                <!-- Photos Carousel -->
                @if($locality->photos && count($locality->photos) > 0)
                    <div class="mb-4">
                        <h3 class="display-6 fw-bold text-dark mb-4">
                            <i class="bi bi-images me-2"></i>Photos
                        </h3>
                        <div class="row justify-content-center">
                            <div class="col-lg-10">
                                <div id="localityCarousel" class="carousel slide shadow-lg rounded-3" data-bs-ride="carousel">
                                    <div class="carousel-indicators">
                                        @foreach($locality->photos as $index => $photo)
                                            <button type="button" data-bs-target="#localityCarousel" data-bs-slide-to="{{ $index }}" 
                                                    class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                                    aria-label="Slide {{ $index + 1 }}"></button>
                                        @endforeach
                                    </div>
                                    
                                    <div class="carousel-inner">
                                        @foreach($locality->photos as $index => $photo)
                                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                <img src="{{ $photo }}" 
                                                     class="d-block w-100" 
                                                     alt="{{ $locality->name }} - Imagen {{ $index + 1 }}" 
                                                     loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                                                     style="height: 400px; object-fit: cover;"
                                                     width="1200"
                                                     height="400">
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    @if(count($locality->photos) > 1)
                                        <button class="carousel-control-prev" type="button" data-bs-target="#localityCarousel" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#localityCarousel" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Basic Information -->
                <article class="card mb-4">
                    <div class="card-body">
                        <h2 class="card-title h4 fw-bold mb-3">
                            <i class="bi bi-info-circle me-2" aria-hidden="true"></i>Sobre {{ $locality->name }}
                        </h2>
                        <p class="card-text mb-3">{{ $locality->description }}</p>
                        <p class="text-muted">
                            <i class="bi bi-geo-alt me-1"></i><strong>Address:</strong> {{ $locality->address }}
                        </p>
                    </div>
                </article>

                <!-- Places of Interest -->
                <section class="card mb-4" aria-label="Lugares de interés">
                    <div class="card-body">
                        <h2 class="card-title h4 fw-bold mb-3">
                            <i class="bi bi-star me-2" aria-hidden="true"></i>Lugares de Interés
                        </h2>
                        <div class="card-text" style="white-space: pre-line;">{{ $locality->places_of_interest }}</div>
                    </div>
                </section>

                <!-- Monuments -->
                <section class="card mb-4" aria-label="Monumentos">
                    <div class="card-body">
                        <h2 class="card-title h4 fw-bold mb-3">
                            <i class="bi bi-building me-2" aria-hidden="true"></i>Monumentos
                        </h2>
                        <div class="card-text" style="white-space: pre-line;">{{ $locality->monuments }}</div>
                    </div>
                </section>

                <div class="d-lg-none mb-4">
                    @include('ads.secondary_banner', ['ads' => $secondaryAdvertisements, 'orientation' => 'inline', 'newAdParams' => $adCreationParams])
                </div>

                <!-- Festivities -->
                <section class="card" aria-label="Festividades">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2 class="card-title h4 fw-bold mb-0">
                                <i class="bi bi-calendar-event me-2" aria-hidden="true"></i>Festividades en {{ $locality->name }}
                            </h2>
                            @auth
                                @can('create', App\Models\Festivity::class)
                                    <a href="{{ route('festivities.create') }}?locality_id={{ $locality->id }}" class="btn btn-success btn-custom">
                                        <i class="bi bi-plus-circle me-1"></i>Add Festivity
                                    </a>
                                @endcan
                            @endauth
                        </div>

                        @if($locality->festivities->count() > 0)
                            <div class="row g-4">
                                @foreach($locality->festivities as $festivity)
                                    <div class="col-md-6">
                                        <div class="card h-100 card-hover">
                                            @if($festivity->photos && count($festivity->photos) > 0)
                                                <img src="{{ $festivity->photos[0] }}" 
                                                     class="card-img-top" 
                                                     alt="{{ $festivity->name }}" 
                                                     loading="lazy"
                                                     style="height: 150px; object-fit: cover;"
                                                     width="400"
                                                     height="150">
                                            @endif
                                            <div class="card-body d-flex flex-column">
                                                <h5 class="card-title">{{ $festivity->name }}</h5>
                                                <p class="text-muted small mb-2">
                                                    <i class="bi bi-calendar me-1"></i>
                                                    {{ $festivity->start_date->format('M j, Y') }}
                                                    @if($festivity->end_date)
                                                        - {{ $festivity->end_date->format('M j, Y') }}
                                                    @endif
                                                </p>
                                                <p class="card-text flex-grow-1">{{ Str::limit($festivity->description, 100) }}</p>
                                                <a href="{{ route('festivities.show', $festivity) }}" class="btn btn-primary btn-sm">
                                                    Learn More <i class="bi bi-arrow-right ms-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-calendar-x display-1 text-muted"></i>
                                <p class="text-muted mt-3">No festivities have been added to this locality yet.</p>
                            </div>
                        @endif
                    </div>
                </section>
            </div>

            <div class="col-lg-4 d-none d-lg-block">
                @include('ads.secondary_banner', ['ads' => $secondaryAdvertisements, 'orientation' => 'sidebar', 'newAdParams' => $adCreationParams])
            </div>
        </div>
    </div>

</x-app-layout>
