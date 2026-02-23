<?php

namespace App\Http\Controllers;

use App\Models\{Order, OrderItem, MenuItem, Ingredient, StockMovement};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('orderItems.menuItem');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $orders = $query->latest()->paginate(20);
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $menuItems = MenuItem::with('category')
                             ->where('is_available', true)
                             ->orderBy('name')
                             ->get();
        return view('orders.create', compact('menuItems'));
    }

    /**
     * Create a new order.
     * At this point we only VALIDATE stock; we do NOT deduct yet.
     * Stock is deducted when status changes to "delivered".
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name'           => 'nullable|string|max:150',
            'notes'                   => 'nullable|string|max:500',
            'items'                   => 'required|array|min:1',
            'items.*.menu_item_id'    => 'required|exists:menu_items,id',
            'items.*.quantity'        => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($validated) {
            $order = Order::create([
                'order_number'  => Order::generateOrderNumber(),
                'status'        => 'pending',
                'customer_name' => $validated['customer_name'] ?? null,
                'notes'         => $validated['notes'] ?? null,
                'total_amount'  => 0,
            ]);

            $total = 0;

            foreach ($validated['items'] as $item) {
                $menuItem = MenuItem::findOrFail($item['menu_item_id']);
                $subtotal = $menuItem->price * $item['quantity'];
                $total   += $subtotal;

                OrderItem::create([
                    'order_id'     => $order->id,
                    'menu_item_id' => $menuItem->id,
                    'quantity'     => $item['quantity'],
                    'unit_price'   => $menuItem->price,
                    'subtotal'     => $subtotal,
                ]);
            }

            $order->update(['total_amount' => $total]);
        });

        return redirect()->route('orders.index')
                         ->with('success', 'Order created successfully!');
    }

    public function show(Order $order)
    {
        $order->load(['orderItems.menuItem.ingredients', 'stockMovements.ingredient']);
        return view('orders.show', compact('order'));
    }

    /**
     * Update the order status.
     * When status becomes "delivered":
     *   1. Check sufficient stock for ALL items
     *   2. Deduct stock using DB transaction
     *   3. Log stock movements
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,preparing,delivered,cancelled',
        ]);

        $newStatus = $request->status;

        // Prevent re-delivering an already delivered order
        if ($order->status === 'delivered') {
            return back()->with('error', 'Order has already been delivered.');
        }

        // Handle delivery: check stock then deduct
        if ($newStatus === 'delivered') {
            return $this->processDelivery($order);
        }

        $order->update(['status' => $newStatus]);
        return back()->with('success', 'Order status updated to ' . ucfirst($newStatus) . '.');
    }

    /**
     * Core stock deduction logic triggered on delivery.
     */
    private function processDelivery(Order $order)
    {
        // Load items with their recipes
        $order->load(['orderItems.menuItem.ingredients']);

        // --- STEP 1: Check stock availability for ALL items ---
        $stockCheck = $order->checkStockAvailability();

        if (!$stockCheck['sufficient']) {
            $shortageText = collect($stockCheck['shortages'])->map(function ($s) {
                return "{$s['menu_item']} → {$s['ingredient']}: needs {$s['required']} {$s['unit']}, only {$s['available']} available";
            })->implode('; ');

            return back()->with('error', "Insufficient stock! " . $shortageText);
        }

        // --- STEP 2: Deduct stock using a DB transaction ---
        DB::transaction(function () use ($order) {
            foreach ($order->orderItems as $orderItem) {
                $menuItem = $orderItem->menuItem;

                foreach ($menuItem->ingredients as $ingredient) {
                    $quantityToDeduct = $ingredient->pivot->quantity_required * $orderItem->quantity;

                    // Lock the row to prevent race conditions
                    $ingredient = Ingredient::lockForUpdate()->find($ingredient->id);

                    $stockBefore = $ingredient->current_stock;
                    $stockAfter  = $stockBefore - $quantityToDeduct;

                    // Update ingredient stock
                    $ingredient->update(['current_stock' => $stockAfter]);

                    // Log the deduction
                    StockMovement::create([
                        'ingredient_id' => $ingredient->id,
                        'order_id'      => $order->id,
                        'type'          => 'deduction',
                        'quantity'      => -$quantityToDeduct,   // negative = deducted
                        'stock_before'  => $stockBefore,
                        'stock_after'   => $stockAfter,
                        'reason'        => "Deducted for Order #{$order->order_number} — {$menuItem->name} x{$orderItem->quantity}",
                    ]);
                }
            }

            // Mark as delivered
            $order->update([
                'status'       => 'delivered',
                'delivered_at' => now(),
            ]);
        });

        return back()->with('success', 'Order delivered and stock deducted successfully!');
    }

    public function destroy(Order $order)
    {
        if ($order->status === 'delivered') {
            return back()->with('error', 'Cannot delete a delivered order.');
        }

        $order->delete();
        return redirect()->route('orders.index')
                         ->with('success', 'Order cancelled and deleted.');
    }
}
