@extends('layouts.app')
@section('title', 'Order '.$order->order_number)
@section('page-title', 'Order Details')
@section('content')

<div class="page-header">
    <div>
        <h1>{{ $order->order_number }}</h1>
        <div class="text-muted" style="margin-top:4px">Created: {{ $order->created_at->format('d M Y H:i') }}</div>
    </div>
    <div class="flex-gap">
        <a href="{{ route('orders.index') }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
        @if($order->status !== 'delivered' && $order->status !== 'cancelled')
        <form method="POST" action="{{ route('orders.destroy', $order) }}" onsubmit="return confirm('Cancel this order?')">
            @csrf @method('DELETE')
            <button class="btn btn-danger btn-sm"><i class="fas fa-times"></i> Cancel Order</button>
        </form>
        @endif
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;align-items:start">

    {{-- Left: Order Items --}}
    <div>
        <div class="card" style="margin-bottom:20px">
            <div class="card-header">
                <h2><i class="fas fa-list"></i> Order Items</h2>
                <span class="badge badge-{{ $order->status }}" style="font-size:.85rem">{{ ucfirst($order->status) }}</span>
            </div>
            <div class="card-body" style="padding:0">
                <table>
                    <thead><tr><th>Item</th><th>Category</th><th>Unit Price</th><th>Qty</th><th>Subtotal</th></tr></thead>
                    <tbody>
                        @foreach($order->orderItems as $item)
                        <tr>
                            <td><strong>{{ $item->menuItem->name }}</strong></td>
                            <td><span class="badge badge-preparing">{{ $item->menuItem->category->name }}</span></td>
                            <td>Rs. {{ number_format($item->unit_price, 2) }}</td>
                            <td>× {{ $item->quantity }}</td>
                            <td><strong>Rs. {{ number_format($item->subtotal, 2) }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" style="text-align:right;font-weight:600;padding:14px">Total Amount:</td>
                            <td style="font-size:1.1rem;font-weight:700;color:var(--primary)">Rs. {{ number_format($order->total_amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Stock Deductions Log (if delivered) --}}
        @if($order->status === 'delivered' && $order->stockMovements->isNotEmpty())
        <div class="card">
            <div class="card-header"><h2><i class="fas fa-boxes"></i> Stock Deductions Made</h2></div>
            <div class="card-body" style="padding:0">
                <table>
                    <thead><tr><th>Ingredient</th><th>Deducted</th><th>Stock Before</th><th>Stock After</th></tr></thead>
                    <tbody>
                        @foreach($order->stockMovements as $m)
                        <tr>
                            <td><strong>{{ $m->ingredient->name }}</strong></td>
                            <td style="color:#e63946;font-weight:600">{{ $m->quantity }} {{ $m->ingredient->unit }}</td>
                            <td class="text-muted">{{ $m->stock_before }}</td>
                            <td>{{ $m->stock_after }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    {{-- Right: Status & Actions --}}
    <div>
        <div class="card" style="margin-bottom:20px">
            <div class="card-header"><h2><i class="fas fa-info-circle"></i> Order Info</h2></div>
            <div class="card-body">
                <table style="width:100%">
                    <tr><td style="color:#888;padding:6px 0">Customer</td><td>{{ $order->customer_name ?? '—' }}</td></tr>
                    <tr><td style="color:#888;padding:6px 0">Status</td><td><span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td></tr>
                    <tr><td style="color:#888;padding:6px 0">Notes</td><td>{{ $order->notes ?? '—' }}</td></tr>
                    @if($order->delivered_at)
                    <tr><td style="color:#888;padding:6px 0">Delivered At</td><td>{{ $order->delivered_at->format('d M Y H:i') }}</td></tr>
                    @endif
                </table>
            </div>
        </div>

        @if($order->status !== 'delivered' && $order->status !== 'cancelled')
        <div class="card">
            <div class="card-header"><h2><i class="fas fa-exchange-alt"></i> Update Status</h2></div>
            <div class="card-body">
                @if($order->status === 'pending')
                <form method="POST" action="{{ route('orders.update-status', $order) }}" style="margin-bottom:12px">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="preparing">
                    <button type="submit" class="btn btn-secondary" style="width:100%"><i class="fas fa-fire"></i> Mark as Preparing</button>
                </form>
                @endif

                <form method="POST" action="{{ route('orders.update-status', $order) }}" onsubmit="return confirm('Mark as delivered? This will deduct stock from inventory.')">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="delivered">
                    <button type="submit" class="btn btn-success" style="width:100%">
                        <i class="fas fa-check-circle"></i> Mark as Delivered
                    </button>
                </form>
                <p class="text-muted" style="margin-top:8px;font-size:.78rem">
                    <i class="fas fa-info-circle"></i> Marking as delivered will automatically deduct ingredient stock based on recipes.
                </p>
            </div>
        </div>
        @endif

        @if($order->status === 'delivered')
        <div class="alert alert-success" style="margin-top:16px">
            <i class="fas fa-check-circle"></i> Order delivered. Stock has been deducted.
        </div>
        @endif
    </div>

</div>
@endsection
