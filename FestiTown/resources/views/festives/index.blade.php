@extends('layouts.app')

@section('title', 'Festividades - FestiTowns')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">🎉 Festividades</h1>
            <a href="{{ route('festives.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                ➕ Nueva Festividad
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($festives as $festive)
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $festive->name }}</h3>
                    <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded">
                        {{ \Carbon\Carbon::parse($festive->date)->format('d M Y') }}
                    </span>
                </div>
                <p class="text-gray-600 mb-2">{{ $festive->town->name }}, {{ $festive->town->province }}</p>
                <p class="text-sm text-gray-500 mb-4">{{ $festive->advertisements->count() }} anuncios</p>
                <div class="flex space-x-2">
                    <a href="{{ route('festives.show', $festive) }}" class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 transition-colors">
                        Ver
                    </a>
                    <a href="{{ route('festives.edit', $festive) }}" class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-gray-600 text-white text-sm rounded-md hover:bg-gray-700 transition-colors">
                        Editar
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <div class="text-gray-500 text-lg">No hay festividades registradas</div>
                <a href="{{ route('festives.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors mt-4">
                    Crear primera festividad
                </a>
            </div>
            @endforelse
        </div>

        @if($festives->hasPages())
        <div class="mt-8">
            {{ $festives->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

