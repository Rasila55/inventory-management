<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'ingredient_id', 'order_id', 'type', 'quantity',
        'stock_before', 'stock_after', 'reason',
    ];

    protected $casts = [
        'quantity'     => 'decimal:3',
        'stock_before' => 'decimal:3',
        'stock_after'  => 'decimal:3',
    ];

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
