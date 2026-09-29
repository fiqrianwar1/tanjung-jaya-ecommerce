<?php

use App\Http\Controllers\Admin\AuditLogController as AdminAuditLogController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
// Public & Customer Controllers
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReturnRequestController as AdminReturnRequestController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\CartController;
// Admin Controllers
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\Gudang\OrderController as GudangOrderController;
use App\Http\Controllers\Gudang\ReturnRequestController as GudangReturnRequestController;
use App\Http\Controllers\Gudang\StockLogController as GudangStockLogController;
use App\Http\Controllers\Manager\DashboardController as ManagerDashboardController;
use App\Http\Controllers\Manager\ReportController as ManagerReportController;
use App\Http\Controllers\Manager\ReportSubscriptionController as ManagerReportSubscriptionController;
// Gudang Controllers
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
// Manager Controllers
use App\Http\Controllers\ReturnRequestController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

// Catalog is public
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/produk/{product}', [ProductController::class, 'show'])->name('products.show');
Route::post('/chat', [ChatbotController::class, 'chat'])->name('chat')->middleware('throttle:30,1');

Route::middleware('auth')->group(function () {
    // Breeze Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Default Dashboard: Customer diarahkan ke katalog, staf mendapat ringkasan data
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->role === 'Customer') {
            return redirect()->route('home');
        }

        $revenueStatuses = ['processing', 'shipped', 'completed'];

        $activeStatuses = $user->role === 'Manager'
            ? $revenueStatuses
            : ['pending', 'processing', 'shipped', 'completed', 'returned', 'cancelled'];

        return view('dashboard', [
            'totalOrders' => Order::whereIn('status', $activeStatuses)->count(),
            'revenue' => Order::whereIn('status', $revenueStatuses)->sum('total'),
            'totalProducts' => Product::where('status', 'active')->count(),
            'lowStockCount' => Product::where('stock', '<=', 10)->count(),
            'recentOrders' => Order::with('user')->latest()->take(6)->get(),
        ]);
    })->middleware(['verified'])->name('dashboard');

    // ==========================================
    // CUSTOMER ROUTES
    // ==========================================
    Route::middleware('role:Customer')->name('customer.')->group(function () {
        Route::resource('carts', CartController::class);
        Route::resource('orders', OrderController::class);
        Route::resource('reviews', ReviewController::class);
        Route::resource('returns', ReturnRequestController::class);
        Route::resource('wishlists', WishlistController::class);
    });

    // ==========================================
    // ADMIN ROUTES
    // ==========================================
    Route::middleware('role:Admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('products', AdminProductController::class);
        Route::resource('categories', AdminCategoryController::class);
        Route::resource('orders', AdminOrderController::class);
        Route::resource('users', AdminUserController::class);
        Route::resource('reviews', AdminReviewController::class);
        Route::resource('returns', AdminReturnRequestController::class);
        Route::resource('audit-logs', AdminAuditLogController::class)->only(['index', 'show']);
    });

    // ==========================================
    // GUDANG ROUTES
    // ==========================================
    Route::middleware('role:Gudang')->prefix('gudang')->name('gudang.')->group(function () {
        Route::resource('stocks', GudangStockLogController::class);
        Route::resource('orders', GudangOrderController::class)->only(['index', 'show', 'update']);
        Route::resource('returns', GudangReturnRequestController::class)->only(['index', 'show', 'update']);
    });

    // ==========================================
    // MANAGER ROUTES
    // ==========================================
    Route::middleware('role:Manager')->prefix('manager')->name('manager.')->group(function () {
        Route::get('dashboard', [ManagerDashboardController::class, 'index'])->name('dashboard');
        Route::get('reports', [ManagerReportController::class, 'index'])->name('reports');
        Route::get('reports/export', [ManagerReportController::class, 'export'])->name('reports.export');
        Route::resource('subscriptions', ManagerReportSubscriptionController::class);
    });
});

require __DIR__.'/auth.php';
