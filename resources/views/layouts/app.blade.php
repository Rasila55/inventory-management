<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Restaurant IMS') — Digital Waiter Nepal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* ===== CSS Variables ===== */
        :root {
            --primary: #e63946;
            --primary-dark: #c1121f;
            --secondary: #457b9d;
            --success: #2d6a4f;
            --warning: #f4a261;
            --danger: #e63946;
            --dark: #1d3557;
            --light: #f8f9fa;
            --sidebar-w: 260px;
            --radius: 10px;
            --shadow: 0 2px 12px rgba(0,0,0,.08);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; background: #f0f2f5; color: #333; display: flex; min-height: 100vh; }

        /* ===== Sidebar ===== */
        .sidebar {
            width: var(--sidebar-w); background: var(--dark);
            position: fixed; top: 0; left: 0; height: 100vh;
            overflow-y: auto; display: flex; flex-direction: column; z-index: 100;
        }
        .sidebar-brand {
            padding: 20px 24px; background: var(--primary);
            font-size: 1.1rem; font-weight: 700; color: #fff;
            display: flex; align-items: center; gap: 10px;
        }
        .sidebar-brand i { font-size: 1.3rem; }
        .sidebar-brand span { font-size: .75rem; display: block; font-weight: 400; opacity: .85; }
        .sidebar-nav { padding: 16px 0; flex: 1; }
        .nav-section { padding: 8px 24px 4px; font-size: .7rem; text-transform: uppercase;
            letter-spacing: 1px; color: rgba(255,255,255,.4); margin-top: 8px; }
        .nav-item { display: flex; align-items: center; gap: 12px;
            padding: 11px 24px; color: rgba(255,255,255,.75); text-decoration: none;
            font-size: .9rem; transition: all .2s; }
        .nav-item:hover, .nav-item.active { background: rgba(255,255,255,.1); color: #fff; }
        .nav-item.active { border-left: 3px solid var(--primary); }
        .nav-item i { width: 20px; text-align: center; }
        .nav-badge { margin-left: auto; background: var(--primary);
            color: #fff; font-size: .7rem; padding: 2px 7px; border-radius: 20px; }

        /* ===== Main Content ===== */
        .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; }
        .topbar {
            background: #fff; padding: 0 28px; height: 64px;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 1px 4px rgba(0,0,0,.08); position: sticky; top: 0; z-index: 50;
        }
        .topbar h1 { font-size: 1.2rem; font-weight: 600; color: var(--dark); }
        .topbar-actions { display: flex; gap: 12px; align-items: center; }
        .topbar-actions a { color: #666; font-size: .9rem; text-decoration: none; }
        .content { padding: 28px; flex: 1; }

        /* ===== Cards ===== */
        .card { background: #fff; border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
        .card-header { padding: 16px 20px; border-bottom: 1px solid #eee;
            display: flex; align-items: center; justify-content: space-between; }
        .card-header h2 { font-size: 1rem; font-weight: 600; color: var(--dark); }
        .card-body { padding: 20px; }

        /* ===== Stat Cards ===== */
        .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 28px; }
        .stat-card { background: #fff; border-radius: var(--radius); padding: 20px; box-shadow: var(--shadow);
            display: flex; align-items: center; gap: 16px; }
        .stat-icon { width: 52px; height: 52px; border-radius: 12px; display: grid; place-items: center;
            font-size: 1.4rem; color: #fff; }
        .stat-icon.red    { background: linear-gradient(135deg, #e63946, #c1121f); }
        .stat-icon.blue   { background: linear-gradient(135deg, #457b9d, #1d3557); }
        .stat-icon.green  { background: linear-gradient(135deg, #2d6a4f, #40916c); }
        .stat-icon.orange { background: linear-gradient(135deg, #f4a261, #e76f51); }
        .stat-icon.purple { background: linear-gradient(135deg, #7b2d8b, #9d4edd); }
        .stat-label { font-size: .78rem; color: #888; margin-bottom: 2px; }
        .stat-value { font-size: 1.6rem; font-weight: 700; color: var(--dark); }

        /* ===== Buttons ===== */
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px;
            border-radius: 7px; font-size: .875rem; font-weight: 500; cursor: pointer;
            border: none; text-decoration: none; transition: all .2s; }
        .btn-primary  { background: var(--primary); color: #fff; }
        .btn-primary:hover  { background: var(--primary-dark); }
        .btn-secondary { background: var(--secondary); color: #fff; }
        .btn-secondary:hover { background: #1d3557; }
        .btn-success  { background: #2d6a4f; color: #fff; }
        .btn-success:hover  { background: #1b4332; }
        .btn-warning  { background: var(--warning); color: #fff; }
        .btn-warning:hover  { background: #e76f51; }
        .btn-danger   { background: var(--danger); color: #fff; }
        .btn-danger:hover   { background: var(--primary-dark); }
        .btn-outline  { background: transparent; border: 1.5px solid #ddd; color: #555; }
        .btn-outline:hover  { border-color: var(--primary); color: var(--primary); }
        .btn-sm { padding: 5px 10px; font-size: .8rem; }
        .btn-icon { padding: 7px 10px; }

        /* ===== Tables ===== */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f8f9fa; font-size: .78rem; text-transform: uppercase;
            letter-spacing: .5px; color: #888; padding: 10px 14px; text-align: left; }
        td { padding: 12px 14px; border-bottom: 1px solid #f0f0f0; font-size: .9rem; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #fafafa; }

        /* ===== Forms ===== */
        .form-group { margin-bottom: 18px; }
        label { display: block; font-size: .85rem; font-weight: 500; color: #555; margin-bottom: 6px; }
        input, select, textarea {
            width: 100%; padding: 9px 13px; border: 1.5px solid #e0e0e0;
            border-radius: 7px; font-size: .9rem; transition: border .2s; outline: none;
            font-family: inherit;
        }
        input:focus, select:focus, textarea:focus { border-color: var(--primary); }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
        .error-msg { color: var(--danger); font-size: .8rem; margin-top: 4px; }
        .form-hint { color: #888; font-size: .78rem; margin-top: 4px; }

        /* ===== Alerts ===== */
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;
            display: flex; align-items: center; gap: 10px; font-size: .9rem; }
        .alert-success { background: #d1fae5; color: #065f46; }
        .alert-error   { background: #fee2e2; color: #991b1b; }
        .alert-warning { background: #fef3c7; color: #92400e; }
        .alert-info    { background: #dbeafe; color: #1e40af; }

        /* ===== Badges ===== */
        .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: .75rem; font-weight: 600; }
        .badge-pending   { background: #fef3c7; color: #92400e; }
        .badge-preparing { background: #dbeafe; color: #1e40af; }
        .badge-delivered { background: #d1fae5; color: #065f46; }
        .badge-cancelled { background: #f3f4f6; color: #6b7280; }
        .badge-low       { background: #fee2e2; color: #991b1b; }
        .badge-ok        { background: #d1fae5; color: #065f46; }

        /* ===== Pagination ===== */
        .pagination { display: flex; gap: 4px; margin-top: 20px; justify-content: center; }
        .pagination a, .pagination span {
            padding: 6px 12px; border-radius: 6px; border: 1px solid #ddd;
            font-size: .85rem; text-decoration: none; color: #555;
        }
        .pagination .active { background: var(--primary); border-color: var(--primary); color: #fff; }

        /* ===== Misc ===== */
        .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px; }
        .page-header h1 { font-size: 1.4rem; font-weight: 700; color: var(--dark); }
        .empty-state { text-align: center; padding: 60px 20px; color: #aaa; }
        .empty-state i { font-size: 3rem; margin-bottom: 12px; }
        img.menu-thumb { width: 48px; height: 48px; object-fit: cover; border-radius: 8px; }
        .stock-bar { height: 6px; border-radius: 3px; background: #eee; overflow: hidden; margin-top: 4px; }
        .stock-bar-fill { height: 100%; border-radius: 3px; }
        .text-muted { color: #888; font-size: .85rem; }
        .mt-2 { margin-top: 8px; }
        .flex-gap { display: flex; gap: 8px; flex-wrap: wrap; }
    </style>
</head>
<body>

{{-- ===== Sidebar ===== --}}
<aside class="sidebar">
    <div class="sidebar-brand">
        <i class="fas fa-utensils"></i>
        <div>
            Restaurant IMS
            <span>Digital Waiter Nepal</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">Main</div>
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i> Dashboard
        </a>

        <div class="nav-section">Menu</div>
        <a href="{{ route('categories.index') }}" class="nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <i class="fas fa-tags"></i> Categories
        </a>
        <a href="{{ route('menu-items.index') }}" class="nav-item {{ request()->routeIs('menu-items.*') ? 'active' : '' }}">
            <i class="fas fa-hamburger"></i> Menu Items
        </a>

        <div class="nav-section">Inventory</div>
        <a href="{{ route('ingredients.index') }}" class="nav-item {{ request()->routeIs('ingredients.*') ? 'active' : '' }}">
            <i class="fas fa-boxes"></i> Ingredients
            @php $lowStock = \App\Models\Ingredient::lowStock()->count(); @endphp
            @if($lowStock > 0)
                <span class="nav-badge">{{ $lowStock }}</span>
            @endif
        </a>
        <a href="{{ route('stock.movements') }}" class="nav-item {{ request()->routeIs('stock.*') ? 'active' : '' }}">
            <i class="fas fa-history"></i> Stock Movements
        </a>

        <div class="nav-section">Orders</div>
        <a href="{{ route('orders.index') }}" class="nav-item {{ request()->routeIs('orders.*') ? 'active' : '' }}">
            <i class="fas fa-receipt"></i> Orders
            @php $pending = \App\Models\Order::where('status','pending')->count(); @endphp
            @if($pending > 0)
                <span class="nav-badge">{{ $pending }}</span>
            @endif
        </a>
        <a href="{{ route('orders.create') }}" class="nav-item">
            <i class="fas fa-plus-circle"></i> New Order
        </a>
    </nav>
</aside>

{{-- ===== Main ===== --}}
<main class="main">
    <div class="topbar">
        <h1>@yield('page-title', 'Dashboard')</h1>
        <div class="topbar-actions">
            <a href="{{ route('orders.create') }}"><i class="fas fa-plus"></i> New Order</a>
        </div>
    </div>

    <div class="content">
        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        @yield('content')
    </div>
</main>

</body>
</html>
