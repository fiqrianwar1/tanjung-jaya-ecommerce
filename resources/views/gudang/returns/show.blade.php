<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('gudang.returns.index') }}" class="text-emerald-600 hover:underline mr-2">&larr; Kembali ke Daftar Retur</a>
        Pemeriksaan Barang Retur
    </x-slot>

    <div class="max-w-4xl mx-auto pb-12">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">INV-{{ str_pad($return->order_id, 6, '0', STR_PAD_LEFT) }}</h2>
                <p class="text-slate-500 font-medium">Diajukan {{ $return->created_at->format('d M Y, H:i') }} · {{ $return->order->user->name ?? 'User Terhapus' }}</p>
            </div>
            <span class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider bg-amber-100 text-amber-700 border border-amber-200 self-start">{{ $return->status }}</span>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 mb-8">
            <h3 class="text-lg font-bold text-slate-800 mb-6">Detail Pengajuan</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-rose-50 border border-rose-100 rounded-2xl p-5">
                    <div class="text-xs font-bold text-rose-500 uppercase tracking-wider mb-2">Alasan Retur</div>
                    <div class="font-medium text-rose-900">{{ $return->reason }}</div>
                </div>
                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5">
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Catatan Pelanggan</div>
                    <div class="text-slate-700">{{ $return->notes ?? 'Tidak ada catatan.' }}</div>
                </div>
            </div>

            @if($return->proof_image)
            <div class="mb-6">
                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Bukti Foto dari Pelanggan</div>
                <img src="{{ Storage::url($return->proof_image) }}" alt="Bukti Retur" class="max-w-sm rounded-2xl border border-slate-200 shadow-sm">
            </div>
            @endif

            <div class="border-t border-slate-100 pt-6">
                <h4 class="font-bold text-slate-800 mb-4">Barang yang Diklaim</h4>
                <div class="space-y-3">
                    @foreach($return->order->orderItems as $item)
                    <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <div class="w-12 h-12 bg-white rounded-xl overflow-hidden flex-shrink-0 border border-slate-200 flex items-center justify-center">
                            @if($item->product && $item->product->image)
                                <img src="{{ Str::startsWith($item->product->image, 'http') ? $item->product->image : Storage::url($item->product->image) }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            @endif
                        </div>
                        <div class="flex-grow font-medium text-slate-800">{{ $item->product->name ?? 'Produk Terhapus' }}</div>
                        <div class="font-black text-slate-700">{{ $item->qty }} unit</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Konfirmasi Kondisi Fisik -->
        @if($return->status === 'Disetujui' || $return->status === 'Menunggu Verifikasi')
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
            <h3 class="text-lg font-bold text-slate-800 mb-1">Konfirmasi Kondisi Fisik Barang</h3>
            <p class="text-sm text-slate-500 mb-6">Barang layak akan dikembalikan ke stok jual; barang rusak dicatat sebagai stok rusak.</p>

            <form action="{{ route('gudang.returns.update', $return->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                @csrf
                @method('PUT')

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Hasil Pemeriksaan</label>
                    <select name="physical_status" required class="w-full border-slate-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 text-sm shadow-sm">
                        <option value="layak">Layak Jual — kembalikan ke stok tersedia</option>
                        <option value="rusak">Rusak — catat sebagai stok rusak</option>
                    </select>
                </div>

                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-xl transition shadow-md shadow-emerald-500/25 h-11">
                    Konfirmasi Penerimaan
                </button>
            </form>
        </div>
        @else
        <div class="bg-slate-50 rounded-3xl border border-slate-200 p-8 text-center text-slate-600">
            Barang retur ini sudah diproses. Statusnya saat ini: <span class="font-bold">{{ $return->status }}</span>.
        </div>
        @endif
    </div>
</x-app-layout>