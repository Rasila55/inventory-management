<?php

namespace App\Http\Controllers;

use App\Models\{MenuItem, Ingredient, Recipe};
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    /**
     * Show recipe management for a specific menu item.
     */
    public function index(MenuItem $menuItem)
    {
        $menuItem->load('ingredients');
        $ingredients = Ingredient::orderBy('name')->get();
        return view('recipes.index', compact('menuItem', 'ingredients'));
    }

    /**
     * Add or update an ingredient in the recipe.
     */
    public function store(Request $request, MenuItem $menuItem)
    {
        $validated = $request->validate([
            'ingredient_id'     => 'required|exists:ingredients,id',
            'quantity_required' => 'required|numeric|min:0.001',
        ]);

        // Use updateOrCreate to handle duplicates gracefully
        Recipe::updateOrCreate(
            [
                'menu_item_id'  => $menuItem->id,
                'ingredient_id' => $validated['ingredient_id'],
            ],
            ['quantity_required' => $validated['quantity_required']]
        );

        return redirect()->route('recipes.index', $menuItem)
                         ->with('success', 'Recipe ingredient saved!');
    }

    /**
     * Remove an ingredient from the recipe.
     */
    public function destroy(MenuItem $menuItem, Recipe $recipe)
    {
        if ($recipe->menu_item_id !== $menuItem->id) {
            abort(403);
        }

        $recipe->delete();

        return redirect()->route('recipes.index', $menuItem)
                         ->with('success', 'Ingredient removed from recipe.');
    }
}
