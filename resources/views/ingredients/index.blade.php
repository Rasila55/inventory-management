@extends('layouts.app')
@section('title', 'Ingredients')
@section('page-title', 'Ingredients / Stock')
@section('content')
<div class="page-header">
    <h1>Ingredients <span class="text-muted">({{ $ingredients->total() }})</span></h1>
    <div class="flex-gap">
        <a href="{{ route('ingredients.index', ['low_stock' => 1]) }}" class="btn btn-warning btn-sm"><i class="fas fa-exclamation-triangle"></i> Low Stock</a>
        <a href="{{ route('ingredients.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Ingredient</a>
    </div>
</div>
<div class="card">
    <div class="card-body" style="padding:0">
        @if($ingredients->isEmpty())
            <div class="empty-state"><i class="fas fa-boxes"></i><p>No ingredients yet.</p></div>
        @else
        <table>
            <thead><tr><th>Name</th><th>Unit</th><th>Current Stock</th><th>Min. Stock</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($ingredients as $ing)
                @php $isLow = $ing->current_stock <= $ing->minimum_stock; @endphp
                <tr>
                    <td><strong>{{ $ing->name }}</strong></td>
                    <td>{{ $ing->unit }}</td>
                    <td>
                        <span class="badge {{ $isLow ? 'badge-low' : 'badge-ok' }}">{{ $ing->current_stock }}</span>
                        @if($ing->minimum_stock > 0)
                        <div class="stock-bar" style="width:120px">
                            @php $pct = min(100, ($ing->current_stock / ($ing->minimum_stock * 2)) * 100); @endphp
                            <div class="stock-bar-fill" style="width:{{ $pct }}%;background:{{ $isLow ? '#e63946' : '#2d6a4f' }}"></div>
                        </div>
                        @endif
                    </td>
                    <td class="text-muted">{{ $ing->minimum_stock }}</td>
                    <td><span class="badge {{ $isLow ? 'badge-low' : 'badge-ok' }}">{{ $isLow ? '⚠ Low' : 'OK' }}</span></td>
                    <td>
                        <div class="flex-gap">
                            <a href="{{ route('ingredients.stock-in.form', $ing) }}" class="btn btn-success btn-sm" title="Add Stock"><i class="fas fa-plus-circle"></i> Stock In</a>
                            <a href="{{ route('ingredients.show', $ing) }}" class="btn btn-outline btn-sm"><i class="fas fa-history"></i></a>
                            <a href="{{ route('ingredients.edit', $ing) }}" class="btn btn-outline btn-sm"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{ route('ingredients.destroy', $ing) }}" onsubmit="return confirm('Delete this ingredient?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="padding:16px">{{ $ingredients->links() }}</div>
        @endif
    </div>
</div>
@endsection
