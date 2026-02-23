<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\MenuItem;
use App\Models\Recipe;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with sample restaurant data.
     */
    public function run(): void
    {
        // ── Categories ────────────────────────────────────────────────────────
        $mainCourse = Category::create(['name' => 'Main Course', 'description' => 'Hearty main dishes']);
        $drinks     = Category::create(['name' => 'Drinks',      'description' => 'Beverages and juices']);
        $desserts   = Category::create(['name' => 'Desserts',    'description' => 'Sweet endings']);
        $starters   = Category::create(['name' => 'Starters',   'description' => 'Appetizers and soups']);

        // ── Ingredients ───────────────────────────────────────────────────────
        $tomato    = Ingredient::create(['name' => 'Tomato',        'unit' => 'gram',  'current_stock' => 5000, 'minimum_stock' => 500,  'cost_per_unit' => 0.08]);
        $cheese    = Ingredient::create(['name' => 'Cheese',        'unit' => 'gram',  'current_stock' => 3000, 'minimum_stock' => 200,  'cost_per_unit' => 0.5]);
        $bread     = Ingredient::create(['name' => 'Burger Bun',    'unit' => 'piece', 'current_stock' => 100,  'minimum_stock' => 20,   'cost_per_unit' => 15]);
        $chicken   = Ingredient::create(['name' => 'Chicken',       'unit' => 'gram',  'current_stock' => 10000,'minimum_stock' => 1000, 'cost_per_unit' => 0.4]);
        $pasta     = Ingredient::create(['name' => 'Pasta',         'unit' => 'gram',  'current_stock' => 8000, 'minimum_stock' => 500,  'cost_per_unit' => 0.12]);
        $cream     = Ingredient::create(['name' => 'Cream',         'unit' => 'ml',    'current_stock' => 3000, 'minimum_stock' => 300,  'cost_per_unit' => 0.2]);
        $pizzaBase = Ingredient::create(['name' => 'Pizza Base',    'unit' => 'piece', 'current_stock' => 50,   'minimum_stock' => 10,   'cost_per_unit' => 30]);
        $sauce     = Ingredient::create(['name' => 'Tomato Sauce',  'unit' => 'gram',  'current_stock' => 4000, 'minimum_stock' => 500,  'cost_per_unit' => 0.1]);
        $sugar     = Ingredient::create(['name' => 'Sugar',         'unit' => 'gram',  'current_stock' => 5000, 'minimum_stock' => 200,  'cost_per_unit' => 0.05]);
        $milk      = Ingredient::create(['name' => 'Milk',          'unit' => 'ml',    'current_stock' => 10000,'minimum_stock' => 1000, 'cost_per_unit' => 0.08]);
        $flour     = Ingredient::create(['name' => 'All-Purpose Flour','unit' => 'gram','current_stock'=>5000,  'minimum_stock' => 500,  'cost_per_unit' => 0.06]);
        $egg       = Ingredient::create(['name' => 'Egg',           'unit' => 'piece', 'current_stock' => 200,  'minimum_stock' => 30,   'cost_per_unit' => 12]);

        // ── Menu Items ────────────────────────────────────────────────────────
        $burger = MenuItem::create(['category_id' => $mainCourse->id, 'name' => 'Classic Burger', 'price' => 350, 'description' => 'Juicy chicken burger with fresh veggies']);
        $pizza  = MenuItem::create(['category_id' => $mainCourse->id, 'name' => 'Margherita Pizza','price' => 450,'description' => 'Classic pizza with tomato sauce and mozzarella']);
        $pasta2 = MenuItem::create(['category_id' => $mainCourse->id, 'name' => 'Creamy Pasta',    'price' => 320, 'description' => 'Pasta in rich cream sauce']);
        $chai   = MenuItem::create(['category_id' => $drinks->id,     'name' => 'Masala Tea',       'price' => 80,  'description' => 'Hot spiced Nepali tea']);
        $cake   = MenuItem::create(['category_id' => $desserts->id,   'name' => 'Chocolate Cake',   'price' => 250, 'description' => 'Rich chocolate sponge cake']);
        $soup   = MenuItem::create(['category_id' => $starters->id,   'name' => 'Tomato Soup',      'price' => 180, 'description' => 'Creamy tomato soup']);

        // ── Recipes ───────────────────────────────────────────────────────────
        // Burger recipe
        Recipe::insert([
            ['menu_item_id' => $burger->id, 'ingredient_id' => $chicken->id,  'quantity_required' => 150, 'created_at' => now(), 'updated_at' => now()],
            ['menu_item_id' => $burger->id, 'ingredient_id' => $bread->id,    'quantity_required' => 1,   'created_at' => now(), 'updated_at' => now()],
            ['menu_item_id' => $burger->id, 'ingredient_id' => $tomato->id,   'quantity_required' => 50,  'created_at' => now(), 'updated_at' => now()],
            ['menu_item_id' => $burger->id, 'ingredient_id' => $cheese->id,   'quantity_required' => 20,  'created_at' => now(), 'updated_at' => now()],
        ]);

        // Pizza recipe
        Recipe::insert([
            ['menu_item_id' => $pizza->id, 'ingredient_id' => $pizzaBase->id,'quantity_required' => 1,   'created_at' => now(), 'updated_at' => now()],
            ['menu_item_id' => $pizza->id, 'ingredient_id' => $sauce->id,    'quantity_required' => 100, 'created_at' => now(), 'updated_at' => now()],
            ['menu_item_id' => $pizza->id, 'ingredient_id' => $cheese->id,   'quantity_required' => 80,  'created_at' => now(), 'updated_at' => now()],
            ['menu_item_id' => $pizza->id, 'ingredient_id' => $tomato->id,   'quantity_required' => 60,  'created_at' => now(), 'updated_at' => now()],
        ]);

        // Pasta recipe
        Recipe::insert([
            ['menu_item_id' => $pasta2->id, 'ingredient_id' => $pasta->id,  'quantity_required' => 150, 'created_at' => now(), 'updated_at' => now()],
            ['menu_item_id' => $pasta2->id, 'ingredient_id' => $cream->id,  'quantity_required' => 100, 'created_at' => now(), 'updated_at' => now()],
            ['menu_item_id' => $pasta2->id, 'ingredient_id' => $cheese->id, 'quantity_required' => 30,  'created_at' => now(), 'updated_at' => now()],
        ]);

        // Masala Tea recipe
        Recipe::insert([
            ['menu_item_id' => $chai->id, 'ingredient_id' => $milk->id,  'quantity_required' => 150, 'created_at' => now(), 'updated_at' => now()],
            ['menu_item_id' => $chai->id, 'ingredient_id' => $sugar->id, 'quantity_required' => 10,  'created_at' => now(), 'updated_at' => now()],
        ]);

        // Tomato Soup recipe
        Recipe::insert([
            ['menu_item_id' => $soup->id, 'ingredient_id' => $tomato->id, 'quantity_required' => 200, 'created_at' => now(), 'updated_at' => now()],
            ['menu_item_id' => $soup->id, 'ingredient_id' => $cream->id, 'quantity_required' => 50,  'created_at' => now(), 'updated_at' => now()],
        ]);

        // Chocolate Cake recipe
        Recipe::insert([
            ['menu_item_id' => $cake->id, 'ingredient_id' => $flour->id,  'quantity_required' => 200, 'created_at' => now(), 'updated_at' => now()],
            ['menu_item_id' => $cake->id, 'ingredient_id' => $egg->id,    'quantity_required' => 2,   'created_at' => now(), 'updated_at' => now()],
            ['menu_item_id' => $cake->id, 'ingredient_id' => $sugar->id,  'quantity_required' => 100, 'created_at' => now(), 'updated_at' => now()],
            ['menu_item_id' => $cake->id, 'ingredient_id' => $milk->id,   'quantity_required' => 80,  'created_at' => now(), 'updated_at' => now()],
        ]);

        $this->command->info('✅ Seeding complete! Restaurant data ready.');
    }
}
