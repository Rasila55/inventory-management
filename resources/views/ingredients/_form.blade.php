{{-- ingredients/_form.blade.php --}}
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Ingredient Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $ingredient->name ?? '') }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label fw-semibold">Unit <span class="text-danger">*</span></label>
        <select name="unit" class="form-select @error('unit') is-invalid @enderror" required>
            @foreach(['kg','gram','piece','liter','ml','pack'] as $unit)
                <option value="{{ $unit }}" {{ old('unit', $ingredient->unit ?? '') === $unit ? 'selected' : '' }}>
                    {{ $unit }}
                </option>
            @endforeach
        </select>
        @error('unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label fw-semibold">Cost per Unit (NPR)</label>
        <input type="number" name="cost_per_unit" step="0.01" min="0" class="form-control"
               value="{{ old('cost_per_unit', $ingredient->cost_per_unit ?? '') }}" placeholder="0.00">
    </div>

    @if(!isset($editMode) || !$editMode)
    <div class="col-md-4">
        <label class="form-label fw-semibold">Opening Stock <span class="text-danger">*</span></label>
        <input type="number" name="current_stock" step="0.001" min="0"
               class="form-control @error('current_stock') is-invalid @enderror"
               value="{{ old('current_stock', $ingredient->current_stock ?? 0) }}" required>
        @error('current_stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    @endif

    <div class="col-md-4">
        <label class="form-label fw-semibold">Minimum Stock Alert <span class="text-danger">*</span></label>
        <input type="number" name="minimum_stock" step="0.001" min="0"
               class="form-control @error('minimum_stock') is-invalid @enderror"
               value="{{ old('minimum_stock', $ingredient->minimum_stock ?? 0) }}" required>
        <small class="text-muted">Alert when stock drops to or below this level.</small>
        @error('minimum_stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
