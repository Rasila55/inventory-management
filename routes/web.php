<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    CategoryController,
    MenuItemController,
    IngredientController,
    RecipeController,
    OrderController,
    StockMovementController,
};

/*
|--------------------------------------------------------------------------
| Web Routes — Restaurant Inventory Management System
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => redirect()->route('dashboard'));

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Category Management
Route::resource('categories', CategoryController::class)->except(['show']);

// Menu Item Management
Route::resource('menu-items', MenuItemController::class);

// Ingredient / Stock Management
Route::resource('ingredients', IngredientController::class);
Route::get('/ingredients/{ingredient}/stock-in', [IngredientController::class, 'stockInForm'])
     ->name('ingredients.stock-in.form');
Route::post('/ingredients/{ingredient}/stock-in', [IngredientController::class, 'stockIn'])
     ->name('ingredients.stock-in');

// Recipe Management (nested under menu items)
Route::get('/menu-items/{menuItem}/recipes', [RecipeController::class, 'index'])
     ->name('recipes.index');
Route::post('/menu-items/{menuItem}/recipes', [RecipeController::class, 'store'])
     ->name('recipes.store');
Route::delete('/menu-items/{menuItem}/recipes/{recipe}', [RecipeController::class, 'destroy'])
     ->name('recipes.destroy');

// Order Management
Route::resource('orders', OrderController::class)->except(['edit', 'update']);
Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])
     ->name('orders.update-status');

// Stock Movement Logs
Route::get('/stock/movements', [StockMovementController::class, 'index'])
     ->name('stock.movements');
