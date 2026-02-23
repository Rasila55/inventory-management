@extends('layouts.app')
@section('title', 'Stock Movements')
@section('page-title', 'Stock Movement Log')
@section('content')

<div class="page-header">
    <h1>Stock Movement History</h1>
</div>

<div class="card" style="margin-bottom:16px;padding:14px 20px">
    <form method="GET" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap">
        <select name="ingredient_id" style="padding:7px 12px;border-radius:7px;border:1.5px solid #e0e0e0">
            <option value="">All Ingredients</option>
            @foreach($ingredients as $ing)
                <option value="{{ $ing->id }}" {{ request('ingredient_id') == $ing->id ? 'selected' : '' }}>{{ $ing->name }}</option>
            @endforeach
        </select>
        <select name="type" style="padding:7px 12px;border-radius:7px;border:1.5px solid #e0e0e0">
            <option value="">All Types</option>
            @foreach(['deduction','manual_add','adjustment','waste'] as $t)
                <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$t)) }}</option>
            @endforeach
        </select>
        <input type="date" name="date" value="{{ request('date') }}" style="padding:7px 12px;border-radius:7px;border:1.5px solid #e0e0e0">
        <button type="submit" class="btn btn-secondary btn-sm"><i class="fas fa-search"></i> Filter</button>
        <a href="{{ route('stock.movements') }}" class="btn btn-outline btn-sm">Clear</a>
    </form>
</div>

<div class="card">
    <div class="card-body" style="padding:0">
        @if($movements->isEmpty())
            <div class="empty-state"><i class="fas fa-history"></i><p>No stock movements recorded.</p></div>
        @else
        <table>
            <thead><tr><th>Date</th><th>Ingredient</th><th>Type</th><th>Qty Change</th><th>Before</th><th>After</th><th>Order</th><th>Reason</th></tr></thead>
            <tbody>
                @foreach($movements as $m)
                <tr>
                    <td class="text-muted">{{ $m->created_at->format('d M Y H:i') }}</td>
                    <td><strong>{{ $m->ingredient->name }}</strong> <small class="text-muted">({{ $m->ingredient->unit }})</small></td>
                    <td>
                        @if($m->type === 'deduction')
                            <span class="badge badge-low">Deduction</span>
                        @elseif($m->type === 'manual_add')
                            <span class="badge badge-ok">Manual Add</span>
                        @else
                            <span class="badge badge-cancelled">{{ ucfirst(str_replace('_',' ',$m->type)) }}</span>
                        @endif
                    </td>
                    <td style="color:{{ $m->quantity < 0 ? '#e63946' : '#2d6a4f' }};font-weight:600">
                        {{ $m->quantity > 0 ? '+' : '' }}{{ $m->quantity }}
                    </td>
                    <td class="text-muted">{{ $m->stock_before }}</td>
                    <td>{{ $m->stock_after }}</td>
                    <td>
                        @if($m->order)
                            <a href="{{ route('orders.show', $m->order) }}" style="color:var(--primary);text-decoration:none">{{ $m->order->order_number }}</a>
                        @else — @endif
                    </td>
                    <td class="text-muted">{{ Str::limit($m->reason, 50) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="padding:16px">{{ $movements->links() }}</div>
        @endif
    </div>
</div>
@endsection
