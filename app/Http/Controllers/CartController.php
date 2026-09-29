<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Order::with(['orderItems.product.category'])
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        // Keranjang bisa memuat produk yang sudah dihapus/dinonaktifkan admin -> tandai untuk ditampilkan
        $unavailableItemIds = $cart
            ? $cart->orderItems
                ->filter(fn ($item) => ! $item->product || $item->product->status !== 'active')
                ->pluck('id')
                ->all()
            : [];

        return view('customer.carts.index', compact('cart', 'unavailableItemIds'));
    }

    /**
     * Perbarui jumlah (qty) sebuah item di keranjang.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $orderItem = OrderItem::with('product')->whereHas('order', function ($q) {
            $q->where('user_id', Auth::id())->where('status', 'pending');
        })->findOrFail($id);

        // Produk bisa saja sudah dihapus / dinonaktifkan admin setelah masuk keranjang
        if (! $orderItem->product || $orderItem->product->status !== 'active') {
            return redirect()->route('customer.carts.index')
                ->with('error', 'Produk ini sudah tidak tersedia. Silakan hapus dari keranjang.');
        }

        if ($request->quantity > $orderItem->product->stock) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi untuk jumlah ini.');
        }

        $orderItem->update([
            'qty' => $request->quantity,
            'price' => $orderItem->product->price,
        ]);

        $orderItem->order->recalculateTotal();

        return redirect()->route('customer.carts.index')->with('success', 'Jumlah produk berhasil diperbarui.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->status !== 'active') {
            return redirect()->back()->with('error', 'Produk ini sedang tidak tersedia.');
        }

        if ($product->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi.');
        }
        // Key kolom 'total' sesuai skema tabel orders (bukan 'total_amount')
        $cart = Order::firstOrCreate(
            ['user_id' => Auth::id(), 'status' => 'pending'],
            ['total' => 0, 'shipping_cost' => 0]
        );

        $orderItem = OrderItem::where('order_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($orderItem) {
            $newQuantity = $orderItem->qty + $request->quantity;
            if ($newQuantity > $product->stock) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi untuk jumlah ini.');
            }
            $orderItem->update([
                'qty' => $newQuantity,
                'price' => $product->price,
            ]);
        } else {
            OrderItem::create([
                'order_id' => $cart->id,
                'product_id' => $product->id,
                'qty' => $request->quantity,
                'price' => $product->price,
            ]);
        }

        // Recalculate order total
        $cart->recalculateTotal();

        return redirect()->route('customer.carts.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function destroy(string $id)
    {
        $orderItem = OrderItem::whereHas('order', function ($q) {
            $q->where('user_id', Auth::id())->where('status', 'pending');
        })->findOrFail($id);

        $order = $orderItem->order;
        $productName = $orderItem->product->name ?? 'Produk';
        $orderItem->delete();

        $order->recalculateTotal();

        return redirect()->route('customer.carts.index')->with('success', $productName.' dihapus dari keranjang.');
    }
}
