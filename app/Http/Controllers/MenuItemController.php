<?php

namespace App\Http\Controllers;

use App\Models\{MenuItem, Category};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuItemController extends Controller
{
    public function index()
    {
        $menuItems = MenuItem::with('category')
                             ->withCount('ingredients')
                             ->latest()
                             ->paginate(15);
        return view('menu-items.index', compact('menuItems'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('menu-items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'name'         => 'required|string|max:150',
            'description'  => 'nullable|string|max:1000',
            'price'        => 'required|numeric|min:0',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_available' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('menu-items', 'public');
        }

        $validated['is_available'] = $request->boolean('is_available', true);
        MenuItem::create($validated);

        return redirect()->route('menu-items.index')
                         ->with('success', 'Menu item created successfully!');
    }

    public function show(MenuItem $menuItem)
    {
        $menuItem->load(['category', 'ingredients', 'recipes.ingredient']);
        return view('menu-items.show', compact('menuItem'));
    }

    public function edit(MenuItem $menuItem)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('menu-items.edit', compact('menuItem', 'categories'));
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $validated = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'name'         => 'required|string|max:150',
            'description'  => 'nullable|string|max:1000',
            'price'        => 'required|numeric|min:0',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_available' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($menuItem->image) {
                Storage::disk('public')->delete($menuItem->image);
            }
            $validated['image'] = $request->file('image')->store('menu-items', 'public');
        }

        $validated['is_available'] = $request->boolean('is_available', true);
        $menuItem->update($validated);

        return redirect()->route('menu-items.index')
                         ->with('success', 'Menu item updated successfully!');
    }

    public function destroy(MenuItem $menuItem)
    {
        if ($menuItem->image) {
            Storage::disk('public')->delete($menuItem->image);
        }
        $menuItem->delete();

        return redirect()->route('menu-items.index')
                         ->with('success', 'Menu item deleted successfully!');
    }
}
