@extends('layouts.app')
@section('title', 'Stock In')
@section('page-title', 'Add Stock')
@section('content')
<div style="max-width:500px">
<a href="{{ route('ingredients.index') }}" class="btn btn-outline btn-sm" style="margin-bottom:16px"><i class="fas fa-arrow-left"></i> Back</a>
<div class="card">
    <div class="card-header"><h2><i class="fas fa-plus-circle" style="color:#2d6a4f"></i> Stock In — {{ $ingredient->name }}</h2></div>
    <div class="card-body">
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            Current Stock: <strong>{{ $ingredient->current_stock }} {{ $ingredient->unit }}</strong>
            &nbsp;|&nbsp; Min Alert: <strong>{{ $ingredient->minimum_stock }} {{ $ingredient->unit }}</strong>
        </div>
        <form method="POST" action="{{ route('ingredients.stock-in', $ingredient) }}">
            @csrf
            <div class="form-group">
                <label>Quantity to Add ({{ $ingredient->unit }}) *</label>
                <input type="number" name="quantity" value="{{ old('quantity') }}" step="0.001" min="0.001" placeholder="e.g. 5.5" required>
                @error('quantity')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Reason / Notes</label>
                <textarea name="reason" rows="2" placeholder="e.g. Weekly stock purchase">{{ old('reason') }}</textarea>
            </div>
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Add Stock</button>
        </form>
    </div>
</div>
</div>
@endsection
