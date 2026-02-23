<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 'status', 'total_amount', 'notes', 'customer_name', 'delivered_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'delivered_at' => 'datetime',
    ];

    /**
     * Generate a unique order number like ORD-20240101-0001.
     */
    public static function generateOrderNumber(): string
    {
        $prefix = 'ORD-' . now()->format('Ymd') . '-';
        $last   = static::where('order_number', 'like', $prefix . '%')->latest()->first();
        $seq    = $last ? (int) substr($last->order_number, -4) + 1 : 1;
        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Check if all items in this order have sufficient stock.
     */
    public function checkStockAvailability(): array
    {
        $allShortages = [];

        foreach ($this->orderItems as $orderItem) {
            $menuItem = $orderItem->menuItem->load('ingredients');
            $result   = $menuItem->checkStockAvailability($orderItem->quantity);

            if (!$result['sufficient']) {
                foreach ($result['shortages'] as $shortage) {
                    $shortage['menu_item'] = $menuItem->name;
                    $allShortages[]        = $shortage;
                }
            }
        }

        return [
            'sufficient' => empty($allShortages),
            'shortages'  => $allShortages,
        ];
    }
}
