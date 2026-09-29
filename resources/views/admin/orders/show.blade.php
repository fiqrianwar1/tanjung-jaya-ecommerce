<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('admin.orders.index') }}" class="text-emerald-600 hover:underline mr-2">&larr; Kembali ke Pesanan</a>
        Detail Pesanan
    </x-slot>

    <div class="max-w-5xl mx-auto pb-12">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">INV-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h2>
                <p class="text-slate-500 font-medium">{{ $order->created_at->format('d M Y, H:i') }} · {{ $order->user->name ?? 'User Terhapus' }} ({{ $order->user->email ?? '-' }})</p>
            </div>
            @php
                $statusTone = [
                    'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                    'processing' => 'bg-blue-100 text-blue-700 border-blue-200',
                    'shipped' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                    'completed' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                    'returned' => 'bg-rose-100 text-rose-700 border-rose-200',
                    'cancelled' => 'bg-slate-100 text-slate-600 border-slate-200',
                ];
            @endphp
            <span class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider border {{ $statusTone[$order->status] ?? 'bg-slate-100 text-slate-600 border-slate-200' }} self-start">
                {{ $order->status_label }}
            </span>
        </div>

        @if($order->returnRequest)
        <div class="mb-8 bg-amber-50 border border-amber-200 rounded-2xl p-5">
            <div class="font-bold text-amber-900">Pesanan ini memiliki pengajuan retur</div>
            <p class="text-sm text-amber-800 mt-1">Status retur: <span class="font-semibold">{{ $order->returnRequest->status }}</span> · Alasan: {{ $order->returnRequest->reason }}</p>
            <a href="{{ route('admin.returns.index') }}" class="text-sm font-bold text-amber-900 underline mt-2 inline-block">Lihat daftar retur &rarr;</a>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Rincian Item -->
            <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-bold text-slate-800">Rincian Barang</h3>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach($order->orderItems as $item)
                    <div class="p-6 flex items-center gap-4">
                        <div class="w-16 h-16 bg-slate-50 rounded-xl overflow-hidden flex-shrink-0 border border-slate-100 flex items-center justify-center">
                            @if($item->product && $item->product->image)
                                <img src="{{ Str::startsWith($item->product->image, 'http') ? $item->product->image : Storage::url($item->product->image) }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            @endif
                        </div>
                        <div class="flex-grow min-w-0">
                            <div class="font-bold text-slate-800 truncate">{{ $item->product->name ?? 'Produk Terhapus' }}</div>
                            <div class="text-sm text-slate-500 mt-0.5">{{ $item->qty }} × Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                        </div>
                        <div class="font-black text-slate-800 whitespace-nowrap">Rp {{ number_format($item->qty * $item->price, 0, ',', '.') }}</div>
                    </div>
                    @endforeach
                </div>
                <div class="p-6 bg-slate-50 border-t border-slate-100 space-y-2">
                    <div class="flex justify-between text-sm text-slate-600">
                        <span>Subtotal Barang</span>
                        <span class="font-bold text-slate-800">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-slate-600">
                        <span>Ongkos Kirim</span>
                        <span class="font-bold text-slate-800">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-end pt-2 border-t border-slate-200">
                        <span class="font-bold text-slate-700 uppercase tracking-wider text-sm">Total</span>
                        <span class="text-2xl font-black text-emerald-600">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Panel Aksi -->
            <div class="space-y-6">
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
                    <h3 class="font-bold text-slate-800 mb-1">Perbarui Pesanan</h3>
                    <p class="text-sm text-slate-500 mb-5">Ubah status, kurir, dan nomor resi.</p>

                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Status</label>
                            <select name="status" required class="w-full border-slate-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 text-sm shadow-sm">
                                @foreach(['pending' => 'Menunggu Pembayaran', 'processing' => 'Diproses', 'shipped' => 'Dikirim', 'completed' => 'Selesai', 'returned' => 'Diretur', 'cancelled' => 'Dibatalkan'] as $value => $label)
                                    <option value="{{ $value }}" {{ $order->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Kurir</label>
                            <input type="text" name="courier" value="{{ old('courier', $order->courier) }}" placeholder="Contoh: JNE" class="w-full border-slate-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 text-sm shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nomor Resi</label>
                            <input type="text" name="resi" value="{{ old('resi', $order->resi) }}" placeholder="Contoh: JNE123456789" class="w-full border-slate-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 text-sm shadow-sm">
                        </div>

                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl transition shadow-md shadow-emerald-500/25">
                            Simpan Perubahan
                        </button>
                    </form>
                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
                    <h3 class="font-bold text-slate-800 mb-1">Info Pelanggan</h3>
                    <div class="flex items-center gap-3 mt-4">
                        <div class="w-11 h-11 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-black">
                            {{ strtoupper(substr($order->user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <div class="font-bold text-slate-800 truncate">{{ $order->user->name ?? 'User Terhapus' }}</div>
                            <div class="text-xs text-slate-500 truncate">{{ $order->user->email ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                @if(in_array($order->status, ['pending', 'processing']))
                <div class="bg-rose-50 rounded-3xl border border-rose-100 p-6">
                    <h3 class="font-bold text-rose-900 mb-1">Batalkan Pesanan</h3>
                    <p class="text-sm text-rose-700 mb-4">Stok produk akan dikembalikan bila pesanan sudah dipotong stoknya.</p>
                    <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Batalkan & hapus pesanan ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-3 rounded-xl transition shadow-md shadow-rose-500/25">
                            Batalkan Pesanan
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>