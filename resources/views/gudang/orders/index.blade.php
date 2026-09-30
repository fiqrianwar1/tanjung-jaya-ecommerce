<x-app-layout>
    @section('title', 'Pesanan (Pengiriman)')

    <x-slot name="header">Pesanan (Pengiriman)</x-slot>

    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Antrian Pengiriman</h2>
            <p class="text-sm text-slate-500">Siapkan barang pesanan lalu input nomor resi pengiriman.</p>
        </div>
        <form action="{{ route('gudang.orders.index') }}" method="GET" class="flex items-center gap-2">
            <select name="status" onchange="this.form.submit()" class="rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm">
                <option value="">Semua Status</option>
                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Perlu Disiapkan</option>
                <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Sedang Dikirim</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Diretur</option>
            </select>
        </form>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold">Invoice / Tanggal</th>
                        <th class="px-6 py-4 font-semibold">Pelanggan</th>
                        <th class="px-6 py-4 font-semibold text-center">Jumlah Item</th>
                        <th class="px-6 py-4 font-semibold">Resi</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $order)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold text-emerald-600 text-sm mb-1">INV-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
                            <div class="text-xs text-slate-500">{{ $order->created_at->format('d M Y, H:i') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-slate-800 text-sm">{{ $order->user->name ?? 'User Terhapus' }}</div>
                            <div class="text-xs text-slate-500">{{ $order->user->email ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-slate-700">{{ $order->order_items_count }}</td>
                        <td class="px-6 py-4">
                            @if($order->resi)
                                <span class="text-xs font-mono bg-slate-100 px-2 py-1 rounded border border-slate-200">{{ $order->resi }}</span>
                                <div class="text-xs text-slate-500 mt-1">{{ $order->courier }}</div>
                            @else
                                <span class="text-xs text-slate-400 italic">Belum ada</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($order->status === 'processing')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">Perlu Disiapkan</span>
                            @elseif($order->status === 'shipped')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">Sedang Dikirim</span>
                            @elseif($order->status === 'completed')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Selesai</span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">{{ $order->status_label }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('gudang.orders.show', $order->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-500 hover:text-white transition-colors border border-blue-100" title="Detail & Kirim">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-slate-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <p>Tidak ada pesanan pada antrian pengiriman.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-200 bg-slate-50">
            {{ $orders->links() }}
        </div>
    </div>
</x-app-layout>