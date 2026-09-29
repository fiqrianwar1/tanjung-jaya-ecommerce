<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('gudang.orders.index') }}" class="text-emerald-600 hover:underline mr-2">&larr; Kembali ke Antrian</a>
        Pengiriman Pesanan
    </x-slot>

    <div class="max-w-5xl mx-auto pb-12">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">INV-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h2>
                <p class="text-slate-500 font-medium">Dibuat {{ $order->created_at->format('d M Y, H:i') }} · {{ $order->user->name ?? 'User Terhapus' }}</p>
            </div>
            <span class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider bg-amber-100 text-amber-700 border border-amber-200 self-start">{{ $order->status_label }}</span>
        </div>

        <!-- Daftar Barang untuk Disiapkan -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 mb-8">
            <h3 class="text-lg font-bold text-slate-800 mb-6">Daftar Barang yang Harus Disiapkan</h3>

            <div class="space-y-4">
                @foreach($order->orderItems as $item)
                <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="w-16 h-16 bg-white rounded-xl overflow-hidden flex-shrink-0 border border-slate-200 flex items-center justify-center">
                        @if($item->product && $item->product->image)
                            <img src="{{ Str::startsWith($item->product->image, 'http') ? $item->product->image : Storage::url($item->product->image) }}" class="w-full h-full object-cover">
                        @else
                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        @endif
                    </div>
                    <div class="flex-grow">
                        <div class="font-bold text-slate-800">{{ $item->product->name ?? 'Produk Terhapus' }}</div>
                        <div class="text-sm text-slate-500">Rp {{ number_format($item->price, 0, ',', '.') }} / unit</div>
                    </div>
                    <div class="text-center px-4">
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Jumlah</div>
                        <div class="text-2xl font-black text-emerald-600">{{ $item->qty }}</div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-8 pt-6 border-t border-slate-100 flex justify-between items-center">
                <span class="text-slate-500 font-bold uppercase tracking-wider">Total Pesanan</span>
                <span class="text-2xl font-black text-emerald-600">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Form Update Pengiriman -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
            <h3 class="text-lg font-bold text-slate-800 mb-1">Perbarui Status Pengiriman</h3>
            <p class="text-sm text-slate-500 mb-6">Isi kurir & nomor resi setelah barang diserahkan ke ekspedisi.</p>

            <form action="{{ route('gudang.orders.update', $order->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Status Pesanan</label>
                    <select name="status" required class="w-full border-slate-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 text-sm shadow-sm">
                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Perlu Disiapkan</option>
                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Sedang Dikirim</option>
                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Selesai (Diterima)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Kurir / Ekspedisi</label>
                    <input type="text" name="courier" value="{{ old('courier', $order->courier) }}" placeholder="Contoh: JNE, J&T, SiCepat" class="w-full border-slate-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 text-sm shadow-sm">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nomor Resi</label>
                    <input type="text" name="resi" value="{{ old('resi', $order->resi) }}" placeholder="Contoh: JNE123456789" class="w-full border-slate-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 text-sm shadow-sm">
                </div>

                <div class="md:col-span-3 flex justify-end pt-4 border-t border-slate-100">
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-8 rounded-xl transition shadow-md shadow-emerald-500/25">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>