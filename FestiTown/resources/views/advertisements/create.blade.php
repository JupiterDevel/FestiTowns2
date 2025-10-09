@extends('layouts.app')

@section('title', 'Nuevo Anuncio - FestiTowns')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">📢 Nuevo Anuncio</h1>

            <form action="{{ route('advertisements.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label for="image_url" class="block text-sm font-medium text-gray-700 mb-2">URL de la Imagen</label>
                        <input type="url" id="image_url" name="image_url" value="{{ old('image_url') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('image_url') border-red-500 @enderror" 
                               placeholder="https://ejemplo.com/imagen.jpg" required>
                        @error('image_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="festive_id" class="block text-sm font-medium text-gray-700 mb-2">Festividad</label>
                        <select id="festive_id" name="festive_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('festive_id') border-red-500 @enderror" 
                                required>
                            <option value="">Selecciona una festividad</option>
                            @foreach($festives as $festive)
                                <option value="{{ $festive->id }}" {{ old('festive_id') == $festive->id ? 'selected' : '' }}>
                                    {{ $festive->name }} - {{ $festive->town->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('festive_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end space-x-4 mt-8">
                    <a href="{{ route('advertisements.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                        Crear Anuncio
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

