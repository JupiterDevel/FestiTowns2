<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h1 class="display-6 fw-bold text-primary mb-0">
                <i class="bi bi-pencil-square me-2"></i>Editar anuncio
            </h1>
            <a href="{{ route('advertisements.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Volver
            </a>
        </div>
    </x-slot>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('advertisements.update', $advertisement) }}" enctype="multipart/form-data">
                    @method('PUT')
                    @include('ads.admin._form', ['advertisement' => $advertisement, 'festivities' => $festivities, 'localities' => $localities, 'preselectedFestivityId' => null])
                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <a href="{{ route('advertisements.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Actualizar anuncio
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

