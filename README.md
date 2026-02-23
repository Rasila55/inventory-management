#  Restaurant Inventory Management System

**A complete Laravel-based Inventory Management System for restaurants with automatic stock deduction on order delivery.**

**Developed for:** Digital Waiter Nepal  
**Framework:** Laravel 12.x  
**Database:** MySQL  
**PHP Version:** 8.2+

---

##  Table of Contents

- [Features](#-features)
- [Screenshots](#-screenshots)
- [Installation](#-installation)
- [Database Schema](#-database-schema)
- [Usage Guide](#-usage-guide)
- [How Stock Deduction Works](#-how-stock-deduction-works)
- [Business Rules](#-business-rules)
- [Troubleshooting](#-troubleshooting)
- [File Structure](#-file-structure)
- [API Routes](#-api-routes)

---

##  Features

### **Core Modules**

| Module | Features |
|--------|----------|
| **Dashboard** | Real-time stats, low stock alerts, recent orders, revenue tracking |
| **Categories** | CRUD operations, active/inactive status, menu item count |
| **Menu Items** | CRUD, image upload, price management, availability toggle, category assignment |
| **Ingredients** | CRUD, unit types (kg/gram/piece/liter/ml/dozen), current stock, minimum stock alerts, cost tracking |
| **Recipe Management** | Define ingredient quantities per menu item, stock availability preview, max servings calculator |
| **Orders** | Create orders, multi-item support, status workflow (pending → preparing → delivered) |
| **Stock Deduction** | **Automatic deduction on delivery**, stock validation, transaction safety, insufficient stock prevention |
| **Stock Movements** | Complete audit trail, filterable logs, before/after quantities, order linkage |

### **Key Highlights**

 **Automatic Stock Deduction** - When order status changes to "delivered", stock is automatically deducted based on recipes  
 **Stock Validation** - Prevents orders if ingredients are insufficient  
 **Transaction Safety** - Uses database transactions to prevent partial deductions  
 **Row-Level Locking** - Prevents race conditions in concurrent orders  
 **Low Stock Alerts** - Real-time alerts when ingredients reach minimum threshold  
 **Complete Audit Log** - Every stock movement is tracked with reason and timestamp  
 **Manual Stock In** - Add stock manually with reason logging  
 **Recipe-Based System** - Each menu item has defined ingredient requirements  

---

##  Screenshots

### Dashboard
- Summary statistics (orders, revenue, items)
- Low stock ingredient alerts
- Recent orders list
- Stock movement history

### Menu Items
- Grid view with images
- Category badges
- Price display
- Recipe management button
- Availability status

### Recipe Management
- Ingredient list with quantities
- Current stock vs required
- Stock availability calculator
- "Max servings possible" preview

### Orders
- Order creation form with dynamic item selection
- Real-time price calculation
- Status workflow (pending → preparing → delivered)
- Stock deduction confirmation

### Ingredients
- Stock levels with visual bars
- Low stock indicators
- Stock-in functionality
- Movement history per ingredient

---

##  Installation

### **Prerequisites**

- PHP 8.2 or higher
- Composer
- MySQL 8.0+ or PostgreSQL 15+
- Node.js & NPM (optional, for asset compilation)

### **Step-by-Step Installation**

#### **1. Create Fresh Laravel Project**

```bash
composer create-project laravel/laravel restaurant-ims
cd restaurant-ims
```

#### **2. Extract and Copy Files**

Extract the `restaurant-ims.zip` and copy these folders into your Laravel project:

```
app/Http/Controllers/     → Replace
app/Models/               → Replace
database/migrations/      → Replace
database/seeders/         → Merge (keep DatabaseSeeder.php, add RestaurantSeeder.php)
resources/views/          → Replace
routes/web.php            → Replace
README.md                 → Replace
```

#### **3. Create Missing Base Files**

Create `app/Http/Controllers/Controller.php`:

```php
<?php

namespace App\Http\Controllers;

abstract class Controller
{
    //
}
```

Create `app/Providers/AppServiceProvider.php`:

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        //
    }
}
```

#### **4. Configure Environment**

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` file:

```env
APP_NAME="Restaurant IMS"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=restaurant_ims
DB_USERNAME=root
DB_PASSWORD=your_password

SESSION_DRIVER=file
SESSION_LIFETIME=120
```

**Important:** Use `SESSION_DRIVER=file` (not `database`)

#### **5. Create Database**

```sql
CREATE DATABASE restaurant_ims CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Or using command line:

```bash
mysql -u root -p -e "CREATE DATABASE restaurant_ims CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

#### **6. Run Migrations**

```bash
php artisan migrate
```

This creates 7 tables:
- categories
- menu_items
- ingredients
- recipes (pivot table)
- orders
- order_items
- stock_movements

#### **7. Seed Sample Data (Optional but Recommended)**

```bash
php artisan db:seed --class=RestaurantSeeder
```

This adds:
- 5 Categories (Main Course, Beverages, Desserts, Starters, Fast Food)
- 18 Ingredients with stock (Tomato, Cheese, Chicken, Bread, etc.)
- 4 Menu Items (Classic Beef Burger, Creamy Pasta, Margherita Pizza, Cappuccino)
- Complete recipes with ingredient quantities

#### **8. Create Storage Link**

```bash
php artisan storage:link
```

This enables image uploads for menu items.

#### **9. Start Development Server**

```bash
php artisan serve
```

Open browser: **http://localhost:8000**

---

##  Database Schema

### **Relationships Diagram**

```
Categories (1) ──────< Menu Items (N)
                           │
                           │ (Many-to-Many)
                           │
                      Recipes (Pivot)
                           │
                           │
Ingredients (N) ──────────┘

Orders (1) ──────< Order Items (N) ──────> Menu Items
   │
   └──────< Stock Movements (N) ──────> Ingredients
```

### **Table Structures**

```sql
-- Categories Table
categories:
  - id (PK)
  - name (unique)
  - description
  - is_active
  - timestamps

-- Menu Items Table  
menu_items:
  - id (PK)
  - category_id (FK → categories)
  - name
  - description
  - price
  - image
  - is_available
  - timestamps

-- Ingredients Table
ingredients:
  - id (PK)
  - name
  - unit (enum: kg, gram, piece, liter, ml, dozen)
  - current_stock
  - minimum_stock
  - cost_per_unit
  - timestamps

-- Recipes Table (Pivot)
recipes:
  - id (PK)
  - menu_item_id (FK → menu_items)
  - ingredient_id (FK → ingredients)
  - quantity_required (per 1 serving)
  - timestamps
  - UNIQUE(menu_item_id, ingredient_id)

-- Orders Table
orders:
  - id (PK)
  - order_number (unique, auto-generated)
  - status (enum: pending, preparing, delivered, cancelled)
  - total_amount
  - notes
  - customer_name
  - delivered_at
  - timestamps

-- Order Items Table
order_items:
  - id (PK)
  - order_id (FK → orders)
  - menu_item_id (FK → menu_items)
  - quantity
  - unit_price (snapshot at order time)
  - subtotal
  - timestamps

-- Stock Movements Table (Audit Log)
stock_movements:
  - id (PK)
  - ingredient_id (FK → ingredients)
  - order_id (FK → orders, nullable)
  - type (enum: deduction, manual_add, adjustment, waste)
  - quantity (+ = added, - = deducted)
  - stock_before
  - stock_after
  - reason
  - timestamps
```

---

##  Usage Guide

### **1. Setup Categories**

1. Navigate to **Categories** → Click **Add Category**
2. Enter name (e.g., "Main Course", "Drinks", "Desserts")
3. Optional: Add description
4. Set active status
5. Click **Save**

### **2. Add Ingredients**

1. Navigate to **Ingredients** → Click **Add Ingredient**
2. Enter ingredient name (e.g., "Tomato", "Cheese")
3. Select unit (kg, gram, piece, liter, ml, dozen)
4. Set current stock quantity
5. Set minimum stock alert threshold
6. Optional: Set cost per unit
7. Click **Save**

### **3. Create Menu Items**

1. Navigate to **Menu Items** → Click **Add Menu Item**
2. Enter item name (e.g., "Beef Burger")
3. Select category
4. Set price
5. Optional: Upload image
6. Add description
7. Mark as available
8. Click **Save**

### **4. Define Recipes**

1. Go to **Menu Items** → Click ** Recipe** button
2. Select ingredient from dropdown
3. Enter quantity required per serving (e.g., 50 grams)
4. Click **Save Ingredient**
5. Repeat for all ingredients in the recipe
6. View "Stock Availability Check" to see max servings possible

**Example Recipe for Burger:**
- Beef Patty: 1 piece
- Bread: 2 pieces
- Tomato: 50 grams
- Cheese: 30 grams
- Lettuce: 20 grams
- Mayonnaise: 15 grams

### **5. Create Orders**

1. Navigate to **Orders** → Click **New Order**
2. Optional: Enter customer name
3. Select menu item from dropdown
4. Enter quantity
5. Click **Add Another Item** for multiple items
6. Review total amount
7. Click **Place Order**

### **6. Process Orders**

**Order Flow:**

```
Pending → Preparing → Delivered
   ↓          ↓           ↓
No stock   No stock    Stock
deduction  deduction   DEDUCTED
```

**To deliver an order:**
1. Go to **Orders** → Click on order number
2. Click **Mark as Preparing** (optional)
3. Click **Mark as Delivered**
4. System checks stock availability:
   -  If sufficient → Deducts stock automatically
   -  If insufficient → Shows error with shortage details
5. View **Stock Deductions Made** section to see what was deducted

### **7. Manage Stock**

**Add Stock Manually:**
1. Go to **Ingredients** → Click **Stock In** button
2. Enter quantity to add
3. Enter reason (e.g., "Weekly purchase")
4. Click **Add Stock**

**View Stock History:**
1. Go to **Ingredients** → Click **🕐 History** button
2. See all movements for that ingredient
3. Or go to **Stock Movements** to see system-wide log

**Monitor Low Stock:**
- Check dashboard for low stock alerts
- Red badges indicate stock ≤ minimum threshold
- Filter ingredients by "Low Stock" button

---

##  How Stock Deduction Works

### **The Algorithm**

When order status changes to **"delivered"**:

```
┌─────────────────────────────────────────────┐
│ STEP 1: Load Order with Items & Recipes    │
└─────────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────────┐
│ STEP 2: Check Stock Availability           │
│                                             │
│ For each order item:                        │
│   For each ingredient in recipe:            │
│     required = qty_required × order_qty     │
│     if current_stock < required:            │
│       → ABORT with error message            │
└─────────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────────┐
│ STEP 3: All Checks Passed                  │
│ Begin DB Transaction                        │
└─────────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────────┐
│ STEP 4: Deduct Stock (Loop)                │
│                                             │
│ For each ingredient:                        │
│   1. Lock row (lockForUpdate)               │
│   2. Calculate: new_stock = old - required  │
│   3. Update ingredient.current_stock        │
│   4. Create stock_movement log              │
└─────────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────────┐
│ STEP 5: Mark Order as Delivered            │
│ Set delivered_at = now()                    │
└─────────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────────┐
│ STEP 6: Commit Transaction                 │
│ All changes saved atomically               │
└─────────────────────────────────────────────┘
```

### **Example**

**Order:** 2 Burgers

**Recipe per Burger:**
- Tomato: 50g
- Cheese: 30g

**Stock Before:**
- Tomato: 5000g
- Cheese: 3000g

**Calculation:**
- Tomato needed: 50g × 2 = 100g
- Cheese needed: 30g × 2 = 60g

**Stock After:**
- Tomato: 4900g (5000 - 100)
- Cheese: 2940g (3000 - 60)

**Stock Movements Log:**
```
Ingredient: Tomato
Type: deduction
Quantity: -100
Stock Before: 5000
Stock After: 4900
Order: ORD-20260223-0001
Reason: "Deducted for Order #ORD-20260223-0001 — Classic Beef Burger x2"

Ingredient: Cheese
Type: deduction
Quantity: -60
Stock Before: 3000
Stock After: 2940
Order: ORD-20260223-0001
Reason: "Deducted for Order #ORD-20260223-0001 — Classic Beef Burger x2"
```

---

##  Business Rules

### **Enforced Constraints**

 **Cannot place order if stock is insufficient**
- System checks ALL ingredients before allowing order
- Shows specific shortage details

 **Stock deducted ONLY when status = "delivered"**
- Pending/Preparing orders don't affect stock
- Prevents premature deduction

**Multiple quantities handled correctly**
- Recipe quantity × Order quantity = Total deduction
- Example: 3 burgers × 50g tomato = 150g deducted

 **Database transactions prevent errors**
- All-or-nothing: Either all ingredients deducted or none
- No partial deductions possible

**Row-level locking prevents race conditions**
- `lockForUpdate()` ensures concurrent orders don't cause issues
- Safe for multiple users

 **Cannot delete category with menu items**
- Must reassign or delete menu items first

**Cannot delete ingredient used in recipes**
- Must remove from recipes first

**Cannot delete delivered orders**
- Stock already deducted, order is final

 **Cannot re-deliver delivered orders**
- Prevents double stock deduction

---

##  Troubleshooting

### **Error: "Database connection [MySQL] not configured"**

**Cause:** DB_CONNECTION in .env is capitalized

**Fix:**
```env
# Wrong
DB_CONNECTION=MySQL

# Correct
DB_CONNECTION=mysql
```

Then run:
```bash
php artisan config:clear
```

---

### **Error: "Table 'sessions' doesn't exist"**

**Cause:** SESSION_DRIVER is set to "database"

**Fix:**
```env
SESSION_DRIVER=file
```

Then run:
```bash
php artisan config:clear
```

---

### **Error: "AppServiceProvider not found"**

**Cause:** Missing provider files

**Fix:** Create `app/Providers/AppServiceProvider.php`:

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}
    public function boot(): void {}
}
```

---

### **Error: "Controller not found"**

**Cause:** Missing base Controller

**Fix:** Create `app/Http/Controllers/Controller.php`:

```php
<?php

namespace App\Http\Controllers;

abstract class Controller {}
```

---

### **Images not showing**

**Cause:** Storage link not created

**Fix:**
```bash
php artisan storage:link
```

---

### **Permission errors (Windows)**

**Fix:**
```bash
icacls storage /grant Everyone:(OI)(CI)F /T
icacls bootstrap\cache /grant Everyone:(OI)(CI)F /T
```

---

### **Cache issues**

**Fix:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

---
##  File Structure

```
restaurant-ims/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Controller.php              # Base controller
│   │       ├── DashboardController.php     # Dashboard stats
│   │       ├── CategoryController.php      # Category CRUD
│   │       ├── MenuItemController.php      # Menu item CRUD
│   │       ├── IngredientController.php    # Stock management
│   │       ├── RecipeController.php        # Recipe builder
│   │       ├── OrderController.php         # Order + stock deduction
│   │       └── StockMovementController.php # Movement logs
│   │
│   ├── Models/
│   │   ├── Category.php                    # hasMany MenuItems
│   │   ├── MenuItem.php                    # belongsTo Category, belongsToMany Ingredients
│   │   ├── Ingredient.php                  # scopeLowStock, isLowStock()
│   │   ├── Recipe.php                      # Pivot model
│   │   ├── Order.php                       # checkStockAvailability(), generateOrderNumber()
│   │   ├── OrderItem.php                   # belongsTo Order, MenuItem
│   │   └── StockMovement.php               # Audit log model
│   │
│   └── Providers/
│       └── AppServiceProvider.php
│
├── database/
│   ├── migrations/
│   │   ├── 2024_01_01_000001_create_categories_table.php
│   │   ├── 2024_01_01_000002_create_menu_items_table.php
│   │   ├── 2024_01_01_000003_create_ingredients_table.php
│   │   ├── 2024_01_01_000004_create_recipes_table.php
│   │   ├── 2024_01_01_000005_create_orders_table.php
│   │   ├── 2024_01_01_000006_create_order_items_table.php
│   │   └── 2024_01_01_000007_create_stock_movements_table.php
│   │
│   └── seeders/
│       └── RestaurantSeeder.php            # Sample data
│
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php               # Master layout
│       ├── dashboard/
│       │   └── index.blade.php             # Dashboard page
│       ├── categories/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   └── edit.blade.php
│       ├── menu-items/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── show.blade.php
│       ├── ingredients/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   ├── show.blade.php              # Stock history
│       │   └── stock-in.blade.php          # Manual stock add
│       ├── recipes/
│       │   └── index.blade.php             # Recipe builder
│       ├── orders/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   └── show.blade.php              # Order details + delivery
│       └── stock/
│           └── movements.blade.php         # Stock log
│
├── routes/
│   └── web.php                             # All routes
│
├── .env                                    # Environment config
└── README.md                               # This file
```

---

##  API Routes

```php
// Dashboard
GET  /                          → Redirect to dashboard
GET  /dashboard                 → Dashboard page

// Categories
GET    /categories              → List all
GET    /categories/create       → Create form
POST   /categories              → Store new
GET    /categories/{id}/edit    → Edit form
PUT    /categories/{id}         → Update
DELETE /categories/{id}         → Delete

// Menu Items
GET    /menu-items              → List all
GET    /menu-items/create       → Create form
POST   /menu-items              → Store new
GET    /menu-items/{id}         → Show details
GET    /menu-items/{id}/edit    → Edit form
PUT    /menu-items/{id}         → Update
DELETE /menu-items/{id}         → Delete

// Ingredients
GET    /ingredients             → List all
GET    /ingredients/create      → Create form
POST   /ingredients             → Store new
GET    /ingredients/{id}        → Show stock history
GET    /ingredients/{id}/edit   → Edit form
PUT    /ingredients/{id}        → Update
DELETE /ingredients/{id}        → Delete
GET    /ingredients/{id}/stock-in      → Stock in form
POST   /ingredients/{id}/stock-in      → Add stock

// Recipes (nested under menu items)
GET    /menu-items/{id}/recipes        → Recipe builder
POST   /menu-items/{id}/recipes        → Add ingredient
DELETE /menu-items/{id}/recipes/{rid}  → Remove ingredient

// Orders
GET    /orders                  → List all
GET    /orders/create           → Create form
POST   /orders                  → Store new
GET    /orders/{id}             → Show details
PATCH  /orders/{id}/status      → Update status (triggers stock deduction)
DELETE /orders/{id}             → Cancel/delete

// Stock Movements
GET    /stock/movements         → View log (filterable)
```

---

## Testing Checklist

Before deploying, verify these features work correctly:

- [ ] Create category
- [ ] Create menu item with image upload
- [ ] Create multiple ingredients with different units
- [ ] Build recipe for menu item (add 3+ ingredients)
- [ ] Create order with multiple items
- [ ] Check "max servings possible" calculation
- [ ] Mark order as delivered (verify stock deduction)
- [ ] Try to deliver order with insufficient stock (should fail with error)
- [ ] Add stock manually via "Stock In"
- [ ] View stock movement log (filter by ingredient/type/date)
- [ ] Check low stock alerts on dashboard
- [ ] Try to delete category with menu items (should fail)
- [ ] Try to delete ingredient used in recipe (should fail)
- [ ] Verify delivered order cannot be re-delivered
- [ ] Test with concurrent orders (if possible)

---



**Developed for:** Digital Waiter Nepal  
**Framework:** Laravel 11.x  
**Database:** MySQL 8.0  
**UI:** Custom CSS (responsive, no framework dependencies)  
**Icons:** Font Awesome 6.5.0

---



For issues or questions about this system, contact Digital Waiter Nepal.

---

**Last Updated:** February 23, 2026  
**Version:** 1.0.0