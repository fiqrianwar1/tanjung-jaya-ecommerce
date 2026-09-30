<x-app-layout>
    @section('title', 'Katalog Produk')

    <x-slot name="header">Tanjung Jaya</x-slot>

    <!-- Carousel Banner with Alpine.js -->
    <div class="mb-10 relative rounded-3xl overflow-hidden shadow-2xl group w-full h-[350px] md:h-[450px]" x-data="{ slide: 0, total: 3 }" x-init="setInterval(() => slide = (slide + 1) % total, 5000)">
        
        <!-- Slide 1 -->
        <div x-show="slide === 0" x-transition.opacity.duration.700ms class="absolute inset-0 w-full h-full bg-slate-900 flex items-center px-8 md:px-20 text-white overflow-hidden group/slide">
            <img src="{{ asset('images/products/cat-interior.jpg') }}" class="absolute inset-0 w-full h-full object-cover opacity-60 mix-blend-overlay group-hover/slide:scale-105 transition-transform duration-[2000ms]" alt="Cat Tembok Interior">
            <div class="absolute inset-0 bg-gradient-to-r from-emerald-950/95 via-emerald-900/80 to-transparent"></div>

            <div class="relative z-10 max-w-2xl transform transition duration-700 delay-100 translate-y-0 opacity-100">
                <span class="inline-block py-1 px-4 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-xs font-black tracking-widest mb-4 backdrop-blur-md uppercase">Promo Terbatas</span>
                <h2 class="text-4xl md:text-6xl font-black mb-4 tracking-tighter drop-shadow-lg leading-tight">Promo Gajian <br><span class="text-emerald-400">Paling Cuan!</span></h2>
                <p class="text-lg md:text-xl font-medium mb-8 text-emerald-50/90 drop-shadow-md">Nikmati diskon spektakuler hingga 90% setiap hari. Jangan sampai terlewat.</p>
                <a href="#" class="bg-emerald-500 text-white px-8 py-3.5 rounded-full font-bold shadow-[0_0_20px_rgba(16,185,129,0.4)] hover:shadow-[0_0_30px_rgba(16,185,129,0.6)] hover:bg-emerald-400 transition-all transform hover:-translate-y-1 inline-block border border-emerald-400/50">Klaim Promo Sekarang</a>
            </div>
        </div>

        <!-- Slide 2 -->
        <div x-show="slide === 1" x-cloak x-transition.opacity.duration.700ms class="absolute inset-0 w-full h-full bg-slate-900 flex items-center px-8 md:px-20 text-white overflow-hidden group/slide">
            <img src="{{ asset('images/products/alat-pengecatan.jpg') }}" class="absolute inset-0 w-full h-full object-cover opacity-60 mix-blend-overlay group-hover/slide:scale-105 transition-transform duration-[2000ms]" alt="Alat Pengecatan">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-950/95 via-indigo-900/80 to-transparent"></div>

            <div class="relative z-10 max-w-2xl transform transition duration-700 delay-100 translate-y-0 opacity-100">
                <span class="inline-block py-1 px-4 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-300 text-xs font-black tracking-widest mb-4 backdrop-blur-md uppercase">Pengiriman Cepat</span>
                <h2 class="text-4xl md:text-6xl font-black mb-4 tracking-tighter drop-shadow-lg leading-tight">Gratis Ongkir <br><span class="text-blue-400">Seluruh Indonesia</span></h2>
                <p class="text-lg md:text-xl font-medium mb-8 text-blue-50/90 drop-shadow-md">Belanja puas tanpa pusing ongkir. Berlaku untuk semua produk favorit Anda.</p>
                <a href="#" class="bg-blue-600 text-white px-8 py-3.5 rounded-full font-bold shadow-[0_0_20px_rgba(37,99,235,0.4)] hover:shadow-[0_0_30px_rgba(37,99,235,0.6)] hover:bg-blue-500 transition-all transform hover:-translate-y-1 inline-block border border-blue-400/50">Mulai Belanja</a>
            </div>
        </div>

        <!-- Slide 3 -->
        <div x-show="slide === 2" x-cloak x-transition.opacity.duration.700ms class="absolute inset-0 w-full h-full bg-slate-900 flex items-center px-8 md:px-20 text-white overflow-hidden group/slide">
            <img src="{{ asset('images/products/cat-eksterior.jpg') }}" class="absolute inset-0 w-full h-full object-cover opacity-60 mix-blend-overlay group-hover/slide:scale-105 transition-transform duration-[2000ms]" alt="Cat Tembok Eksterior">
            <div class="absolute inset-0 bg-gradient-to-r from-amber-950/95 via-orange-900/80 to-transparent"></div>
            
            <div class="relative z-10 max-w-2xl transform transition duration-700 delay-100 translate-y-0 opacity-100">
                <span class="inline-block py-1 px-4 rounded-full bg-amber-500/20 border border-amber-400/30 text-amber-300 text-xs font-black tracking-widest mb-4 backdrop-blur-md uppercase">Waktu Terbatas</span>
                <h2 class="text-4xl md:text-6xl font-black mb-4 tracking-tighter drop-shadow-lg leading-tight">Flash Sale <br><span class="text-amber-400">Gila-Gilaan!</span></h2>
                <p class="text-lg md:text-xl font-medium mb-8 text-amber-50/90 drop-shadow-md">Diskon kilat untuk produk pilihan. Siapa cepat dia dapat!</p>
                <a href="#" class="bg-gradient-to-r from-amber-500 to-orange-500 text-white px-8 py-3.5 rounded-full font-bold shadow-[0_0_20px_rgba(245,158,11,0.4)] hover:shadow-[0_0_30px_rgba(245,158,11,0.6)] hover:from-amber-400 hover:to-orange-400 transition-all transform hover:-translate-y-1 inline-block border border-amber-300/50">Cek Flash Sale</a>
            </div>
        </div>
        
        <!-- Controls -->
        <button @click="slide = (slide === 0 ? total - 1 : slide - 1)" class="absolute z-20 top-1/2 left-4 md:left-8 -translate-y-1/2 bg-white/20 backdrop-blur-xl border border-white/30 hover:bg-white hover:text-emerald-600 text-white rounded-full p-3 md:p-4 shadow-xl opacity-0 group-hover:opacity-100 transition-all duration-300 transform hover:scale-110">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
        </button>
        <button @click="slide = (slide + 1) % total" class="absolute z-20 top-1/2 right-4 md:right-8 -translate-y-1/2 bg-white/20 backdrop-blur-xl border border-white/30 hover:bg-white hover:text-emerald-600 text-white rounded-full p-3 md:p-4 shadow-xl opacity-0 group-hover:opacity-100 transition-all duration-300 transform hover:scale-110">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
        </button>
        
        <!-- Pagination Dots -->
        <div class="absolute z-20 bottom-6 left-1/2 -translate-x-1/2 flex space-x-3">
            <template x-for="i in total">
                <button @click="slide = i - 1" class="h-2 rounded-full transition-all duration-300 shadow-sm" :class="slide === i - 1 ? 'bg-emerald-400 w-8' : 'bg-white/50 hover:bg-white/80 w-2'"></button>
            </template>
        </div>
    </div>

    <!-- Kategori Pilihan -->
    <div class="mb-12">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-black text-slate-800 tracking-tight">Kategori Pilihan</h3>
        </div>
        <div class="flex overflow-x-auto space-x-4 pb-4 hide-scrollbar">
            <!-- All Categories -->
            <a href="{{ route('home') }}#produk-katalog" class="flex flex-col items-center min-w-[90px] group">
                <div class="w-16 h-16 rounded-2xl border border-slate-200 bg-white shadow-sm flex items-center justify-center mb-3 group-hover:border-emerald-500 group-hover:shadow-[0_8px_20px_-6px_rgba(16,185,129,0.3)] transition-all duration-300 transform group-hover:-translate-y-1">
                    <div class="p-2 rounded-xl bg-emerald-50 text-emerald-500 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    </div>
                </div>
                <span class="text-xs font-bold text-slate-700 text-center leading-tight group-hover:text-emerald-600 transition">Semua<br>Kategori</span>
            </a>
            
            @foreach($categories as $cat)
            @php
                // Ikon khas per kategori supaya tiap kategori bisa dibedakan
                // sekilas. Dicocokkan lewat kata kunci nama kategori; bila nama
                // kategori berubah, otomatis jatuh ke ikon default (kuas).
                $iconByKeyword = [
                    // Dicek lebih dulu agar "eksterior" tidak tertangkap "interior"
                    // (keduanya mengandung "erior").
                    'eksterior' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3M9 21v-6h6v6',
                    'interior' => 'M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z',
                    'kayu' => 'M4 6h16M4 12h16M4 18h16',
                    'pengecatan' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01',
                    'thinner' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z',
                ];

                $namaKategori = strtolower($cat->name);
                $iconPath = 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01';

                foreach ($iconByKeyword as $kata => $path) {
                    if (str_contains($namaKategori, $kata)) {
                        $iconPath = $path;
                        break;
                    }
                }

                // Nama kategori panjang (mis. "Alat Pengecatan (Kuas/Roller)")
                // dipersingkat untuk chip; teks lengkap tetap dibawa lewat title.
                $labelKategori = preg_replace('/\s*\(.*?\)\s*/', '', $cat->name);
            @endphp
            <a href="{{ route('home', ['category' => $cat->id]) }}#produk-katalog" title="{{ $cat->name }}" class="flex flex-col items-center min-w-[104px] max-w-[104px] group">
                <div class="w-16 h-16 rounded-2xl border {{ request('category') == $cat->id ? 'border-emerald-500 bg-emerald-50 shadow-[0_8px_20px_-6px_rgba(16,185,129,0.3)]' : 'border-slate-200 bg-white shadow-sm' }} flex items-center justify-center mb-3 group-hover:border-emerald-500 group-hover:shadow-[0_8px_20px_-6px_rgba(16,185,129,0.3)] transition-all duration-300 transform group-hover:-translate-y-1">
                    <div class="p-2 rounded-xl {{ request('category') == $cat->id ? 'bg-emerald-100/50 text-emerald-600 scale-110' : 'bg-slate-50 text-slate-500 group-hover:bg-emerald-50 group-hover:text-emerald-500 group-hover:scale-110' }} transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}"></path></svg>
                    </div>
                </div>
                <span class="text-xs font-bold text-slate-700 text-center leading-snug {{ request('category') == $cat->id ? 'text-emerald-600' : 'group-hover:text-emerald-600' }} transition">{{ $labelKategori }}</span>
            </a>
            @endforeach
        </div>
    </div>

    <!-- Flash Sale -->
    <div class="mb-12 bg-white p-6 md:p-8 rounded-3xl shadow-sm border border-slate-100 overflow-hidden relative">
        <!-- Decoration -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-red-50 rounded-full blur-3xl opacity-50 -mr-20 -mt-20 pointer-events-none"></div>
        
        <div class="flex flex-col sm:flex-row sm:items-center mb-6 relative z-10">
            <div class="flex items-center">
                <div class="bg-red-100 p-2 rounded-xl mr-3 text-red-500">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path></svg>
                </div>
                <h2 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight mr-4">Kejar Diskon</h2>
            </div>
            <div class="flex space-x-1.5 items-center mt-3 sm:mt-0 bg-red-50 text-red-600 px-4 py-2 rounded-full font-bold text-sm border border-red-100">
                <span>Berakhir dalam</span>
                <span class="bg-red-500 text-white px-2 py-0.5 rounded-md ml-2 shadow-inner">02</span><span class="font-bold text-red-500">:</span>
                <span class="bg-red-500 text-white px-2 py-0.5 rounded-md shadow-inner">45</span><span class="font-bold text-red-500">:</span>
                <span class="bg-red-500 text-white px-2 py-0.5 rounded-md shadow-inner">12</span>
            </div>
            <a href="#" class="mt-4 sm:mt-0 sm:ml-auto text-sm font-bold text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 px-4 py-2 rounded-full transition-colors inline-flex items-center">
                Lihat Semua
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
        
        <div class="flex overflow-x-auto space-x-4 pb-4 hide-scrollbar relative z-10">
            @foreach($products->take(5) as $fsProduct)
                <x-product-card :product="$fsProduct" size="sm" class="min-w-[200px] w-[200px]" />
            @endforeach
        </div>
    </div>

    <!-- ML Recommendations Section -->
    @if(isset($recommendedProducts) && $recommendedProducts->isNotEmpty())
    <div class="mb-12">
        <div class="flex items-center mb-6">
            <div class="bg-blue-100 p-2 rounded-xl mr-3 text-blue-500">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            </div>
            <h2 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">Rekomendasi Untuk Anda</h2>
            <span class="ml-4 bg-emerald-100 text-emerald-700 text-xs font-bold px-2 py-1 rounded-md border border-emerald-200">Powered by AI</span>
        </div>
        <div class="flex overflow-x-auto space-x-4 pb-4 hide-scrollbar">
            @foreach($recommendedProducts as $product)
                <x-product-card :product="$product" size="sm" class="min-w-[200px] w-[200px]" />
            @endforeach
        </div>
    </div>
    @endif

    <div id="produk-katalog" class="flex items-center mb-4 pt-4">
        <h3 class="text-xl md:text-2xl font-extrabold text-slate-800 relative inline-block">
                @if(request('search'))
                    Hasil pencarian untuk "{{ request('search') }}"
                @elseif(request('category'))
                    Kategori: {{ $categories->where('id', request('category'))->first()->name ?? '' }}
                @else
                    Semua Produk
                @endif
                <div class="absolute -bottom-1 left-0 w-1/2 h-1 bg-emerald-500 rounded-full"></div>
            </h3>
        </div>

    @if($products->isEmpty())
        <x-empty-state
            title="Oops, barangnya tidak ditemukan"
            description="Coba kata kunci lain atau cek kategori lainnya."
            icon="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
            :action-href="route('home')"
            action-label="Lihat Semua Produk"
            class="my-8"
        />
    @else
        <!-- Grid Produk -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6 mb-10">
            @foreach($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>

        <div class="mt-8 flex justify-center">
            {{ $products->links() }}
        </div>
    @endif
</x-app-layout>
    