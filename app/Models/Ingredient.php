<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'unit', 'current_stock', 'minimum_stock', 'cost_per_unit',
    ];

    protected $casts = [
        'current_stock' => 'decimal:3',
        'minimum_stock' => 'decimal:3',
        'cost_per_unit' => 'decimal:2',
    ];

    /**
     * Many menu items use this ingredient.
     */
    public function menuItems()
    {
        return $this->belongsToMany(MenuItem::class, 'recipes')
                    ->withPivot('quantity_required')
                    ->withTimestamps();
    }

    /**
     * Stock movement history for this ingredient.
     */
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Check if stock is below minimum threshold.
     */
    public function isLowStock(): bool
    {
        return $this->current_stock <= $this->minimum_stock;
    }

    /**
     * Scope: only low stock ingredients.
     */
    public function scopeLowStock($query)
    {
        return $query->whereColumn('current_stock', '<=', 'minimum_stock');
    }
}
