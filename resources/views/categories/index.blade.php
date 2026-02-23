@extends('layouts.app')
@section('title', 'Categories')
@section('page-title', 'Categories')
@section('content')
<div class="page-header">
    <h1>Categories <span class="text-muted">({{ $categories->total() }})</span></h1>
    <a href="{{ route('categories.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Category</a>
</div>
<div class="card">
    <div class="card-body" style="padding:0">
        @if($categories->isEmpty())
            <div class="empty-state"><i class="fas fa-tags"></i><p>No categories yet. <a href="{{ route('categories.create') }}">Create one</a></p></div>
        @else
        <table>
            <thead><tr><th>#</th><th>Name</th><th>Description</th><th>Menu Items</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($categories as $cat)
                <tr>
                    <td class="text-muted">{{ $cat->id }}</td>
                    <td><strong>{{ $cat->name }}</strong></td>
                    <td class="text-muted">{{ $cat->description ?? '—' }}</td>
                    <td>{{ $cat->menu_items_count }}</td>
                    <td><span class="badge {{ $cat->is_active ? 'badge-ok' : 'badge-cancelled' }}">{{ $cat->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td>
                        <div class="flex-gap">
                            <a href="{{ route('categories.edit', $cat) }}" class="btn btn-outline btn-sm"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{ route('categories.destroy', $cat) }}" onsubmit="return confirm('Delete this category?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="padding:16px">{{ $categories->links() }}</div>
        @endif
    </div>
</div>
@endsection
