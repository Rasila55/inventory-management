<?php

namespace App\Http\Controllers;

use App\Models\{Ingredient, StockMovement};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IngredientController extends Controller
{
    public function index(Request $request)
    {
        $query = Ingredient::query();

        // Filter by low stock
        if ($request->boolean('low_stock')) {
            $query->lowStock();
        }

        $ingredients = $query->latest()->paginate(20);
        return view('ingredients.index', compact('ingredients'));
    }

    public function create()
    {
        return view('ingredients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:150',
            'unit'          => 'required|in:kg,gram,piece,liter,ml,dozen',
            'current_stock' => 'required|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:0',
            'cost_per_unit' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $ingredient = Ingredient::create($validated);

            // Log the initial stock as a manual_add movement
            if ($validated['current_stock'] > 0) {
                StockMovement::create([
                    'ingredient_id' => $ingredient->id,
                    'type'          => 'manual_add',
                    'quantity'      => $validated['current_stock'],
                    'stock_before'  => 0,
                    'stock_after'   => $validated['current_stock'],
                    'reason'        => 'Initial stock entry',
                ]);
            }
        });

        return redirect()->route('ingredients.index')
                         ->with('success', 'Ingredient added successfully!');
    }

    public function show(Ingredient $ingredient)
    {
        $movements = $ingredient->stockMovements()
                                ->with('order')
                                ->latest()
                                ->paginate(20);
        return view('ingredients.show', compact('ingredient', 'movements'));
    }

    public function edit(Ingredient $ingredient)
    {
        return view('ingredients.edit', compact('ingredient'));
    }

    public function update(Request $request, Ingredient $ingredient)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:150',
            'unit'          => 'required|in:kg,gram,piece,liter,ml,dozen',
            'minimum_stock' => 'required|numeric|min:0',
            'cost_per_unit' => 'nullable|numeric|min:0',
        ]);

        // Note: current_stock is NOT updated here; use stockIn() for that
        $ingredient->update($validated);

        return redirect()->route('ingredients.index')
                         ->with('success', 'Ingredient updated successfully!');
    }

    public function destroy(Ingredient $ingredient)
    {
        if ($ingredient->menuItems()->exists()) {
            return back()->with('error', 'Cannot delete ingredient used in recipes.');
        }

        $ingredient->delete();
        return redirect()->route('ingredients.index')
                         ->with('success', 'Ingredient deleted successfully!');
    }

    /**
     * Show form to manually add stock.
     */
    public function stockInForm(Ingredient $ingredient)
    {
        return view('ingredients.stock-in', compact('ingredient'));
    }

    /**
     * Manually add stock to an ingredient.
     */
    public function stockIn(Request $request, Ingredient $ingredient)
    {
        $validated = $request->validate([
            'quantity' => 'required|numeric|min:0.001',
            'reason'   => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($ingredient, $validated) {
            $before = $ingredient->current_stock;
            $after  = $before + $validated['quantity'];

            $ingredient->update(['current_stock' => $after]);

            StockMovement::create([
                'ingredient_id' => $ingredient->id,
                'type'          => 'manual_add',
                'quantity'      => $validated['quantity'],
                'stock_before'  => $before,
                'stock_after'   => $after,
                'reason'        => $validated['reason'] ?? 'Manual stock in',
            ]);
        });

        return redirect()->route('ingredients.show', $ingredient)
                         ->with('success', 'Stock added successfully!');
    }
}
