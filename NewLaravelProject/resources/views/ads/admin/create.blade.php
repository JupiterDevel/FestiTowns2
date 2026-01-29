<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="display-6 fw-bold text-primary mb-0">
                <i class="bi bi-plus-circle me-2"></i>Crear anuncio premium
            </h1>
            <a href="{{ route('advertisements.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Volver
            </a>
        </div>
    </x-slot>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('advertisements.store') }}" enctype="multipart/form-data">
                    @include('ads.admin._form', ['advertisement' => new \App\Models\Advertisement(['active' => true]), 'festivities' => $festivities, 'localities' => $localities, 'preselectedFestivityId' => $preselectedFestivityId ?? null])
                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <a href="{{ route('advertisements.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Guardar anuncio
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

