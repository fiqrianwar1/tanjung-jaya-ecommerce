<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Dashboard eksekutif: ringkasan performa penjualan & operasional.
     */
    public function index()
    {
        $revenueStatuses = ['processing', 'shipped', 'completed'];

        $revenue = Order::whereIn('status', $revenueStatuses)->sum('total');
        $ordersCount = Order::whereIn('status', $revenueStatuses)->count();
        $avgOrderValue = $ordersCount > 0 ? $revenue / $ordersCount : 0;

        $pendingReturns = ReturnRequest::where('status', 'Menunggu Verifikasi')->count();
        $lowStockCount = Product::where('stock', '<=', 10)->count();

        // Tren penjualan 14 hari terakhir
        $salesTrend = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total) as revenue'),
            DB::raw('COUNT(*) as orders')
        )
            ->whereIn('status', $revenueStatuses)
            ->where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $topProducts = Product::with('category')
            ->withSum('orderItems as sold_qty', 'qty')
            ->orderByDesc('sold_qty')
            ->take(5)
            ->get();

        $recentOrders = Order::with('user')->latest()->take(6)->get();

        return view('manager.dashboard', compact(
            'revenue',
            'ordersCount',
            'avgOrderValue',
            'pendingReturns',
            'lowStockCount',
            'salesTrend',
            'topProducts',
            'recentOrders'
        ));
    }

    public function create()
    {
        abort(404);
    }

    public function store(Request $request)
    {
        abort(404);
    }

    public function show(string $id)
    {
        abort(404);
    }

    public function edit(string $id)
    {
        abort(404);
    }

    public function update(Request $request, string $id)
    {
        abort(404);
    }

    public function destroy(string $id)
    {
        abort(404);
    }
}
