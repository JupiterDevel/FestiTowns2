@extends('layouts.app')

@section('title', $festive->name . ' - FestiTowns')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $festive->name }}</h1>
                    <p class="text-xl text-gray-600">{{ $festive->town->name }}, {{ $festive->town->province }}</p>
                    <p class="text-lg text-gray-500 mt-2">{{ \Carbon\Carbon::parse($festive->date)->format('d \d\e F \d\e Y') }}</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('festives.edit', $festive) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                        ✏️ Editar
                    </a>
                    <form action="{{ route('festives.destroy', $festive) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta festividad?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                            🗑️ Eliminar
                        </button>
                    </form>
                </div>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">📢 Anuncios ({{ $festive->advertisements->count() }})</h2>
                @if($festive->advertisements->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($festive->advertisements as $advertisement)
                    <div class="bg-gray-50 rounded-lg overflow-hidden hover:bg-gray-100 transition-colors">
                        <img src="{{ $advertisement->image_url }}" alt="Anuncio" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <a href="{{ route('advertisements.show', $advertisement) }}" class="inline-flex items-center px-3 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 transition-colors">
                                Ver anuncio
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8 text-gray-500">
                    <p class="text-lg">Esta festividad no tiene anuncios registrados</p>
                    <a href="{{ route('advertisements.create', ['festive_id' => $festive->id]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors mt-4">
                        Crear anuncio
                    </a>
                </div>
                @endif
            </div>

            <div class="flex justify-between">
                <a href="{{ route('festives.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                    ← Volver a festividades
                </a>
                <a href="{{ route('advertisements.create', ['festive_id' => $festive->id]) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                    ➕ Nuevo anuncio
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

