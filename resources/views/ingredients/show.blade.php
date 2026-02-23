@extends('layouts.app')
@section('title', 'Ingredient History')
@section('page-title', 'Stock History')
@section('content')
<div class="page-header">
    <h1>{{ $ingredient->name }} <span class="text-muted">— Stock History</span></h1>
    <div class="flex-gap">
        <a href="{{ route('ingredients.stock-in.form', $ingredient) }}" class="btn btn-success btn-sm"><i class="fas fa-plus-circle"></i> Stock In</a>
        <a href="{{ route('ingredients.index') }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</div>
<div class="stat-grid" style="margin-bottom:20px">
    <div class="stat-card"><div class="stat-icon {{ $ingredient->isLowStock() ? 'red' : 'green' }}"><i class="fas fa-boxes"></i></div>
        <div><div class="stat-label">Current Stock</div><div class="stat-value">{{ $ingredient->current_stock }} <small style="font-size:.9rem">{{ $ingredient->unit }}</small></div></div></div>
    <div class="stat-card"><div class="stat-icon orange"><i class="fas fa-exclamation-triangle"></i></div>
        <div><div class="stat-label">Minimum Stock</div><div class="stat-value">{{ $ingredient->minimum_stock }} <small style="font-size:.9rem">{{ $ingredient->unit }}</small></div></div></div>
</div>
<div class="card">
    <div class="card-header"><h2>Movement History</h2></div>
    <div class="card-body" style="padding:0">
        @if($movements->isEmpty())
            <div class="empty-state"><i class="fas fa-history"></i><p>No movements recorded yet.</p></div>
        @else
        <table>
            <thead><tr><th>Type</th><th>Qty Change</th><th>Before</th><th>After</th><th>Order</th><th>Reason</th><th>Date</th></tr></thead>
            <tbody>
                @foreach($movements as $m)
                <tr>
                    <td><span class="badge {{ $m->type === 'deduction' ? 'badge-low' : 'badge-ok' }}">{{ ucfirst(str_replace('_',' ',$m->type)) }}</span></td>
                    <td style="color:{{ $m->quantity < 0 ? '#e63946' : '#2d6a4f' }};font-weight:600">
                        {{ $m->quantity > 0 ? '+' : '' }}{{ $m->quantity }} {{ $ingredient->unit }}
                    </td>
                    <td class="text-muted">{{ $m->stock_before }}</td>
                    <td>{{ $m->stock_after }}</td>
                    <td>{{ $m->order ? $m->order->order_number : '—' }}</td>
                    <td class="text-muted">{{ Str::limit($m->reason, 40) }}</td>
                    <td class="text-muted">{{ $m->created_at->format('d M Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="padding:16px">{{ $movements->links() }}</div>
        @endif
    </div>
</div>
@endsection
