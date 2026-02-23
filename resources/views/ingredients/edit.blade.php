@extends('layouts.app')
@section('title', 'Edit Ingredient')
@section('page-title', 'Edit Ingredient')
@section('content')
<div style="max-width:650px">
<a href="{{ route('ingredients.index') }}" class="btn btn-outline btn-sm" style="margin-bottom:16px"><i class="fas fa-arrow-left"></i> Back</a>
<div class="card">
    <div class="card-header"><h2>Edit: {{ $ingredient->name }}</h2></div>
    <div class="card-body">
        <div class="alert alert-info"><i class="fas fa-info-circle"></i> To change stock quantity, use the "Stock In" feature instead of editing directly.</div>
        <form method="POST" action="{{ route('ingredients.update', $ingredient) }}">
            @csrf @method('PUT')
            <div class="form-row">
                <div class="form-group">
                    <label>Ingredient Name *</label>
                    <input type="text" name="name" value="{{ old('name', $ingredient->name) }}" required>
                </div>
                <div class="form-group">
                    <label>Unit *</label>
                    <select name="unit" required>
                        @foreach(['kg','gram','piece','liter','ml','dozen'] as $unit)
                            <option value="{{ $unit }}" {{ old('unit', $ingredient->unit) == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Current Stock (read-only)</label>
                    <input type="text" value="{{ $ingredient->current_stock }} {{ $ingredient->unit }}" disabled style="background:#f8f9fa">
                </div>
                <div class="form-group">
                    <label>Minimum Stock Alert *</label>
                    <input type="number" name="minimum_stock" value="{{ old('minimum_stock', $ingredient->minimum_stock) }}" step="0.001" min="0" required>
                </div>
            </div>
            <div class="form-group">
                <label>Cost per Unit (Rs.)</label>
                <input type="number" name="cost_per_unit" value="{{ old('cost_per_unit', $ingredient->cost_per_unit) }}" step="0.01" min="0">
            </div>
            <div class="flex-gap">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                <a href="{{ route('ingredients.stock-in.form', $ingredient) }}" class="btn btn-success"><i class="fas fa-plus-circle"></i> Add Stock</a>
            </div>
        </form>
    </div>
</div>
</div>
@endsection
