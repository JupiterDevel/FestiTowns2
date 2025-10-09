@extends('layouts.app')

@section('title', 'Inicio - FestiTowns')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Hero Section -->
        <div class="gradient-bg rounded-lg shadow-xl p-8 mb-8 text-white">
            <div class="text-center">
                <h1 class="text-4xl font-bold mb-4">🏘️ Bienvenido a FestiTowns</h1>
                <p class="text-xl mb-6">Descubre los pueblos más hermosos y sus festividades tradicionales</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                    <div class="bg-white bg-opacity-20 rounded-lg p-4">
                        <div class="text-3xl font-bold">{{ $stats['towns_count'] }}</div>
                        <div class="text-sm opacity-90">Pueblos</div>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-lg p-4">
                        <div class="text-3xl font-bold">{{ $stats['festives_count'] }}</div>
                        <div class="text-sm opacity-90">Festividades</div>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-lg p-4">
                        <div class="text-3xl font-bold">{{ $stats['advertisements_count'] }}</div>
                        <div class="text-sm opacity-90">Anuncios</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Towns -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">🏘️ Pueblos Destacados</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($featuredTowns as $town)
                <div class="bg-white rounded-lg shadow-md overflow-hidden card-hover">
                    <img src="{{ $town->photo }}" alt="{{ $town->name }}" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $town->name }}</h3>
                        <p class="text-gray-600 mb-3">{{ $town->province }}</p>
                        <p class="text-sm text-gray-500 mb-4">{{ $town->festive->count() }} festividades</p>
                        <a href="{{ route('towns.show', $town) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                            Ver detalles
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-6">
                <a href="{{ route('towns.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                    Ver todos los pueblos
                </a>
            </div>
        </div>

        <!-- Upcoming Festives -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">🎉 Próximas Festividades</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($upcomingFestives as $festive)
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $festive->name }}</h3>
                        <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded">
                            {{ \Carbon\Carbon::parse($festive->date)->format('d M') }}
                        </span>
                    </div>
                    <p class="text-gray-600 mb-2">{{ $festive->town->name }}, {{ $festive->town->province }}</p>
                    <p class="text-sm text-gray-500 mb-4">{{ $festive->advertisements->count() }} anuncios</p>
                    <a href="{{ route('festives.show', $festive) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                        Ver detalles
                    </a>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-6">
                <a href="{{ route('festives.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                    Ver todas las festividades
                </a>
            </div>
        </div>

        <!-- Recent Advertisements -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">📢 Anuncios Recientes</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($recentAdvertisements as $advertisement)
                <div class="bg-white rounded-lg shadow-md overflow-hidden card-hover">
                    <img src="{{ $advertisement->image_url }}" alt="Anuncio" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-1">{{ $advertisement->festive->name }}</h3>
                        <p class="text-xs text-gray-600">{{ $advertisement->festive->town->name }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-6">
                <a href="{{ route('advertisements.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                    Ver todos los anuncios
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
