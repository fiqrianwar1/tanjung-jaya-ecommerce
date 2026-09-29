<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;

class ReturnRequestController extends Controller
{
    /**
     * Daftar retur yang perlu diterima & diperiksa secara fisik.
     */
    public function index(Request $request)
    {
        $query = ReturnRequest::with(['order.user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $returns = $query->latest()->paginate(10)->withQueryString();

        return view('gudang.returns.index', compact('returns'));
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
     * Detail retur + bukti foto.
     */
    public function show(string $id)
    {
        $return = ReturnRequest::with(['order.user', 'order.orderItems.product'])->findOrFail($id);

        return view('gudang.returns.show', compact('return'));
    }

    public function edit(string $id)
    {
        abort(404);
    }

    /**
     * Konfirmasi kondisi fisik barang retur (layak jual / rusak).
     */
    public function update(Request $request, string $id)
    {
        $return = ReturnRequest::with('order.orderItems.product')->findOrFail($id);

        $validated = $request->validate([
            'physical_status' => 'required|in:layak,rusak',
        ]);

        if ($validated['physical_status'] === 'rusak') {
            // Barang rusak dicatat sebagai bad_stock, bukan stok layak jual
            foreach ($return->order->orderItems as $item) {
                if ($item->product) {
                    $item->product->increment('bad_stock', $item->qty);
                    $item->product->logStock('adjustment', $item->qty);
                }
            }

            return back()->with('warning', 'Barang retur dicatat sebagai stok rusak (bad stock).');
        }

        foreach ($return->order->orderItems as $item) {
            if ($item->product) {
                $item->product->increment('stock', $item->qty);
                $item->product->logStock('in', $item->qty);
            }
        }

        $return->update(['status' => 'Selesai']);

        return back()->with('success', 'Barang retur diterima & dikembalikan ke stok layak jual.');
    }

    public function destroy(string $id)
    {
        abort(403, 'Gudang tidak memiliki izin menghapus pengajuan retur.');
    }
}
