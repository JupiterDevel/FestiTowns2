@csrf
<div class="row g-3">
    <div class="col-md-6">
        <label for="name" class="form-label fw-bold">Nombre del anuncio *</label>
        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $advertisement->name ?? '') }}" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="url" class="form-label fw-bold">URL destino (opcional)</label>
        <input type="url" id="url" name="url" class="form-control @error('url') is-invalid @enderror"
               value="{{ old('url', $advertisement->url ?? '') }}" placeholder="https://example.com">
        @error('url')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row g-3 mt-2">
    <div class="col-md-6">
        <label for="festivity_id" class="form-label fw-bold">Festividad asociada *</label>
        @php
            $selectedFestivityId = old('festivity_id', $advertisement->festivity_id ?? $preselectedFestivityId ?? '');
            $isFestivityPreselected = !empty($preselectedFestivityId);
        @endphp
        <select id="festivity_id" name="festivity_id" class="form-select @error('festivity_id') is-invalid @enderror" required {{ $isFestivityPreselected ? 'disabled' : '' }}>
            <option value="">Selecciona una festividad</option>
            @foreach($festivities as $festivity)
                <option value="{{ $festivity->id }}" {{ (int) $selectedFestivityId === $festivity->id ? 'selected' : '' }}>
                    {{ $festivity->name }} ({{ optional($festivity->locality)->name }})
                </option>
            @endforeach
        </select>
        @if($isFestivityPreselected)
            <input type="hidden" name="festivity_id" value="{{ $preselectedFestivityId }}">
        @endif
        @error('festivity_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">Las fechas del anuncio se calculan automáticamente a partir de la festividad seleccionada.</small>
    </div>
    <div class="col-md-6">
        <label for="locality_id" class="form-label fw-bold">Localidad (opcional)</label>
        <select id="locality_id" name="locality_id" class="form-select @error('locality_id') is-invalid @enderror">
            <option value="">Sin localidad específica</option>
            @foreach($localities as $localityOption)
                <option value="{{ $localityOption->id }}" {{ (int) old('locality_id', $advertisement->locality_id ?? '') === $localityOption->id ? 'selected' : '' }}>
                    {{ $localityOption->name }} ({{ $localityOption->province }})
                </option>
            @endforeach
        </select>
        @error('locality_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row g-3 mt-2">
    <div class="col-md-4">
        <label for="priority" class="form-label fw-bold">Posición *</label>
        <select id="priority" name="priority" class="form-select @error('priority') is-invalid @enderror" required>
            <option value="principal" {{ old('priority', $advertisement->priority ?? '') === 'principal' ? 'selected' : '' }}>Principal (banner)</option>
            <option value="secondary" {{ old('priority', $advertisement->priority ?? '') === 'secondary' ? 'selected' : '' }}>Secundario (lateral)</option>
        </select>
        @error('priority')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-bold d-block">Estado</label>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="active" name="active" value="1" {{ old('active', $advertisement->active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="active">Activo</label>
        </div>
        @error('active')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="image" class="form-label fw-bold">Imagen del anuncio {{ isset($advertisement) ? '(opcional)' : '*' }}</label>
        <input type="file" id="image" name="image" class="form-control @error('image') is-invalid @enderror"
               accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" {{ isset($advertisement) ? '' : 'required' }}>
        <small class="text-muted">Tamaño recomendado: 800x400. Máx 5MB.</small>
        @error('image')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        @if(isset($advertisement) && $advertisement->image)
            <div class="mt-2">
                <img src="{{ \Illuminate\Support\Str::startsWith($advertisement->image, ['http://', 'https://']) ? $advertisement->image : asset($advertisement->image) }}"
                     alt="Imagen actual"
                     class="img-fluid rounded shadow-sm"
                     style="max-height: 150px; object-fit: cover;">
            </div>
        @endif
    </div>
</div>

