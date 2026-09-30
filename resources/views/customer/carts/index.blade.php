<x-app-layout>
    @section('title', 'Keranjang Belanja')

    <x-slot name="header">
        Keranjang Belanja
    </x-slot>

    <div class="pb-12 mt-6">
        <h2 class="text-2xl font-extrabold text-slate-800 mb-6">Keranjang Belanja Anda</h2>

        @if(!$cart || $cart->orderItems->isEmpty())
            <div class="bg-gradient-to-br from-white to-slate-50 rounded-3xl shadow-[0_8px_30px_-4px_rgba(0,0,0,0.05)] border border-slate-100 p-16 text-center relative overflow-hidden">
                <!-- Decorative background elements -->
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-emerald-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-teal-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 pointer-events-none"></div>
                
                <div class="relative z-10 w-32 h-32 bg-white shadow-xl shadow-emerald-500/10 rounded-full flex items-center justify-center mx-auto mb-8 border border-emerald-50">
                    <svg class="w-14 h-14 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <!-- Small decorative badge on cart -->
                    <div class="absolute -top-1 -right-1 bg-rose-500 w-6 h-6 rounded-full border-4 border-white"></div>
                </div>
                <h3 class="text-2xl font-black text-slate-800 mb-3 relative z-10 tracking-tight">Keranjang Anda masih kosong</h3>
                <p class="text-slate-500 mb-10 relative z-10 text-lg">Yuk, temukan barang-barang menarik di etalase kami dan isi keranjangmu!</p>
                <a href="{{ route('home') }}" class="relative z-10 inline-flex items-center bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-white font-bold py-4 px-10 rounded-2xl transition shadow-[0_8px_25px_-6px_rgba(16,185,129,0.5)] transform hover:-translate-y-1 hover:shadow-[0_12px_30px_-6px_rgba(16,185,129,0.6)] border border-emerald-400/30">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    Mulai Belanja Sekarang
                </a>
            </div>
        @else
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Daftar Barang -->
                <div class="w-full lg:w-2/3">
                    <div class="bg-white rounded-3xl shadow-[0_8px_30px_-4px_rgba(0,0,0,0.05)] border border-slate-100 overflow-hidden">
                        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 backdrop-blur-sm">
                            <h3 class="font-extrabold text-slate-800 text-xl flex items-center gap-2">
                                <span class="bg-emerald-100 p-1.5 rounded-lg text-emerald-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                </span>
                                Daftar Produk
                            </h3>
                            <span class="text-sm font-bold bg-white shadow-sm px-4 py-1.5 rounded-full border border-slate-200 text-slate-600 tracking-wide">{{ $cart->orderItems->count() }} Macam Barang</span>
                        </div>
                        
                        <ul class="divide-y divide-slate-100">
                            @foreach($cart->orderItems as $item)
                                <li class="p-6 md:p-8 flex flex-col sm:flex-row gap-6 hover:bg-slate-50/80 transition-colors duration-300 group">
                                    <div class="w-28 h-28 bg-gradient-to-br from-slate-50 to-slate-100 rounded-2xl flex-shrink-0 flex items-center justify-center border border-slate-200 shadow-inner group-hover:border-emerald-200 transition-colors overflow-hidden relative">
                                        @if($item->product->image)
                                            <img src="{{ Str::startsWith($item->product->image, 'http') ? $item->product->image : Storage::url($item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-12 h-12 text-slate-300 group-hover:scale-110 group-hover:text-emerald-300 transition-all duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        @endif
                                    </div>
                                    <div class="flex-1 flex flex-col justify-between">
                                        <div class="flex justify-between items-start gap-4">
                                            <div>
                                                <a href="{{ route('products.show', $item->product_id) }}" class="font-extrabold text-lg text-slate-800 hover:text-emerald-600 transition mb-1.5 inline-block line-clamp-2 leading-snug">
                                                    {{ $item->product->name }}
                                                </a>
                                                <div class="text-sm text-slate-500 font-semibold bg-white border border-slate-200 shadow-sm inline-block px-3 py-1 rounded-lg mt-1">Rp {{ number_format($item->price, 0, ',', '.') }} <span class="text-slate-400 font-normal">/ unit</span></div>
                                            </div>
                                            <div class="font-black text-xl text-slate-900 whitespace-nowrap bg-emerald-50/50 px-3 py-1 rounded-xl">
                                                <span class="text-sm font-bold text-emerald-600 align-top">Rp</span> {{ number_format($item->qty * $item->price, 0, ',', '.') }}
                                            </div>
                                        </div>
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mt-6 pt-4 border-t border-slate-100 border-dashed">
                                            <div class="flex items-center gap-3">
                                                <!-- Kontrol jumlah -->
                                                @if(in_array($item->id, $unavailableItemIds ?? []))
                                                    <span class="text-xs font-bold text-rose-700 bg-rose-50 border border-rose-200 px-3 py-1.5 rounded-xl">Produk sudah tidak dijual — harap hapus dari keranjang</span>
                                                @else
                                                <form action="{{ route('customer.carts.update', $item->id) }}" method="POST" class="flex items-center gap-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Qty</label>
                                                    <input type="number" name="quantity" value="{{ $item->qty }}" min="1" max="{{ max($item->product->stock, 1) }}" class="w-20 text-center border-slate-300 rounded-xl text-sm font-bold shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                                                    <button type="submit" class="p-2 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white border border-emerald-100 transition" title="Perbarui jumlah">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                                    </button>
                                                </form>
                                                <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-100 px-3 py-1.5 rounded-xl">Stok: {{ $item->product->stock }}</span>
                                                @endif
                                            </div>
                                            <form action="{{ route('customer.carts.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus barang ini dari keranjang?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-white font-bold text-sm flex items-center px-4 py-2 hover:bg-red-500 rounded-xl transition-all border border-red-100 hover:border-red-500 shadow-sm hover:shadow-red-500/30 group">
                                                    <svg class="w-4 h-4 mr-1.5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Ringkasan Belanja -->
                <div class="w-full lg:w-1/3">
                    <div class="relative bg-gradient-to-br from-white/90 to-emerald-50/30 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_-4px_rgba(16,185,129,0.1)] border border-emerald-100/50 p-6 md:p-8 sticky top-24 overflow-hidden">
                        <!-- Decorative glow -->
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-200 rounded-full mix-blend-multiply filter blur-2xl opacity-50"></div>

                        <h3 class="font-black text-xl text-slate-800 mb-8 flex items-center gap-3 relative z-10">
                            <span class="p-2.5 bg-emerald-100 text-emerald-600 rounded-xl shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </span>
                            Ringkasan Belanja
                        </h3>

                        <div class="space-y-5 mb-6 text-sm text-slate-600 border-b border-emerald-100/50 pb-8 relative z-10">
                            <div class="flex justify-between items-center">
                                <span class="font-medium">Total Harga ({{ $cart->orderItems->sum('qty') }} Barang)</span>
                                <span class="font-bold text-slate-800 text-base">Rp {{ number_format($cart->subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center bg-white/50 px-3 py-2 rounded-lg border border-slate-100">
                                <span class="flex items-center font-medium">
                                    Diskon
                                    <span class="ml-2 text-[10px] bg-gradient-to-r from-red-500 to-rose-500 text-white font-bold px-2 py-0.5 rounded-full uppercase tracking-wider shadow-sm">Promo</span>
                                </span>
                                <span class="font-bold text-emerald-600">- Rp 0</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-end mb-10 relative z-10">
                            <span class="font-extrabold text-slate-800 text-base">Total Tagihan</span>
                            <span class="font-black text-3xl text-emerald-600 drop-shadow-sm">Rp {{ number_format($cart->total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Form Checkout (di luar <ul> agar tidak nested form) -->
                    <form action="{{ route('customer.orders.store') }}" method="POST" class="mt-6">
                        @csrf
                        <input type="hidden" name="cart_id" value="{{ $cart->id }}">
                        <button type="submit" class="w-full bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-white font-extrabold rounded-2xl transition shadow-[0_8px_20px_-6px_rgba(16,185,129,0.5)] flex items-center justify-center transform hover:-translate-y-1 hover:shadow-[0_12px_25px_-6px_rgba(16,185,129,0.6)] border border-emerald-400/30 group text-lg h-14">
                            Checkout Sekarang
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
