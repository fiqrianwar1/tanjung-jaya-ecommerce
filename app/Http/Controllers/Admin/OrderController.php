<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\StockLog;
use App\Support\RecordsAuditLog;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use RecordsAuditLog;

    /**
     * Status pesanan yang dipakai aplikasi (konsisten di seluruh modul).
     */
    private const STATUSES = 'pending,processing,shipped,completed,returned,cancelled';

    /**
     * Daftar seluruh pesanan.
     */
    public function index(Request $request)
    {
        $query = Order::with('user')->withCount('orderItems');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%"));
            });
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('admin.orders.index', compact('orders'));
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
     * Detail pesanan (halaman penuh, bukan modal).
     */
    public function show(string $id)
    {
        $order = Order::with(['orderItems.product', 'user', 'returnRequest'])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    public function edit(string $id)
    {
        abort(404);
    }

    /**
     * Perbarui status / kurir / resi pesanan.
     */
    public function update(Request $request, string $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:'.self::STATUSES,
            'courier' => 'nullable|string|max:100',
            'resi' => 'nullable|string|max:100',
        ]);

        $old = $order->only(['status', 'courier', 'resi']);

        // Catat waktu pengiriman pertama kali
        if ($validated['status'] === 'shipped' && ! $order->shipped_at) {
            $validated['shipped_at'] = now();
        }

        $order->update($validated);

        $this->recordAudit('Update Status Pesanan #ORD-'.str_pad($order->id, 6, '0', STR_PAD_LEFT), $order->id, $old, $validated);

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Pesanan berhasil diperbarui.');
    }

    /**
     * Batalkan pesanan & kembalikan stok produk bila belum dikirim.
     */
    public function destroy(string $id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);

        if (! in_array($order->status, ['pending', 'processing', 'cancelled'])) {
            return redirect()->route('admin.orders.index')
                ->with('error', 'Pesanan yang sudah dikirim tidak dapat dibatalkan/dihapus.');
        }

        // Kembalikan stok hanya jika barang belum keluar gudang (masih processing)
        if ($order->status === 'processing') {
            foreach ($order->orderItems as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->qty);
                    StockLog::create([
                        'product_id' => $item->product_id,
                        'user_id' => auth()->id(),
                        'type' => 'in',
                        'qty' => $item->qty,
                    ]);
                }
            }
        }

        $this->recordAudit('Hapus Pesanan #ORD-'.str_pad($order->id, 6, '0', STR_PAD_LEFT), $order->id, $order->only(['status', 'total']), null);

        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Pesanan berhasil dibatalkan & dihapus.');
    }
}
