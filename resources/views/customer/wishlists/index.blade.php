<x-app-layout>
    <x-slot name="header">
        Wishlist Saya
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-6">
        <div class="flex items-center mb-8">
            <div class="bg-rose-100 p-2.5 rounded-xl mr-4 text-rose-500 shadow-sm border border-rose-200/50">
                <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
            </div>
            <div>
                <h2 class="text-3xl font-black text-slate-800 tracking-tight">Wishlist Saya</h2>
                <p class="text-slate-500 mt-1 font-medium">Koleksi produk favorit yang ingin Anda beli.</p>
            </div>
        </div>

        @if($wishlists->isEmpty())
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-16 text-center">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Wishlist Masih Kosong</h3>
                <p class="text-slate-500 mb-6">Anda belum menambahkan produk apapun ke daftar keinginan.</p>
                <a href="{{ route('home') }}" class="inline-block bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-8 rounded-xl transition">Mulai Belanja</a>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                @foreach($wishlists as $item)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col group relative">

                    <!-- Hapus tombol -->
                    <form action="{{ route('customer.wishlists.store') }}" method="POST" class="absolute top-3 right-3 z-10">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                        <button type="submit" class="w-8 h-8 bg-white/80 backdrop-blur rounded-full flex items-center justify-center text-rose-500 hover:bg-rose-500 hover:text-white transition shadow-sm border border-slate-100" title="Hapus dari Wishlist">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                        </button>
                    </form>

                    <a href="{{ route('products.show', $item->product_id) }}" class="flex flex-col flex-grow">
                        <div class="h-48 bg-slate-50 relative overflow-hidden flex justify-center items-center">
                            @if($item->product->image)
                                <img src="{{ Str::startsWith($item->product->image, 'http') ? $item->product->image : Storage::url($item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <svg class="w-16 h-16 text-slate-300 group-hover:scale-110 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            @endif
                        </div>
                        <div class="p-4 flex flex-col flex-grow">
                            <h3 class="text-sm font-medium text-slate-700 line-clamp-2 mb-2 h-10 group-hover:text-emerald-600 transition">{{ $item->product->name }}</h3>
                            <div class="font-black text-slate-900 mt-auto text-lg mb-4">Rp {{ number_format($item->product->price, 0, ',', '.') }}</div>
                        </div>
                    </a>

                    <!-- Masukkan Keranjang -->
                    <div class="px-4 pb-4">
                        @if($item->product->stock > 0)
                        <form action="{{ route('customer.carts.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="w-full bg-emerald-100 hover:bg-emerald-600 text-emerald-700 hover:text-white font-bold py-2 rounded-xl transition flex items-center justify-center text-sm border border-emerald-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Ke Keranjang
                            </button>
                        </form>
                        @else
                        <button disabled class="w-full bg-slate-100 text-slate-400 font-bold py-2 rounded-xl cursor-not-allowed text-sm flex items-center justify-center">
                            Stok Habis
                        </button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
