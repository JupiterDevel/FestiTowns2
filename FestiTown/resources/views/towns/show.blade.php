@extends('layouts.app')

@section('title', $town->name . ' - FestiTowns')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <img src="{{ $town->photo }}" alt="{{ $town->name }}" class="w-full h-64 object-cover">
            <div class="p-8">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $town->name }}</h1>
                        <p class="text-xl text-gray-600">{{ $town->province }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('towns.edit', $town) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                            ✏️ Editar
                        </a>
                        <form action="{{ route('towns.destroy', $town) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este pueblo?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                                🗑️ Eliminar
                            </button>
                        </form>
                    </div>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">🎉 Festividades ({{ $town->festive->count() }})</h2>
                    @if($town->festive->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($town->festive as $festive)
                        <div class="bg-gray-50 rounded-lg p-6 hover:bg-gray-100 transition-colors">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $festive->name }}</h3>
                                <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                    {{ \Carbon\Carbon::parse($festive->date)->format('d M Y') }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-4">{{ $festive->advertisements->count() }} anuncios</p>
                            <a href="{{ route('festives.show', $festive) }}" class="inline-flex items-center px-3 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 transition-colors">
                                Ver detalles
                            </a>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8 text-gray-500">
                        <p class="text-lg">Este pueblo no tiene festividades registradas</p>
                        <a href="{{ route('festives.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors mt-4">
                            Crear festividad
                        </a>
                    </div>
                    @endif
                </div>

                <div class="flex justify-between">
                    <a href="{{ route('towns.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                        ← Volver a pueblos
                    </a>
                    <a href="{{ route('festives.create', ['town_id' => $town->id]) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                        ➕ Nueva festividad
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

