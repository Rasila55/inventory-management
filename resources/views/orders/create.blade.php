@extends('layouts.app')
@section('title', 'New Order')
@section('page-title', 'Create New Order')
@section('content')

<div style="max-width:900px">
<a href="{{ route('orders.index') }}" class="btn btn-outline btn-sm" style="margin-bottom:16px"><i class="fas fa-arrow-left"></i> Back</a>

<div class="card">
    <div class="card-header"><h2><i class="fas fa-plus-circle"></i> New Order</h2></div>
    <div class="card-body">
        <form method="POST" action="{{ route('orders.store') }}" id="orderForm">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label>Customer Name (optional)</label>
                    <input type="text" name="customer_name" value="{{ old('customer_name') }}" placeholder="Walk-in / Table No.">
                </div>
                <div class="form-group">
                    <label>Notes</label>
                    <input type="text" name="notes" value="{{ old('notes') }}" placeholder="Special requests...">
                </div>
            </div>

            <hr style="margin:20px 0;border:none;border-top:1px solid #eee">
            <h3 style="margin-bottom:16px;font-size:.95rem;color:var(--dark)"><i class="fas fa-list"></i> Order Items</h3>

            <div id="order-items">
                <div class="order-item" style="display:grid;grid-template-columns:1fr auto auto;gap:12px;align-items:end;margin-bottom:12px">
                    <div class="form-group" style="margin-bottom:0">
                        <label>Menu Item *</label>
                        <select name="items[0][menu_item_id]" class="menu-select" required onchange="updatePrice(this)">
                            <option value="">— Select Menu Item —</option>
                            @foreach($menuItems->groupBy('category.name') as $cat => $items)
                                <optgroup label="{{ $cat }}">
                                    @foreach($items as $item)
                                        <option value="{{ $item->id }}" data-price="{{ $item->price }}">{{ $item->name }} — Rs. {{ number_format($item->price,2) }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom:0;width:100px">
                        <label>Qty *</label>
                        <input type="number" name="items[0][quantity]" min="1" value="1" required onchange="updateTotal()">
                    </div>
                    <div style="padding-bottom:2px">
                        <button type="button" class="btn btn-danger btn-sm btn-icon remove-item" onclick="removeItem(this)"><i class="fas fa-times"></i></button>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-outline btn-sm" onclick="addItem()" style="margin-bottom:20px">
                <i class="fas fa-plus"></i> Add Another Item
            </button>

            <div style="background:#f8f9fa;border-radius:8px;padding:16px;margin-bottom:20px">
                <div style="font-size:.9rem;color:#666">Estimated Total</div>
                <div style="font-size:1.5rem;font-weight:700;color:var(--dark)" id="total-display">Rs. 0.00</div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Place Order</button>
        </form>
    </div>
</div>
</div>

<script>
const menuItems = @json($menuItems->keyBy('id'));
let itemIndex = 1;

function addItem() {
    const container = document.getElementById('order-items');
    const div = document.createElement('div');
    div.className = 'order-item';
    div.style.cssText = 'display:grid;grid-template-columns:1fr auto auto;gap:12px;align-items:end;margin-bottom:12px';
    div.innerHTML = `
        <div class="form-group" style="margin-bottom:0">
            <label>Menu Item *</label>
            <select name="items[${itemIndex}][menu_item_id]" class="menu-select" required onchange="updatePrice(this)">
                <option value="">— Select Menu Item —</option>
                ${buildOptions()}
            </select>
        </div>
        <div class="form-group" style="margin-bottom:0;width:100px">
            <label>Qty *</label>
            <input type="number" name="items[${itemIndex}][quantity]" min="1" value="1" required onchange="updateTotal()">
        </div>
        <div style="padding-bottom:2px">
            <button type="button" class="btn btn-danger btn-sm btn-icon" onclick="removeItem(this)"><i class="fas fa-times"></i></button>
        </div>`;
    container.appendChild(div);
    itemIndex++;
}

function buildOptions() {
    // Group by category
    const groups = {};
    Object.values(menuItems).forEach(item => {
        const cat = item.category?.name || 'Other';
        if (!groups[cat]) groups[cat] = [];
        groups[cat].push(item);
    });
    return Object.entries(groups).map(([cat, items]) =>
        `<optgroup label="${cat}">${items.map(i => `<option value="${i.id}" data-price="${i.price}">${i.name} — Rs. ${parseFloat(i.price).toFixed(2)}</option>`).join('')}</optgroup>`
    ).join('');
}

function removeItem(btn) {
    const items = document.querySelectorAll('.order-item');
    if (items.length <= 1) { alert('At least one item is required'); return; }
    btn.closest('.order-item').remove();
    updateTotal();
}

function updatePrice(select) { updateTotal(); }

function updateTotal() {
    let total = 0;
    document.querySelectorAll('.order-item').forEach(row => {
        const select = row.querySelector('select');
        const qty = row.querySelector('input[type=number]');
        const opt = select.selectedOptions[0];
        if (opt && opt.dataset.price) {
            total += parseFloat(opt.dataset.price) * parseInt(qty.value || 1);
        }
    });
    document.getElementById('total-display').textContent = 'Rs. ' + total.toFixed(2);
}
</script>
@endsection
