<x-app-layout>
    @section('title', 'Manajemen Pesanan')

    <x-slot name="header">Manajemen Pesanan</x-slot>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Daftar Pesanan</h2>
            <p class="text-sm text-slate-500">Pantau semua transaksi dan pesanan yang masuk dari pelanggan.</p>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 mb-6">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-slate-700 mb-1">Cari Invoice / Pelanggan</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="No. invoice, nama, atau email..." class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm">
            </div>
            <div class="w-52">
                <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                <select name="status" class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm">
                    <option value="">Semua Status</option>
                    @foreach(['pending' => 'Menunggu Pembayaran', 'processing' => 'Diproses', 'shipped' => 'Dikirim', 'completed' => 'Selesai', 'returned' => 'Diretur', 'cancelled' => 'Dibatalkan'] as $value => $label)
                        <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2 rounded-lg text-sm font-medium transition shadow-sm">Filter</button>
                @if(request()->anyFilled(['search', 'status']))
                    <a href="{{ route('admin.orders.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2 rounded-lg text-sm font-medium transition border border-slate-200">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <div x-data="{ orderModalOpen: false, selectedOrder: null }" class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold">Invoice / Tanggal</th>
                        <th class="px-6 py-4 font-semibold">Pelanggan</th>
                        <th class="px-6 py-4 font-semibold">Total Belanja</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $order)
                    <tr class="hover:bg-slate-50/70 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-sm relative z-0 hover:z-10 bg-white">
                        <td class="px-6 py-4">
                            <div class="font-bold text-emerald-600 text-sm mb-1">
                                INV-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                            </div>
                            <div class="text-xs text-slate-500">
                                {{ $order->created_at->format('d M Y, H:i') }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-slate-800 text-sm">{{ $order->user->name ?? 'User Terhapus' }}</div>
                            <div class="text-xs text-slate-500">{{ $order->user->email ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-700">
                            Rp {{ number_format($order->total, 0, ',', '.') }}
                            @if($order->shipping_cost > 0)
                                <div class="text-xs font-normal text-slate-400">+ ongkir Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusTone = [
                                    'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'processing' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'shipped' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                    'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'returned' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    'cancelled' => 'bg-slate-100 text-slate-600 border-slate-200',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $statusTone[$order->status] ?? 'bg-slate-100 text-slate-600 border-slate-200' }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-500 hover:text-white transition-colors border border-blue-100" title="Detail Pesanan">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-500">
                            Belum ada pesanan yang masuk.
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
