@extends('layouts.app')
@section('title', 'Orders')
@section('page-title', 'Orders')
@section('content')
<div class="page-header">
    <h1>Orders <span class="text-muted">({{ $orders->total() }})</span></h1>
    <a href="{{ route('orders.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> New Order</a>
</div>

{{-- Filter bar --}}
<div class="card" style="margin-bottom:16px;padding:14px 20px">
    <form method="GET" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap">
        <div>
            <select name="status" style="padding:7px 12px;border-radius:7px;border:1.5px solid #e0e0e0">
                <option value="">All Statuses</option>
                @foreach(['pending','preparing','delivered','cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <input type="date" name="date" value="{{ request('date') }}" style="padding:7px 12px;border-radius:7px;border:1.5px solid #e0e0e0">
        </div>
        <button type="submit" class="btn btn-secondary btn-sm"><i class="fas fa-search"></i> Filter</button>
        <a href="{{ route('orders.index') }}" class="btn btn-outline btn-sm">Clear</a>
    </form>
</div>

<div class="card">
    <div class="card-body" style="padding:0">
        @if($orders->isEmpty())
            <div class="empty-state"><i class="fas fa-receipt"></i><p>No orders found.</p></div>
        @else
        <table>
            <thead><tr><th>Order #</th><th>Customer</th><th>Items</th><th>Total</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td><a href="{{ route('orders.show', $order) }}" style="color:var(--primary);font-weight:600;text-decoration:none">{{ $order->order_number }}</a></td>
                    <td>{{ $order->customer_name ?? '—' }}</td>
                    <td>{{ $order->orderItems->count() }} item(s)</td>
                    <td>Rs. {{ number_format($order->total_amount, 2) }}</td>
                    <td><span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                    <td class="text-muted">{{ $order->created_at->format('d M Y H:i') }}</td>
                    <td>
                        <div class="flex-gap">
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-outline btn-sm"><i class="fas fa-eye"></i></a>
                            @if($order->status !== 'delivered' && $order->status !== 'cancelled')
                            <form method="POST" action="{{ route('orders.destroy', $order) }}" onsubmit="return confirm('Cancel this order?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"><i class="fas fa-times"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="padding:16px">{{ $orders->links() }}</div>
        @endif
    </div>
</div>
@endsection
