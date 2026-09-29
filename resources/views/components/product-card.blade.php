@props([
    'product',
    'rating' => null,
    'showWishlist' => true,
    'size' => 'md', // sm = carousel, md = grid katalog
])

@php
    $isSmall = $size === 'sm';

    // Rating rata-rata dari relasi reviews bila tersedia (tanpa query tambahan bila belum di-load)
    $avgRating = $rating ?? ($product->relationLoaded('reviews') && $product->reviews->isNotEmpty()
        ? round($product->reviews->avg('rating'), 1)
        : null);

    $reviewCount = $product->relationLoaded('reviews') ? $product->reviews->count() : 0;

    $imageUrl = $product->image
        ? (\Illuminate\Support\Str::startsWith($product->image, 'http') ? $product->image : \Illuminate\Support\Facades\Storage::url($product->image))
        : null;
@endphp

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-[0_8px_30px_-4px_rgba(0,0,0,0.1)] hover:-translate-y-1 transition-all duration-300 flex flex-col group relative']) }}>
    @auth
        @if($showWishlist && auth()->user()->role === 'Customer')
        <form action="{{ route('customer.wishlists.store') }}" method="POST" @class(['absolute z-20', 'top-3 right-3' => !$isSmall, 'top-2 left-2' => $isSmall])>
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <button type="submit" @class([
                'bg-white/80 backdrop-blur rounded-full flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition shadow-sm border border-slate-100',
                'w-8 h-8' => !$isSmall,
                'w-7 h-7' => $isSmall,
            ]) title="Tambah / hapus dari Wishlist">
                <svg @class(['w-4 h-4' => !$isSmall, 'w-3.5 h-3.5' => $isSmall]) fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
            </button>
        </form>
        @endif
    @else
        @if($showWishlist)
        <a href="{{ route('login') }}" @class(['absolute z-20', 'top-3 right-3' => !$isSmall, 'top-2 left-2' => $isSmall, 'bg-white/80 backdrop-blur rounded-full flex items-center justify-center text-slate-400 hover:text-rose-500 transition shadow-sm border border-slate-100', 'w-8 h-8' => !$isSmall, 'w-7 h-7' => $isSmall]) title="Masuk untuk wishlist">
            <svg @class(['w-4 h-4' => !$isSmall, 'w-3.5 h-3.5' => $isSmall]) fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
        </a>
        @endif
    @endauth

    <!-- Gambar -->
    <a href="{{ route('products.show', $product->id) }}" @class(['bg-slate-50 relative overflow-hidden flex items-center justify-center', 'h-44 md:h-52' => !$isSmall, 'h-40' => $isSmall])>
        @if($imageUrl)
            <img src="{{ $imageUrl }}" alt="{{ $product->name }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out">
        @else
            <svg class="w-12 h-12 text-slate-300 group-hover:scale-110 transition-transform duration-700 ease-out" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        @endif

        @if($product->stock <= 0)
        <div class="absolute inset-0 bg-white/70 flex items-center justify-center backdrop-blur-sm">
            <span class="bg-slate-800 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-lg">Stok Habis</span>
        </div>
        @endif
    </a>

    <!-- Detail -->
    <div class="p-4 flex flex-col flex-grow">
        <a href="{{ route('products.show', $product->id) }}" @class([
            'font-medium text-slate-700 leading-snug line-clamp-2 mb-2 group-hover:text-emerald-600 transition-colors',
            'text-[13px] md:text-sm min-h-[36px] md:min-h-[40px]' => !$isSmall,
            'text-sm h-[40px]' => $isSmall,
        ])>
            {{ $product->name }}
        </a>

        <div class="text-base md:text-lg font-black text-slate-900 mt-auto mb-2">
            Rp{{ number_format($product->price, 0, ',', '.') }}
        </div>

        <div class="flex items-center text-xs font-medium text-slate-500 mb-2">
            <svg class="w-3.5 h-3.5 mr-1 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            <span class="truncate">{{ $product->category->name ?? 'Tanjung Jaya' }}</span>
        </div>

        <div class="flex items-center text-xs text-slate-500 mb-3 font-medium">
            @if($avgRating)
                <svg class="w-3.5 h-3.5 text-amber-400 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                <span>{{ $avgRating }}</span>
                <span class="mx-1.5 text-slate-300">|</span>
                <span>{{ $reviewCount }} ulasan</span>
            @else
                <span class="text-slate-400">Belum ada ulasan</span>
            @endif

            @if($product->stock > 0 && $product->stock <= 10)
                <span class="ml-auto text-[11px] font-bold text-amber-600 bg-amber-50 border border-amber-100 px-2 py-0.5 rounded-md">Sisa {{ $product->stock }}</span>
            @endif
        </div>

        @if($product->stock > 0)
            @auth
            <form action="{{ route('customer.carts.store') }}" method="POST" class="mt-auto">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="w-full bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white border border-emerald-200 hover:border-emerald-500 py-2 rounded-xl font-bold text-sm transition-all focus:outline-none flex items-center justify-center">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Keranjang
                </button>
            </form>
            @else
            <a href="{{ route('login') }}" class="mt-auto w-full bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white border border-emerald-200 hover:border-emerald-500 py-2 rounded-xl font-bold text-sm transition-all flex items-center justify-center">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Keranjang
            </a>
            @endauth
        @endif
    </div>
</div>