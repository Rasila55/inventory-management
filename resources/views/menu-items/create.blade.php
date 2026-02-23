@extends('layouts.app')
@section('title', 'Add Menu Item')
@section('page-title', 'Add Menu Item')
@section('content')
<div style="max-width:700px">
<a href="{{ route('menu-items.index') }}" class="btn btn-outline btn-sm" style="margin-bottom:16px"><i class="fas fa-arrow-left"></i> Back</a>
<div class="card">
    <div class="card-header"><h2>New Menu Item</h2></div>
    <div class="card-body">
        <form method="POST" action="{{ route('menu-items.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label>Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Cheese Burger" required>
                    @error('name')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>Category *</label>
                    <select name="category_id" required>
                        <option value="">— Select Category —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Price (Rs.) *</label>
                    <input type="number" name="price" value="{{ old('price') }}" step="0.01" min="0" placeholder="0.00" required>
                    @error('price')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>Image (optional)</label>
                    <input type="file" name="image" accept="image/*">
                    @error('image')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3" placeholder="Brief description...">{{ old('description') }}</textarea>
            </div>
            <div class="form-group">
                <label><input type="checkbox" name="is_available" value="1" {{ old('is_available', '1') ? 'checked' : '' }}> Available for ordering</label>
            </div>
            <div class="flex-gap">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Menu Item</button>
                <a href="{{ route('menu-items.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
</div>
@endsection
