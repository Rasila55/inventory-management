<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Category, MenuItem, Ingredient, Recipe, Order, OrderItem};

class RestaurantSeeder extends Seeder
{
    public function run(): void
    {
        // ===================== Categories =====================
        $categories = [
            ['name' => 'Main Course',  'description' => 'Hearty main dishes'],
            ['name' => 'Beverages',    'description' => 'Hot and cold drinks'],
            ['name' => 'Desserts',     'description' => 'Sweet treats'],
            ['name' => 'Starters',     'description' => 'Appetizers and salads'],
            ['name' => 'Fast Food',    'description' => 'Burgers, sandwiches and more'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // ===================== Ingredients =====================
        $ingredientData = [
            ['name' => 'Tomato',        'unit' => 'gram',  'current_stock' => 5000, 'minimum_stock' => 500],
            ['name' => 'Cheese',        'unit' => 'gram',  'current_stock' => 3000, 'minimum_stock' => 200],
            ['name' => 'Chicken',       'unit' => 'gram',  'current_stock' => 8000, 'minimum_stock' => 1000],
            ['name' => 'Bread',         'unit' => 'piece', 'current_stock' => 50,   'minimum_stock' => 5],
            ['name' => 'Lettuce',       'unit' => 'gram',  'current_stock' => 2000, 'minimum_stock' => 200],
            ['name' => 'Pasta',         'unit' => 'gram',  'current_stock' => 5000, 'minimum_stock' => 500],
            ['name' => 'Cream',         'unit' => 'ml',    'current_stock' => 2000, 'minimum_stock' => 200],
            ['name' => 'Garlic',        'unit' => 'gram',  'current_stock' => 500,  'minimum_stock' => 50],
            ['name' => 'Onion',         'unit' => 'gram',  'current_stock' => 3000, 'minimum_stock' => 300],
            ['name' => 'Pizza Dough',   'unit' => 'gram',  'current_stock' => 4000, 'minimum_stock' => 400],
            ['name' => 'Olive Oil',     'unit' => 'ml',    'current_stock' => 1000, 'minimum_stock' => 100],
            ['name' => 'Sugar',         'unit' => 'gram',  'current_stock' => 2000, 'minimum_stock' => 200],
            ['name' => 'Flour',         'unit' => 'gram',  'current_stock' => 5000, 'minimum_stock' => 500],
            ['name' => 'Egg',           'unit' => 'piece', 'current_stock' => 100,  'minimum_stock' => 10],
            ['name' => 'Milk',          'unit' => 'ml',    'current_stock' => 5000, 'minimum_stock' => 500],
            ['name' => 'Coffee Beans',  'unit' => 'gram',  'current_stock' => 1000, 'minimum_stock' => 100],
            ['name' => 'Beef Patty',    'unit' => 'piece', 'current_stock' => 30,   'minimum_stock' => 5],
            ['name' => 'Mayonnaise',    'unit' => 'gram',  'current_stock' => 500,  'minimum_stock' => 50],
        ];

        $ingredients = [];
        foreach ($ingredientData as $data) {
            $ing = Ingredient::create($data);
            $ingredients[$ing->name] = $ing;
        }

        // ===================== Menu Items =====================
        $mainCourse = Category::where('name', 'Main Course')->first();
        $fastFood   = Category::where('name', 'Fast Food')->first();
        $beverages  = Category::where('name', 'Beverages')->first();
        $desserts   = Category::where('name', 'Desserts')->first();

        $burger = MenuItem::create([
            'category_id' => $fastFood->id,
            'name'        => 'Classic Beef Burger',
            'description' => 'Juicy beef patty with lettuce, tomato and cheese',
            'price'       => 350,
        ]);
        Recipe::create(['menu_item_id' => $burger->id, 'ingredient_id' => $ingredients['Beef Patty']->id, 'quantity_required' => 1]);
        Recipe::create(['menu_item_id' => $burger->id, 'ingredient_id' => $ingredients['Bread']->id,     'quantity_required' => 2]);
        Recipe::create(['menu_item_id' => $burger->id, 'ingredient_id' => $ingredients['Tomato']->id,    'quantity_required' => 50]);
        Recipe::create(['menu_item_id' => $burger->id, 'ingredient_id' => $ingredients['Cheese']->id,    'quantity_required' => 30]);
        Recipe::create(['menu_item_id' => $burger->id, 'ingredient_id' => $ingredients['Lettuce']->id,   'quantity_required' => 20]);
        Recipe::create(['menu_item_id' => $burger->id, 'ingredient_id' => $ingredients['Mayonnaise']->id,'quantity_required' => 15]);

        $pasta = MenuItem::create([
            'category_id' => $mainCourse->id,
            'name'        => 'Creamy Pasta',
            'description' => 'Pasta in rich cream sauce with garlic',
            'price'       => 280,
        ]);
        Recipe::create(['menu_item_id' => $pasta->id, 'ingredient_id' => $ingredients['Pasta']->id,  'quantity_required' => 150]);
        Recipe::create(['menu_item_id' => $pasta->id, 'ingredient_id' => $ingredients['Cream']->id,  'quantity_required' => 100]);
        Recipe::create(['menu_item_id' => $pasta->id, 'ingredient_id' => $ingredients['Garlic']->id, 'quantity_required' => 10]);
        Recipe::create(['menu_item_id' => $pasta->id, 'ingredient_id' => $ingredients['Olive Oil']->id, 'quantity_required' => 20]);
        Recipe::create(['menu_item_id' => $pasta->id, 'ingredient_id' => $ingredients['Cheese']->id, 'quantity_required' => 30]);

        $pizza = MenuItem::create([
            'category_id' => $mainCourse->id,
            'name'        => 'Margherita Pizza',
            'description' => 'Classic pizza with tomato and cheese',
            'price'       => 450,
        ]);
        Recipe::create(['menu_item_id' => $pizza->id, 'ingredient_id' => $ingredients['Pizza Dough']->id, 'quantity_required' => 200]);
        Recipe::create(['menu_item_id' => $pizza->id, 'ingredient_id' => $ingredients['Tomato']->id,      'quantity_required' => 80]);
        Recipe::create(['menu_item_id' => $pizza->id, 'ingredient_id' => $ingredients['Cheese']->id,      'quantity_required' => 100]);
        Recipe::create(['menu_item_id' => $pizza->id, 'ingredient_id' => $ingredients['Olive Oil']->id,   'quantity_required' => 15]);

        $coffee = MenuItem::create([
            'category_id' => $beverages->id,
            'name'        => 'Cappuccino',
            'description' => 'Espresso with steamed milk foam',
            'price'       => 150,
        ]);
        Recipe::create(['menu_item_id' => $coffee->id, 'ingredient_id' => $ingredients['Coffee Beans']->id, 'quantity_required' => 18]);
        Recipe::create(['menu_item_id' => $coffee->id, 'ingredient_id' => $ingredients['Milk']->id,         'quantity_required' => 150]);

        $this->command->info('Database seeded with sample restaurant data!');
    }
}
