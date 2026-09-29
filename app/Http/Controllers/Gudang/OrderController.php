<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Status pesanan yang relevan untuk operasional gudang.
     */
    private const WAREHOUSE_STATUSES = ['processing', 'shipped', 'completed', 'returned'];

    /**
     * Daftar pesanan yang perlu disiapkan & dikirim gudang.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->whereIn('status', self::WAREHOUSE_STATUSES);
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('gudang.orders.index', compact('orders'));
    }

    public function create()
    {
        abort(404);
    }

    public function store(Request $request)
    {
        abort(404);
    }

    /**
     * Detail pesanan untuk persiapan pengiriman.
     */
    public function show(string $id)
    {
        $order = Order::with(['orderItems.product', 'user'])->findOrFail($id);

        return view('gudang.orders.show', compact('order'));
    }

    public function edit(string $id)
    {
        abort(404);
    }

    /**
     * Update status pengiriman & input nomor resi.
     */
    public function update(Request $request, string $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:processing,shipped,completed,returned',
            'courier' => 'nullable|string|max:100',
            'resi' => 'nullable|string|max:100',
        ]);

        if ($validated['status'] === 'shipped' && ! $order->shipped_at) {
            $validated['shipped_at'] = now();
        }

        $order->update($validated);

        return back()->with('success', 'Status pesanan #ORD-'.str_pad($order->id, 6, '0', STR_PAD_LEFT).' berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        abort(403, 'Gudang tidak memiliki izin menghapus pesanan.');
    }
}
