<?php

namespace App\Http\Controllers;

use App\Models\{StockMovement, Ingredient};
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $query = StockMovement::with(['ingredient', 'order']);

        if ($request->filled('ingredient_id')) {
            $query->where('ingredient_id', $request->ingredient_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $movements   = $query->latest()->paginate(30);
        $ingredients = Ingredient::orderBy('name')->get();

        return view('stock.movements', compact('movements', 'ingredients'));
    }
}
