@extends('layouts.app')
@section('title', 'Add Ingredient')
@section('page-title', 'Add Ingredient')
@section('content')
<div style="max-width:650px">
<a href="{{ route('ingredients.index') }}" class="btn btn-outline btn-sm" style="margin-bottom:16px"><i class="fas fa-arrow-left"></i> Back</a>
<div class="card">
    <div class="card-header"><h2>New Ingredient</h2></div>
    <div class="card-body">
        <form method="POST" action="{{ route('ingredients.store') }}">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label>Ingredient Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Tomato" required>
                    @error('name')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>Unit *</label>
                    <select name="unit" required>
                        <option value="">— Select Unit —</option>
                        @foreach(['kg','gram','piece','liter','ml','dozen'] as $unit)
                            <option value="{{ $unit }}" {{ old('unit') == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                        @endforeach
                    </select>
                    @error('unit')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-row-3">
                <div class="form-group">
                    <label>Current Stock *</label>
                    <input type="number" name="current_stock" value="{{ old('current_stock', 0) }}" step="0.001" min="0" required>
                    @error('current_stock')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>Minimum Stock Alert *</label>
                    <input type="number" name="minimum_stock" value="{{ old('minimum_stock', 0) }}" step="0.001" min="0" required>
                    <div class="form-hint">Alert triggers when stock reaches this level</div>
                </div>
                <div class="form-group">
                    <label>Cost per Unit (Rs.)</label>
                    <input type="number" name="cost_per_unit" value="{{ old('cost_per_unit') }}" step="0.01" min="0" placeholder="Optional">
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Ingredient</button>
        </form>
    </div>
</div>
</div>
@endsection
