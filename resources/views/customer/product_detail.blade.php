<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('home') }}" class="text-emerald-600 hover:underline mr-2">&larr; Kembali ke Katalog</a>
    </x-slot>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 max-w-5xl mx-auto mt-6">
        <div class="flex flex-col md:flex-row gap-10">
            <!-- Product Image -->
            <div class="w-full md:w-1/2">
                <div class="relative w-full h-[350px] md:h-[450px] bg-gradient-to-br from-slate-50 to-slate-100 p-8 flex items-center justify-center overflow-hidden">
                    <!-- Background accent -->
                    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-white/40 via-transparent to-transparent opacity-60"></div>
                    
                    <!-- Wishlist Toggle (form nyata, bukan tombol mati) -->
                    @auth
                        @if(auth()->user()->role === 'Customer')
                        <form action="{{ route('customer.wishlists.store') }}" method="POST" class="absolute top-4 right-4 z-30">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="w-12 h-12 bg-white/90 backdrop-blur-md rounded-full flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-all shadow-lg border border-slate-100 hover:scale-110" title="Tambah / hapus dari Wishlist">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            </button>
                        </form>
                        @endif
                    @endauth

                    @if($product->image)
                        <img src="{{ Str::startsWith($product->image, 'http') ? $product->image : Storage::url($product->image) }}" alt="{{ $product->name }}" class="relative z-10 w-full h-full object-cover transform hover:scale-105 transition-transform duration-700 ease-out filter drop-shadow-2xl">
                    @else
                        <svg class="relative z-10 w-48 h-48 text-slate-300 drop-shadow-xl transform hover:scale-105 transition-transform duration-700 ease-out" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    @endif

                    @if($product->stock <= 0)
                    <div class="absolute inset-0 bg-white/60 backdrop-blur-md flex items-center justify-center z-20">
                        <span class="bg-slate-800 text-white font-black text-xl px-8 py-4 rounded-2xl shadow-2xl transform -rotate-6 border border-slate-700">STOK HABIS</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Product Details -->
            <div class="w-full md:w-1/2 flex flex-col pt-2">
                <div class="mb-4 flex items-center justify-between">
                    <span class="inline-block bg-emerald-50 text-emerald-700 px-4 py-1.5 rounded-full text-xs font-bold tracking-widest uppercase shadow-sm border border-emerald-100">
                        {{ $product->category->name ?? 'Kategori Umum' }}
                    </span>
                    @auth
                        @if(auth()->user()->role === 'Customer')
                        <form action="{{ route('customer.wishlists.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="text-slate-400 hover:text-rose-500 transition-colors bg-slate-50 hover:bg-rose-50 p-2 rounded-full border border-slate-100" title="Tambah / hapus dari Wishlist">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            </button>
                        </form>
                        @endif
                    @endauth
                </div>

                <h1 class="text-3xl md:text-4xl font-black text-slate-900 mb-4 leading-tight tracking-tight">{{ $product->name }}</h1>

                <div class="flex items-center gap-4 mb-6">
                    <div class="text-4xl md:text-5xl font-black text-emerald-600 tracking-tight">
                        <span class="text-2xl align-top">Rp</span>{{ number_format($product->price, 0, ',', '.') }}
                    </div>
                </div>

                <div class="prose prose-slate max-w-none mb-10">
                    <p class="text-slate-600 leading-loose text-lg font-medium opacity-90 whitespace-pre-line">
                        {{ $product->description ?: 'Produk ini belum memiliki deskripsi. Hubungi admin untuk detail lebih lanjut.' }}
                    </p>
                </div>

                <div class="mt-auto relative rounded-3xl p-6 md:p-8 border border-white/50 shadow-[0_8px_30px_-4px_rgba(16,185,129,0.1)] bg-gradient-to-br from-white/80 to-emerald-50/50 backdrop-blur-xl overflow-hidden">
                    <!-- Glassmorphism decorative circles -->
                    <div class="absolute -top-12 -right-12 w-32 h-32 bg-emerald-200 rounded-full mix-blend-multiply filter blur-2xl opacity-40"></div>
                    <div class="absolute -bottom-12 -left-12 w-32 h-32 bg-teal-200 rounded-full mix-blend-multiply filter blur-2xl opacity-40"></div>

                    <div class="relative z-10 flex items-center justify-between mb-6">
                        <span class="text-slate-700 font-bold flex items-center gap-2.5">
                            <span class="p-2 bg-white rounded-lg shadow-sm text-emerald-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            </span>
                            Ketersediaan Stok
                        </span>
                        <span class="font-black text-xl {{ $product->stock > 10 ? 'text-emerald-600' : ($product->stock > 0 ? 'text-amber-500' : 'text-red-600') }}">
                            {{ $product->stock > 0 ? $product->stock . ' Unit' : 'Stok Habis' }}
                        </span>
                    </div>

                    @if($product->stock > 0)
                    <form action="{{ route('customer.carts.store') }}" method="POST" class="relative z-10 flex flex-col sm:flex-row gap-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <div class="w-full sm:w-32 relative">
                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-full bg-white/80 backdrop-blur border border-slate-200/60 rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 text-center font-black text-xl h-14 shadow-inner" required>
                        </div>

                        <button type="submit" class="flex-1 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-white font-extrabold rounded-2xl h-14 shadow-[0_8px_20px_-6px_rgba(16,185,129,0.5)] hover:shadow-[0_12px_25px_-6px_rgba(16,185,129,0.6)] transition-all transform hover:-translate-y-1 flex items-center justify-center text-lg group border border-emerald-400/30">
                            <svg class="w-6 h-6 mr-2 group-hover:scale-125 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Masukkan Keranjang
                        </button>
                    </form>
                    @else
                    <button disabled class="relative z-10 w-full bg-slate-100/80 backdrop-blur text-slate-400 font-bold rounded-2xl h-14 cursor-not-allowed flex items-center justify-center text-lg border border-slate-200/60 shadow-inner">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                        Tidak Tersedia
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Seksi Ulasan Pelanggan -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 max-w-5xl mx-auto mt-6 mb-12">
        <h2 class="text-2xl font-bold text-slate-800 mb-6">Ulasan Pelanggan</h2>

        @if($product->reviews->where('status', 'published')->count() > 0)
            <div class="flex items-center gap-3 mb-6 pb-6 border-b border-slate-100">
                <div class="text-4xl font-black text-slate-800">{{ round($product->reviews->where('status', 'published')->avg('rating'), 1) }}</div>
                <div>
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= round($product->reviews->where('status', 'published')->avg('rating')) ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        @endfor
                    </div>
                    <div class="text-xs text-slate-500 mt-0.5">{{ $product->reviews->where('status', 'published')->count() }} ulasan pembeli</div>
                </div>
            </div>

            <div class="space-y-6">
                @foreach($product->reviews->where('status', 'published') as $review)
                <div class="border-b border-slate-100 pb-6 last:border-0 last:pb-0">
                    <div class="flex items-center justify-between mb-2">
                        <div class="font-bold text-slate-800">{{ $review->user->name }}</div>
                        <div class="text-sm text-slate-500">{{ $review->created_at->format('d M Y') }}</div>
                    </div>
                    <div class="flex items-center mb-3">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        @endfor
                        <span class="ml-2 font-semibold text-slate-700">{{ $review->rating }}.0</span>
                    </div>
                    <p class="text-slate-600">{{ $review->comment ?? 'Tidak ada komentar.' }}</p>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-slate-500">Belum ada ulasan untuk produk ini.</p>
            </div>
        @endif
    </div>

    <!-- Seksi Produk Serupa / Rekomendasi -->
    @if(isset($similarProducts) && $similarProducts->isNotEmpty())
    <div class="max-w-5xl mx-auto mb-12">
        <div class="flex items-center mb-6">
            <div class="bg-blue-100 p-2 rounded-xl mr-3 text-blue-500">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            </div>
            <h2 class="text-2xl font-bold text-slate-800">Rekomendasi Produk Serupa</h2>
            <span class="ml-4 bg-emerald-100 text-emerald-700 text-xs font-bold px-2 py-1 rounded-md border border-emerald-200">Berdasarkan Kategori</span>
        </div>
        <div class="flex overflow-x-auto space-x-4 pb-4 hide-scrollbar">
            @foreach($similarProducts as $simProduct)
                <x-product-card :product="$simProduct" size="sm" class="min-w-[200px] w-[200px]" />
            @endforeach
        </div>
    </div>
    @endif
</x-app-layout>
