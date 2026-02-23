<?php

namespace App\Http\Controllers;

use App\Models\{Category, MenuItem, Ingredient, Order, StockMovement};
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_categories'  => Category::count(),
            'total_menu_items'  => MenuItem::count(),
            'total_ingredients' => Ingredient::count(),
            'total_orders'      => Order::count(),
            'pending_orders'    => Order::where('status', 'pending')->count(),
            'delivered_today'   => Order::where('status', 'delivered')
                                        ->whereDate('delivered_at', today())->count(),
            'revenue_today'     => Order::where('status', 'delivered')
                                        ->whereDate('delivered_at', today())
                                        ->sum('total_amount'),
        ];

        // Ingredients with low stock
        $lowStockIngredients = Ingredient::lowStock()->get();

        // Recent orders
        $recentOrders = Order::with('orderItems.menuItem')
                             ->latest()
                             ->take(10)
                             ->get();

        // Recent stock movements
        $recentMovements = StockMovement::with(['ingredient', 'order'])
                                        ->latest()
                                        ->take(10)
                                        ->get();

        return view('dashboard.index', compact(
            'stats', 'lowStockIngredients', 'recentOrders', 'recentMovements'
        ));
    }
}
