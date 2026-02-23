@extends('layouts.app')
@section('title', 'Stock Movements')
@section('page-title', 'Stock Movement Logs')
@section('breadcrumb')
    <li class="breadcrumb-item active">Stock Movements</li>
@endsection

@section('content')

{{-- Filters --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Ingredient</label>
                <select name="ingredient_id" class="form-select form-select-sm">
                    <option value="">All Ingredients</option>
                    @foreach($ingredients as $ing)
                        <option value="{{ $ing->id }}" {{ request('ingredient_id') == $ing->id ? 'selected' : '' }}>
                            {{ $ing->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold mb-1">Type</label>
                <select name="type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    <option value="deduction"  {{ request('type') === 'deduction'  ? 'selected' : '' }}>Deduction</option>
                    <option value="manual_add" {{ request('type') === 'manual_add' ? 'selected' : '' }}>Manual Add</option>
                    <option value="adjustment" {{ request('type') === 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                    <option value="waste"      {{ request('type') === 'waste'      ? 'selected' : '' }}>Waste</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold mb-1">From Date</label>
                <input type="date" name="from" class="form-control form-control-sm" value="{{ request('from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold mb-1">To Date</label>
                <input type="date" name="to" class="form-control form-control-sm" value="{{ request('to') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary btn-sm me-1">
                    <i class="bi bi-search"></i> Filter
                </button>
                <a href="{{ route('stock-movements.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date & Time</th>
                        <th>Ingredient</th>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>Before</th>
                        <th>After</th>
                        <th>Order</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movements as $mv)
                        <tr>
                            <td class="text-muted small">{{ $mv->created_at->format('d M Y, H:i') }}</td>
                            <td class="fw-semibold">{{ $mv->ingredient->name }}</td>
                            <td>
                                <span class="badge bg-{{ $mv->type_badge }}">
                                    {{ str_replace('_', ' ', $mv->type) }}
                                </span>
                            </td>
                            <td class="{{ $mv->type === 'deduction' ? 'text-danger' : 'text-success' }} fw-semibold">
                                {{ $mv->type === 'deduction' ? '-' : '+' }}{{ $mv->quantity }} {{ $mv->ingredient->unit }}
                            </td>
                            <td class="text-muted">{{ $mv->stock_before }}</td>
                            <td class="fw-semibold {{ $mv->ingredient->isLowStock() && $mv->type === 'deduction' ? 'text-danger' : '' }}">
                                {{ $mv->stock_after }}
                            </td>
                            <td>
                                @if($mv->order)
                                    <a href="{{ route('orders.show', $mv->order) }}" class="text-decoration-none">
                                        {{ $mv->order->order_number }}
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-muted small">{{ $mv->notes ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No stock movements found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($movements->hasPages())
        <div class="card-footer">{{ $movements->links() }}</div>
    @endif
</div>
@endsection
