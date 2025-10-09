@extends('layouts.app')

@section('title', 'Anuncio - ' . $advertisement->festive->name . ' - FestiTowns')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <img src="{{ $advertisement->image_url }}" alt="Anuncio" class="w-full h-96 object-cover">
            <div class="p-8">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $advertisement->festive->name }}</h1>
                        <p class="text-xl text-gray-600">{{ $advertisement->festive->town->name }}, {{ $advertisement->festive->town->province }}</p>
                        <p class="text-lg text-gray-500 mt-2">{{ \Carbon\Carbon::parse($advertisement->festive->date)->format('d \d\e F \d\e Y') }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('advertisements.edit', $advertisement) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                            ✏️ Editar
                        </a>
                        <form action="{{ route('advertisements.destroy', $advertisement) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este anuncio?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                                🗑️ Eliminar
                            </button>
                        </form>
                    </div>
                </div>

                <div class="flex justify-between">
                    <a href="{{ route('advertisements.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                        ← Volver a anuncios
                    </a>
                    <a href="{{ route('festives.show', $advertisement->festive) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                        Ver festividad
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

