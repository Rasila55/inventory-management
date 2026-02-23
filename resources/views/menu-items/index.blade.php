@extends('layouts.app')
@section('title', 'Menu Items')
@section('page-title', 'Menu Items')
@section('content')
<div class="page-header">
    <h1>Menu Items <span class="text-muted">({{ $menuItems->total() }})</span></h1>
    <a href="{{ route('menu-items.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Menu Item</a>
</div>
<div class="card">
    <div class="card-body" style="padding:0">
        @if($menuItems->isEmpty())
            <div class="empty-state"><i class="fas fa-hamburger"></i><p>No menu items yet.</p></div>
        @else
        <table>
            <thead><tr><th>Image</th><th>Name</th><th>Category</th><th>Price</th><th>Ingredients</th><th>Available</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($menuItems as $item)
                <tr>
                    <td>
                        @if($item->image)
                            <img src="{{ asset('storage/'.$item->image) }}" class="menu-thumb" alt="{{ $item->name }}">
                        @else
                            <div style="width:48px;height:48px;background:#f0f2f5;border-radius:8px;display:grid;place-items:center;color:#bbb"><i class="fas fa-utensils"></i></div>
                        @endif
                    </td>
                    <td><strong>{{ $item->name }}</strong></td>
                    <td><span class="badge badge-preparing">{{ $item->category->name }}</span></td>
                    <td>Rs. {{ number_format($item->price, 2) }}</td>
                    <td>{{ $item->ingredients_count }} ingredient(s)</td>
                    <td><span class="badge {{ $item->is_available ? 'badge-ok' : 'badge-cancelled' }}">{{ $item->is_available ? 'Yes' : 'No' }}</span></td>
                    <td>
                        <div class="flex-gap">
                            <a href="{{ route('recipes.index', $item) }}" class="btn btn-secondary btn-sm" title="Manage Recipe"><i class="fas fa-book"></i></a>
                            <a href="{{ route('menu-items.edit', $item) }}" class="btn btn-outline btn-sm"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{ route('menu-items.destroy', $item) }}" onsubmit="return confirm('Delete this menu item?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="padding:16px">{{ $menuItems->links() }}</div>
        @endif
    </div>
</div>
@endsection
