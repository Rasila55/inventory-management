@extends('layouts.app')
@section('title', 'Edit Menu Item')
@section('page-title', 'Edit Menu Item')
@section('content')
<div style="max-width:700px">
<a href="{{ route('menu-items.index') }}" class="btn btn-outline btn-sm" style="margin-bottom:16px"><i class="fas fa-arrow-left"></i> Back</a>
<div class="card">
    <div class="card-header"><h2>Edit: {{ $menuItem->name }}</h2></div>
    <div class="card-body">
        <form method="POST" action="{{ route('menu-items.update', $menuItem) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="form-row">
                <div class="form-group">
                    <label>Name *</label>
                    <input type="text" name="name" value="{{ old('name', $menuItem->name) }}" required>
                </div>
                <div class="form-group">
                    <label>Category *</label>
                    <select name="category_id" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $menuItem->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Price (Rs.) *</label>
                    <input type="number" name="price" value="{{ old('price', $menuItem->price) }}" step="0.01" min="0" required>
                </div>
                <div class="form-group">
                    <label>New Image (leave empty to keep current)</label>
                    @if($menuItem->image)
                        <img src="{{ asset('storage/'.$menuItem->image) }}" class="menu-thumb" style="margin-bottom:8px;display:block">
                    @endif
                    <input type="file" name="image" accept="image/*">
                </div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3">{{ old('description', $menuItem->description) }}</textarea>
            </div>
            <div class="form-group">
                <label><input type="checkbox" name="is_available" value="1" {{ old('is_available', $menuItem->is_available) ? 'checked' : '' }}> Available for ordering</label>
            </div>
            <div class="flex-gap">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                <a href="{{ route('recipes.index', $menuItem) }}" class="btn btn-secondary"><i class="fas fa-book"></i> Manage Recipe</a>
            </div>
        </form>
    </div>
</div>
</div>
@endsection
