<x-app-layout>
    @section('title', 'Laporan Keuangan')

    <x-slot name="header">Laporan Keuangan</x-slot>

    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-end gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Laporan Penjualan</h2>
            <p class="text-sm text-slate-500">
                Periode {{ $start->format('d M Y') }} — {{ $end->format('d M Y') }}
            </p>
        </div>

        <div class="flex flex-wrap gap-2 items-end">
            <form action="{{ route('manager.reports') }}" method="GET" class="flex flex-wrap items-end gap-2">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Dari</label>
                    <input type="date" name="start" value="{{ $start->format('Y-m-d') }}" class="rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Sampai</label>
                    <input type="date" name="end" value="{{ $end->format('Y-m-d') }}" class="rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm">
                </div>
                <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2 rounded-lg text-sm font-bold transition shadow-sm">Terapkan</button>
            </form>

            <a href="{{ route('manager.reports.export', ['start' => $start->format('Y-m-d'), 'end' => $end->format('Y-m-d')]) }}"
               class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-lg text-sm font-bold transition shadow-md shadow-emerald-500/25 flex items-center h-[38px]">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Ekspor CSV
            </a>
        </div>
    </div>

    <!-- Ringkasan -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Pendapatan</span>
            <div class="text-2xl font-black text-emerald-600 mt-2">Rp {{ number_format($summary->revenue ?? 0, 0, ',', '.') }}</div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Jumlah Pesanan</span>
            <div class="text-2xl font-black text-slate-800 mt-2">{{ number_format($summary->orders ?? 0, 0, ',', '.') }}</div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Rata-rata / Pesanan</span>
            <div class="text-2xl font-black text-slate-800 mt-2">Rp {{ number_format($summary->avg_order ?? 0, 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Rincian Harian -->
        <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-bold text-slate-800">Rincian Harian</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs uppercase tracking-wider">
                            <th class="px-6 py-4 font-semibold">Tanggal</th>
                            <th class="px-6 py-4 font-semibold text-center">Pesanan</th>
                            <th class="px-6 py-4 font-semibold text-right">Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($daily as $row)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-6 py-4 font-semibold text-slate-800">{{ \Carbon\Carbon::parse($row->date)->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-center text-slate-600">{{ $row->orders }}</td>
                            <td class="px-6 py-4 text-right font-bold text-emerald-600">Rp {{ number_format($row->revenue, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-slate-500">Tidak ada transaksi pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($daily->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50">
                {{ $daily->links() }}
            </div>
            @endif
        </div>

        <!-- Top Produk Bulanan -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-bold text-slate-800 mb-6">Top Produk (Bulanan)</h3>
            <div class="space-y-5">
                @forelse($topProducts as $index => $item)
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-sm font-semibold text-slate-700 truncate">{{ $index + 1 }}. {{ $item->product->name ?? 'Produk Terhapus' }}</span>
                        <span class="text-xs font-bold text-slate-500 shrink-0 ml-2">{{ $item->total_qty_sold }} unit</span>
                    </div>
                    <div class="text-xs font-bold text-emerald-600">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</div>
                </div>
                @empty
                <div class="py-10 text-center text-slate-500 text-sm">
                    Belum ada data penjualan bulanan yang teragregasi.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
