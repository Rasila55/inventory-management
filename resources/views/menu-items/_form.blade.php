{{-- menu-items/_form.blade.php --}}
<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label fw-semibold">Item Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $menuItem->name ?? '') }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
            <option value="">-- Select --</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('category_id', $menuItem->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label fw-semibold">Description</label>
        <textarea name="description" class="form-control" rows="2">{{ old('description', $menuItem->description ?? '') }}</textarea>
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Price (NPR) <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text">NPR</span>
            <input type="number" name="price" step="0.01" min="0"
                   class="form-control @error('price') is-invalid @enderror"
                   value="{{ old('price', $menuItem->price ?? '') }}" required>
        </div>
        @error('price')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Image <span class="text-muted">(optional)</span></label>
        <input type="file" name="image" accept="image/*" class="form-control @error('image') is-invalid @enderror">
        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
        @if(!empty($menuItem->image))
            <div class="mt-2">
                <img src="{{ asset('storage/' . $menuItem->image) }}" height="60" class="rounded">
                <small class="text-muted d-block">Current image</small>
            </div>
        @endif
    </div>

    <div class="col-md-4 d-flex align-items-end">
        <div class="form-check form-switch mb-2">
            <input class="form-check-input" type="checkbox" name="is_available" value="1" id="is_available"
                   {{ old('is_available', $menuItem->is_available ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_available">Available on menu</label>
        </div>
    </div>
</div>
