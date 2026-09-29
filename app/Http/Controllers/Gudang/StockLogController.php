<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockLog;
use Illuminate\Http\Request;

class StockLogController extends Controller
{
    /**
     * Monitor stok & riwayat mutasi inventori.
     */
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%');
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $request->status === 'low'
                    ? $q->where('stock', '<=', 10)
                    : $q->where('stock', '>', 10);
            })
            ->orderBy('stock')
            ->paginate(12, ['*'], 'products_page')
            ->withQueryString();

        $logs = StockLog::with(['product', 'user'])
            ->when($request->filled('product_id'), fn ($q) => $q->where('product_id', $request->product_id))
            ->latest()
            ->paginate(15, ['*'], 'logs_page')
            ->withQueryString();

        return view('gudang.stocks.index', compact('products', 'logs'));
    }

    /**
     * Simpan mutasi stok (barang masuk / stok opname / penyesuaian).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out,adjustment',
            'qty' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if ($validated['type'] === 'in') {
            $product->increment('stock', $validated['qty']);
        } elseif ($validated['type'] === 'out') {
            if ($product->stock < $validated['qty']) {
                return back()->with('error', 'Stok tidak mencukupi untuk dikurangi.');
            }
            $product->decrement('stock', $validated['qty']);
        } else {
            // Penyesuaian: qty dianggap sebagai jumlah stok akhir
            $product->update(['stock' => $validated['qty']]);
        }

        $product->logStock($validated['type'], $validated['qty']);

        return back()->with('success', 'Mutasi stok untuk "'.$product->name.'" berhasil dicatat.');
    }

    /**
     * Set jumlah stok rusak / tidak layak jual.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'bad_stock' => 'required|integer|min:0',
        ]);

        $product = Product::findOrFail($id);
        $product->update($validated);

        return back()->with('success', 'Jumlah stok rusak berhasil diperbarui.');
    }

    public function create()
    {
        return redirect()->route('gudang.stocks.index');
    }

    public function show(string $id)
    {
        return redirect()->route('gudang.stocks.index', ['product_id' => $id]);
    }

    public function edit(string $id)
    {
        return redirect()->route('gudang.stocks.index');
    }

    public function destroy(string $id)
    {
        abort(403, 'Riwayat mutasi stok tidak dapat dihapus.');
    }
}
