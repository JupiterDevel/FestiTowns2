@extends('layouts.app')

@section('title', 'Anuncios - FestiTowns')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">📢 Anuncios</h1>
            <a href="{{ route('advertisements.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                ➕ Nuevo Anuncio
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($advertisements as $advertisement)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <img src="{{ $advertisement->image_url }}" alt="Anuncio" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="text-sm font-semibold text-gray-900 mb-1">{{ $advertisement->festive->name }}</h3>
                    <p class="text-xs text-gray-600 mb-3">{{ $advertisement->festive->town->name }}, {{ $advertisement->festive->town->province }}</p>
                    <div class="flex space-x-2">
                        <a href="{{ route('advertisements.show', $advertisement) }}" class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 transition-colors">
                            Ver
                        </a>
                        <a href="{{ route('advertisements.edit', $advertisement) }}" class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-gray-600 text-white text-sm rounded-md hover:bg-gray-700 transition-colors">
                            Editar
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <div class="text-gray-500 text-lg">No hay anuncios registrados</div>
                <a href="{{ route('advertisements.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors mt-4">
                    Crear primer anuncio
                </a>
            </div>
            @endforelse
        </div>

        @if($advertisements->hasPages())
        <div class="mt-8">
            {{ $advertisements->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

