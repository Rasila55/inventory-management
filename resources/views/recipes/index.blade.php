@extends('layouts.app')
@section('title', 'Recipe — '.$menuItem->name)
@section('page-title', 'Recipe Management')
@section('content')

<div class="page-header">
    <div>
        <h1>Recipe: {{ $menuItem->name }}</h1>
        <div class="text-muted" style="margin-top:4px"><i class="fas fa-tag"></i> {{ $menuItem->category->name }} &nbsp;|&nbsp; Rs. {{ number_format($menuItem->price, 2) }}</div>
    </div>
    <a href="{{ route('menu-items.index') }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Back to Menu</a>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start">

    {{-- Current Recipe --}}
    <div class="card">
        <div class="card-header"><h2><i class="fas fa-book"></i> Current Recipe</h2></div>
        <div class="card-body" style="padding:0">
            @if($menuItem->ingredients->isEmpty())
                <div class="empty-state" style="padding:40px">
                    <i class="fas fa-book-open"></i>
                    <p>No ingredients added yet.<br>Add ingredients from the form.</p>
                </div>
            @else
            <table>
                <thead><tr><th>Ingredient</th><th>Unit</th><th>Qty Required</th><th>Stock</th><th></th></tr></thead>
                <tbody>
                 @foreach($menuItem->recipes as $recipe)
<tr>
    <td><strong>{{ $recipe->ingredient->name }}</strong></td>
    <td>{{ $recipe->ingredient->unit }}</td>
    <td><span class="badge badge-preparing">{{ $recipe->quantity_required }}</span></td>
    <td>
        @php $ok = $recipe->ingredient->current_stock >= $recipe->quantity_required; @endphp
        <span class="badge {{ $ok ? 'badge-ok' : 'badge-low' }}">{{ $recipe->ingredient->current_stock }}</span>
    </td>
    <td>
        <form method="POST" action="{{ route('recipes.destroy', [$menuItem, $recipe]) }}" onsubmit="return confirm('Remove this ingredient from recipe?')">
            @csrf @method('DELETE')
            <button class="btn btn-danger btn-sm btn-icon"><i class="fas fa-times"></i></button>
        </form>
    </td>
</tr>
@endforeach
                </tbody>
            </table>
            <div style="padding:12px 16px;background:#f8f9fa;border-top:1px solid #eee;font-size:.85rem;color:#666">
                <i class="fas fa-info-circle"></i> These quantities are <strong>per 1 serving</strong> of {{ $menuItem->name }}
            </div>
            @endif
        </div>
    </div>

    {{-- Add Ingredient to Recipe --}}
    <div class="card">
        <div class="card-header"><h2><i class="fas fa-plus-circle"></i> Add / Update Ingredient</h2></div>
        <div class="card-body">
            <form method="POST" action="{{ route('recipes.store', $menuItem) }}">
                @csrf
                <div class="form-group">
                    <label>Ingredient *</label>
                    <select name="ingredient_id" required>
                        <option value="">— Select Ingredient —</option>
                        @foreach($ingredients as $ing)
                            <option value="{{ $ing->id }}" {{ old('ingredient_id') == $ing->id ? 'selected' : '' }}>
                                {{ $ing->name }} ({{ $ing->unit }}) — Stock: {{ $ing->current_stock }}
                            </option>
                        @endforeach
                    </select>
                    @error('ingredient_id')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>Quantity Required per Serving *</label>
                    <input type="number" name="quantity_required" value="{{ old('quantity_required') }}" step="0.001" min="0.001" placeholder="e.g. 50 (grams)" required>
                    <div class="form-hint">Enter the amount needed to make 1 serving of {{ $menuItem->name }}</div>
                    @error('quantity_required')<div class="error-msg">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Ingredient</button>
            </form>
        </div>
    </div>

</div>

{{-- Stock Check Preview --}}
@if($menuItem->ingredients->isNotEmpty())
<div class="card" style="margin-top:20px">
    <div class="card-header"><h2><i class="fas fa-calculator"></i> Stock Availability Check</h2></div>
    <div class="card-body">
        <p class="text-muted" style="margin-bottom:12px">Shows how many servings can be made with current stock:</p>
        <table>
            <thead><tr><th>Ingredient</th><th>Stock</th><th>Per Serving</th><th>Max Servings Possible</th></tr></thead>
            <tbody>
                @php $maxServings = PHP_INT_MAX; @endphp
                @foreach($menuItem->ingredients as $ing)
                @php
                    $possible = $ing->pivot->quantity_required > 0 ? floor($ing->current_stock / $ing->pivot->quantity_required) : PHP_INT_MAX;
                    $maxServings = min($maxServings, $possible);
                @endphp
                <tr>
                    <td>{{ $ing->name }}</td>
                    <td>{{ $ing->current_stock }} {{ $ing->unit }}</td>
                    <td>{{ $ing->pivot->quantity_required }} {{ $ing->unit }}</td>
                    <td><span class="badge {{ $possible > 0 ? 'badge-ok' : 'badge-low' }}">{{ $possible }} serving(s)</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="alert {{ $maxServings > 0 ? 'alert-success' : 'alert-error' }}" style="margin-top:12px;margin-bottom:0">
            <i class="fas {{ $maxServings > 0 ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
            <strong>Total possible servings: {{ $maxServings === PHP_INT_MAX ? '∞' : $maxServings }}</strong>
            @if($maxServings == 0) — Stock insufficient for even 1 serving! @endif
        </div>
    </div>
</div>
@endif

@endsection
