@extends('layouts.app')
@section('title', 'Add Category')
@section('page-title', 'Add Category')
@section('content')
<div style="max-width:600px">
<a href="{{ route('categories.index') }}" class="btn btn-outline btn-sm" style="margin-bottom:16px"><i class="fas fa-arrow-left"></i> Back</a>
<div class="card">
    <div class="card-header"><h2>New Category</h2></div>
    <div class="card-body">
        <form method="POST" action="{{ route('categories.store') }}">
            @csrf
            <div class="form-group">
                <label>Category Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Main Course" required>
                @error('name')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3" placeholder="Optional description...">{{ old('description') }}</textarea>
            </div>
            <div class="form-group">
                <label><input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}> Active</label>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Category</button>
        </form>
    </div>
</div>
</div>
@endsection
