<x-app-layout>
    <x-slot name="header">Dashboard Eksekutif</x-slot>

    @php
        $maxRevenue = $salesTrend->max('revenue') ?: 1;
    @endphp

    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-800">Ringkasan Performa Bisnis</h2>
        <p class="text-sm text-slate-500">Data diperbarui otomatis berdasarkan transaksi terbaru.</p>
    </div>

    <!-- Kartu KPI -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-3xl p-6 text-white shadow-lg shadow-emerald-500/20">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-50/90">Total Pendapatan</span>
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path></svg>
                </div>
            </div>
            <div class="text-2xl font-black">Rp {{ number_format($revenue, 0, ',', '.') }}</div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Pesanan</span>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
            </div>
            <div class="text-2xl font-black text-slate-800">{{ number_format($ordersCount, 0, ',', '.') }}</div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Rata-rata / Pesanan</span>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
            </div>
            <div class="text-2xl font-black text-slate-800">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Retur &amp; Stok Menipis</span>
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
            <div class="text-2xl font-black text-slate-800">{{ $pendingReturns }} <span class="text-base font-bold text-slate-400">/</span> {{ $lowStockCount }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Grafik Tren Penjualan -->
        <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="font-bold text-slate-800">Tren Penjualan 14 Hari Terakhir</h3>
                    <p class="text-sm text-slate-500 mt-0.5">Pendapatan harian dari pesanan yang valid.</p>
                </div>
                <span class="text-xs font-bold bg-emerald-50 text-emerald-600 px-3 py-1.5 rounded-full border border-emerald-100">Live</span>
            </div>

            @if($salesTrend->isEmpty())
                <div class="py-16 text-center text-slate-500">Belum ada data penjualan pada periode ini.</div>
            @else
                <div class="flex items-end gap-2 sm:gap-3 h-56">
                    @foreach($salesTrend as $day)
                    <div class="flex-1 flex flex-col items-center justify-end h-full group" title="{{ \Carbon\Carbon::parse($day->date)->format('d M') }}: Rp {{ number_format($day->revenue, 0, ',', '.') }}">
                        <span class="text-[10px] font-bold text-slate-400 mb-1 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">{{ number_format($day->orders) }} o</span>
                        <div class="w-full bg-gradient-to-t from-emerald-500 to-teal-400 rounded-t-lg transition-all duration-300 group-hover:from-emerald-600 group-hover:to-teal-500" style="height: {{ max(6, round(($day->revenue / $maxRevenue) * 100)) }}%"></div>
                        <span class="text-[10px] font-bold text-slate-400 mt-2">{{ \Carbon\Carbon::parse($day->date)->format('d/m') }}</span>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Produk Terlaris -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
            <h3 class="font-bold text-slate-800 mb-6">Produk Terlaris</h3>
            <div class="space-y-5">
                @forelse($topProducts as $index => $product)
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg {{ $index === 0 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600' }} flex items-center justify-center font-black text-sm shrink-0">
                        {{ $index + 1 }}
                    </div>
                    <div class="min-w-0 flex-grow">
                        <div class="font-semibold text-slate-800 text-sm truncate">{{ $product->name }}</div>
                        <div class="text-xs text-slate-500">{{ $product->category->name ?? '-' }}</div>
                    </div>
                    <div class="text-sm font-black text-emerald-600 shrink-0">{{ $product->sold_qty ?? 0 }}</div>
                </div>
                @empty
                <div class="py-10 text-center text-slate-500 text-sm">Belum ada penjualan tercatat.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Pesanan Terbaru -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h3 class="font-bold text-slate-800">Pesanan Terbaru</h3>
            <a href="{{ route('manager.reports') }}" class="text-sm font-bold text-emerald-600 hover:text-emerald-700 transition">Lihat Laporan Lengkap</a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($recentOrders as $order)
            <div class="p-5 flex items-center justify-between gap-4 hover:bg-slate-50/70 transition">
                <div class="min-w-0">
                    <div class="font-bold text-slate-800 text-sm truncate">#ORD-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }} · {{ $order->user->name ?? 'User Terhapus' }}</div>
                    <div class="text-xs text-slate-500">{{ $order->created_at->format('d M Y, H:i') }}</div>
                </div>
                <div class="text-right shrink-0">
                    <div class="font-black text-slate-800">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                    <div class="text-xs font-bold text-emerald-600 uppercase tracking-wide">{{ $order->status_label }}</div>
                </div>
            </div>
            @empty
            <div class="p-10 text-center text-slate-500">Belum ada pesanan.</div>
            @endforelse
        </div>
    </div>
</x-app-layout>