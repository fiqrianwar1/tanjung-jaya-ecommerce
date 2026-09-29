<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\StockLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('orderItems.product')
            ->where('user_id', Auth::id())
            ->where('status', '!=', 'pending')
            ->latest()
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $cart = Order::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        if ($cart->orderItems->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang kosong.');
        }

        // Guard: cegah checkout ganda (double submit) pada keranjang yang sama
        if ($request->session()->has('checkout_processed_'.$cart->id)) {
            return redirect()->route('customer.orders.index')
                ->with('warning', 'Pesanan ini sudah diproses sebelumnya.');
        }

        try {
            DB::transaction(function () use ($cart) {
                // Ambil ulang item di dalam transaksi + kunci baris produk agar stok tidak balapan
                $items = $cart->orderItems()->with('product')->get();
                $productIds = $items->pluck('product_id')->all();

                $products = Product::whereIn('id', $productIds)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                // Validasi seluruh item dulu sebelum stok dikurangi
                foreach ($items as $item) {
                    $product = $products->get($item->product_id);

                    if (! $product || $product->status !== 'active') {
                        throw new \RuntimeException('Salah satu produk di keranjang sudah tidak dijual lagi. Silakan hapus dari keranjang.');
                    }

                    if ($product->stock < $item->qty) {
                        throw new \RuntimeException('Stok produk '.$product->name.' tidak mencukupi (tersisa '.$product->stock.').');
                    }
                }

                // Kurangi stok & catat mutasi stok untuk setiap item
                foreach ($items as $item) {
                    $product = $products->get($item->product_id);
                    $product->decrement('stock', $item->qty);

                    StockLog::create([
                        'product_id' => $product->id,
                        'user_id' => Auth::id(),
                        'type' => 'out',
                        'qty' => $item->qty,
                    ]);
                }

                // Tandai keranjang sudah diproses & ubah status menjadi diproses gudang
                $cart->update(['status' => 'processing']);
            });
        } catch (\RuntimeException $e) {
            return redirect()->route('customer.carts.index')->with('error', $e->getMessage());
        }

        $request->session()->put('checkout_processed_'.$cart->id, true);

        // Segarkan rekomendasi karena riwayat pesanan user berubah
        try {
            Cache::forget('user_recommendations_ids_'.Auth::id());
        } catch (\Throwable $e) {
            // abaikan: kegagalan cache tidak boleh menggagalkan checkout
        }

        return redirect()->route('customer.orders.index')->with('success', 'Checkout berhasil! Pesanan Anda sedang diproses oleh Gudang.');
    }

    public function show(string $id)
    {
        $order = Order::with(['orderItems.product', 'returnRequest'])->where('user_id', Auth::id())->findOrFail($id);

        return view('customer.orders.show', compact('order'));
    }
}
