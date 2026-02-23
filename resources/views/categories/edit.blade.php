@extends('layouts.app')
@section('title', 'Edit Category')
@section('page-title', 'Edit Category')
@section('content')
<div style="max-width:600px">
<a href="{{ route('categories.index') }}" class="btn btn-outline btn-sm" style="margin-bottom:16px"><i class="fas fa-arrow-left"></i> Back</a>
<div class="card">
    <div class="card-header"><h2>Edit: {{ $category->name }}</h2></div>
    <div class="card-body">
        <form method="POST" action="{{ route('categories.update', $category) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Category Name *</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required>
                @error('name')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3">{{ old('description', $category->description) }}</textarea>
            </div>
            <div class="form-group">
                <label><input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}> Active</label>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Category</button>
        </form>
    </div>
</div>
</div>
@endsection
