<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'description', 'price', 'image', 'is_available'];

    protected $casts = [
        'price'        => 'decimal:2',
        'is_available' => 'boolean',
    ];

    /**
     * Each menu item belongs to one category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Each menu item has many ingredients through recipes (pivot).
     */
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'recipes')
                    ->withPivot('quantity_required')
                    ->withTimestamps();
    }

    /**
     * Direct relationship to recipe rows.
     */
    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    /**
     * Check if ALL ingredients have sufficient stock for a given quantity.
     * Returns array: ['sufficient' => bool, 'shortages' => [...]]
     */
    public function checkStockAvailability(int $quantity = 1): array
    {
        $shortages = [];

        foreach ($this->ingredients as $ingredient) {
            $required = $ingredient->pivot->quantity_required * $quantity;

            if ($ingredient->current_stock < $required) {
                $shortages[] = [
                    'ingredient' => $ingredient->name,
                    'unit'       => $ingredient->unit,
                    'required'   => $required,
                    'available'  => $ingredient->current_stock,
                    'shortage'   => $required - $ingredient->current_stock,
                ];
            }
        }

        return [
            'sufficient' => empty($shortages),
            'shortages'  => $shortages,
        ];
    }
}
