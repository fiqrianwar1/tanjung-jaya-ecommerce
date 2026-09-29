<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\MonthlyProductSale;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Laporan penjualan dengan filter rentang tanggal & ekspor CSV.
     */
    public function index(Request $request)
    {
        $start = $request->filled('start') ? $request->date('start') : now()->startOfMonth();
        $end = $request->filled('end') ? $request->date('end') : now();

        $revenueStatuses = ['processing', 'shipped', 'completed'];

        $summary = Order::whereIn('status', $revenueStatuses)
            ->whereBetween('created_at', [$start->startOfDay(), $end->endOfDay()])
            ->selectRaw('COUNT(*) as orders, COALESCE(SUM(total), 0) as revenue, COALESCE(AVG(total), 0) as avg_order')
            ->first();

        $daily = Order::whereIn('status', $revenueStatuses)
            ->whereBetween('created_at', [$start->startOfDay(), $end->endOfDay()])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('date')
            ->orderByDesc('date')
            ->paginate(15)->withQueryString();

        $topProducts = MonthlyProductSale::with('product')
            ->orderByDesc('total_revenue')
            ->take(5)
            ->get();

        return view('manager.reports.index', compact('summary', 'daily', 'topProducts', 'start', 'end'));
    }

    /**
     * Ekspor laporan harian ke berkas CSV.
     */
    public function export(Request $request)
    {
        $start = $request->filled('start') ? $request->date('start') : now()->startOfMonth();
        $end = $request->filled('end') ? $request->date('end') : now();

        $rows = Order::whereIn('status', ['processing', 'shipped', 'completed'])
            ->whereBetween('created_at', [$start->startOfDay(), $end->endOfDay()])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('date')
            ->orderByDesc('date')
            ->get();

        $filename = 'laporan-penjualan-'.$start->format('Ymd').'-'.$end->format('Ymd').'.csv';

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Tanggal', 'Jumlah Pesanan', 'Total Pendapatan']);
            foreach ($rows as $row) {
                fputcsv($handle, [$row->date, $row->orders, $row->revenue]);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
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
