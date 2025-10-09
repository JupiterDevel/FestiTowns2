@extends('layouts.app')

@section('title', 'Editar ' . $festive->name . ' - FestiTowns')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">✏️ Editar {{ $festive->name }}</h1>

            <form action="{{ route('festives.update', $festive) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nombre de la Festividad</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $festive->name) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror" 
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
                        <input type="date" id="date" name="date" value="{{ old('date', $festive->date) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('date') border-red-500 @enderror" 
                               required>
                        @error('date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="town_id" class="block text-sm font-medium text-gray-700 mb-2">Pueblo</label>
                        <select id="town_id" name="town_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('town_id') border-red-500 @enderror" 
                                required>
                            <option value="">Selecciona un pueblo</option>
                            @foreach($towns as $town)
                                <option value="{{ $town->id }}" {{ old('town_id', $festive->town_id) == $town->id ? 'selected' : '' }}>
                                    {{ $town->name }}, {{ $town->province }}
                                </option>
                            @endforeach
                        </select>
                        @error('town_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end space-x-4 mt-8">
                    <a href="{{ route('festives.show', $festive) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                        Actualizar Festividad
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

