@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Stat Cards --}}
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-receipt"></i></div>
        <div>
            <div class="stat-label">Total Orders</div>
            <div class="stat-value">{{ $stats['total_orders'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-clock"></i></div>
        <div>
            <div class="stat-label">Pending Orders</div>
            <div class="stat-value">{{ $stats['pending_orders'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
        <div>
            <div class="stat-label">Delivered Today</div>
            <div class="stat-value">{{ $stats['delivered_today'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-coins"></i></div>
        <div>
            <div class="stat-label">Revenue Today</div>
            <div class="stat-value">Rs. {{ number_format($stats['revenue_today'], 2) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-hamburger"></i></div>
        <div>
            <div class="stat-label">Menu Items</div>
            <div class="stat-value">{{ $stats['total_menu_items'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-boxes"></i></div>
        <div>
            <div class="stat-label">Ingredients</div>
            <div class="stat-value">{{ $stats['total_ingredients'] }}</div>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">

    {{-- Low Stock Alert --}}
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-exclamation-triangle" style="color:#f4a261"></i> Low Stock Alerts</h2>
            <a href="{{ route('ingredients.index', ['low_stock' => 1]) }}" class="btn btn-outline btn-sm">View All</a>
        </div>
        <div class="card-body" style="padding:0">
            @if($lowStockIngredients->isEmpty())
                <div class="empty-state"><i class="fas fa-check-circle" style="color:#2d6a4f"></i><p>All stock levels are healthy!</p></div>
            @else
                <table>
                    <thead><tr><th>Ingredient</th><th>Unit</th><th>Stock</th><th>Min</th></tr></thead>
                    <tbody>
                        @foreach($lowStockIngredients as $ing)
                        <tr>
                            <td><strong>{{ $ing->name }}</strong></td>
                            <td>{{ $ing->unit }}</td>
                            <td><span class="badge badge-low">{{ $ing->current_stock }}</span></td>
                            <td class="text-muted">{{ $ing->minimum_stock }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-receipt"></i> Recent Orders</h2>
            <a href="{{ route('orders.index') }}" class="btn btn-outline btn-sm">View All</a>
        </div>
        <div class="card-body" style="padding:0">
            @if($recentOrders->isEmpty())
                <div class="empty-state"><i class="fas fa-receipt"></i><p>No orders yet</p></div>
            @else
                <table>
                    <thead><tr><th>Order #</th><th>Items</th><th>Amount</th><th>Status</th></tr></thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                        <tr>
                            <td><a href="{{ route('orders.show', $order) }}" style="color:var(--primary);text-decoration:none">{{ $order->order_number }}</a></td>
                            <td>{{ $order->orderItems->count() }} item(s)</td>
                            <td>Rs. {{ number_format($order->total_amount, 2) }}</td>
                            <td><span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

</div>

{{-- Recent Stock Movements --}}
<div class="card" style="margin-top:20px">
    <div class="card-header">
        <h2><i class="fas fa-history"></i> Recent Stock Movements</h2>
        <a href="{{ route('stock.movements') }}" class="btn btn-outline btn-sm">View All</a>
    </div>
    <div class="card-body" style="padding:0">
        @if($recentMovements->isEmpty())
            <div class="empty-state"><i class="fas fa-history"></i><p>No movements yet</p></div>
        @else
            <table>
                <thead><tr><th>Ingredient</th><th>Type</th><th>Qty Change</th><th>Before</th><th>After</th><th>Order</th><th>Time</th></tr></thead>
                <tbody>
                    @foreach($recentMovements as $m)
                    <tr>
                        <td><strong>{{ $m->ingredient->name }}</strong></td>
                        <td>
                            @if($m->type === 'deduction')
                                <span class="badge badge-low">Deduction</span>
                            @else
                                <span class="badge badge-ok">{{ ucfirst(str_replace('_', ' ', $m->type)) }}</span>
                            @endif
                        </td>
                        <td style="color:{{ $m->quantity < 0 ? '#e63946' : '#2d6a4f' }}">
                            {{ $m->quantity > 0 ? '+' : '' }}{{ $m->quantity }} {{ $m->ingredient->unit }}
                        </td>
                        <td class="text-muted">{{ $m->stock_before }}</td>
                        <td>{{ $m->stock_after }}</td>
                        <td>{{ $m->order ? $m->order->order_number : '—' }}</td>
                        <td class="text-muted">{{ $m->created_at->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

@endsection
