<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-emerald-100 rounded-xl">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Halo, {{ auth()->user()->name }}!</h1>
        </div>
    </x-slot>

    @php
        $role = auth()->user()->role;

        $heroActions = [
            'Admin' => ['route' => 'admin.products.index', 'label' => 'Kelola Produk & Master Data',
                'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
            'Manager' => ['route' => 'manager.dashboard', 'label' => 'Buka Dashboard Eksekutif',
                'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
            'Gudang' => ['route' => 'gudang.stocks.index', 'label' => 'Kelola Stok Gudang',
                'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
        ];

        $stats = [
            ['label' => 'Total Pesanan', 'value' => $totalOrders, 'tone' => 'blue',
                'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'],
            ['label' => 'Total Pendapatan', 'value' => 'Rp '.number_format($revenue, 0, ',', '.'), 'tone' => 'emerald',
                'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1'],
            ['label' => 'Produk Aktif', 'value' => $totalProducts, 'tone' => 'amber',
                'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
            ['label' => 'Stok Menipis', 'value' => $lowStockCount, 'tone' => 'rose',
                'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
        ];
    @endphp

    <!-- Welcome Banner -->
    <div class="mb-8 relative overflow-hidden bg-gradient-to-br from-slate-900 to-slate-800 rounded-3xl p-8 sm:p-10 shadow-2xl">
        <div class="relative z-10 max-w-3xl">
            <h2 class="text-3xl sm:text-4xl font-black text-white mb-4 leading-tight">Selamat Datang di Pusat Kendali<br><span class="text-emerald-400">Tanjung Jaya Corporation</span></h2>
            <p class="text-slate-300 text-lg sm:text-xl font-medium mb-8 max-w-2xl">Anda masuk dengan peran <span class="bg-emerald-500/20 text-emerald-300 px-3 py-1 rounded-full font-bold uppercase tracking-wider text-sm ml-1 border border-emerald-500/30">{{ auth()->user()->role }}</span>. Kelola operasional dengan cepat dan efisien.</p>
            <div class="flex flex-wrap gap-4 relative z-10 mt-6">
                @isset($heroActions[$role])
                <a href="{{ route($heroActions[$role]['route']) }}" class="bg-emerald-500 hover:bg-emerald-400 text-slate-900 font-bold px-6 py-3 rounded-xl transition-all transform hover:-translate-y-1 hover:shadow-lg hover:shadow-emerald-500/25 flex items-center focus:outline-none focus:ring-4 focus:ring-emerald-400/40">
                    {{ $heroActions[$role]['label'] }}
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
                @endisset

                <a href="{{ route('home') }}" class="bg-slate-800 hover:bg-slate-700 text-white font-bold px-6 py-3 rounded-xl transition-all transform hover:-translate-y-1 hover:shadow-lg flex items-center border border-slate-700 focus:outline-none focus:ring-4 focus:ring-slate-500/40">
                    Buka Katalog Toko
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </a>
            </div>
        </div>
        <!-- Decorative Shapes -->
        <div class="absolute right-0 bottom-0 w-64 h-64 bg-emerald-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 transform translate-x-1/2 translate-y-1/2"></div>
        <div class="absolute right-40 top-0 w-48 h-48 bg-teal-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 transform -translate-y-1/2"></div>
    </div>

    <!-- Quick Stats Overview -->
    <div class="mb-6 flex items-center justify-between">
        <h3 class="text-lg font-bold text-slate-800">Ringkasan Sistem</h3>
        <span class="text-sm font-medium text-slate-500">Data terkini {{ now()->translatedFormat('d F Y') }}</span>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @foreach($stats as $stat)
            <x-stat-card
                :label="$stat['label']"
                :value="$stat['value']"
                :icon="$stat['icon']"
                :tone="$stat['tone']"
            />
        @endforeach
    </div>

    <!-- Pesanan Terbaru -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mb-8">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h3 class="font-bold text-slate-800">Pesanan Terbaru</h3>
            @if($role === 'Manager')
            <a href="{{ route('manager.reports') }}" class="text-sm font-bold text-emerald-600 hover:text-emerald-700 transition">Lihat Laporan</a>
            @elseif($role === 'Admin')
            <a href="{{ route('admin.orders.index') }}" class="text-sm font-bold text-emerald-600 hover:text-emerald-700 transition">Lihat Semua Pesanan</a>
            @elseif($role === 'Gudang')
            <a href="{{ route('gudang.orders.index') }}" class="text-sm font-bold text-emerald-600 hover:text-emerald-700 transition">Lihat Semua Pesanan</a>
            @endif
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($recentOrders as $order)
            <div class="p-5 flex items-center justify-between gap-4 hover:bg-slate-50/70 transition">
                <div class="flex items-center gap-4 min-w-0">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <div class="min-w-0">
                        <div class="font-bold text-slate-800 text-sm truncate">#ORD-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }} · {{ $order->user->name ?? 'User Terhapus' }}</div>
                        <div class="text-xs text-slate-500">{{ $order->created_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
                <div class="text-right shrink-0">
                    <div class="font-black text-slate-800">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                    <div class="text-xs font-bold text-emerald-600 uppercase tracking-wide">{{ $order->status_label }}</div>
                </div>
            </div>
            @empty
            <div class="p-10 text-center text-slate-500">Belum ada pesanan terbaru.</div>
            @endforelse
        </div>
    </div>
</x-app-layout>
