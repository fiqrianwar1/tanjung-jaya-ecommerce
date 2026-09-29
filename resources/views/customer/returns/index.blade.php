<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            {{ __('Retur Saya') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 sm:p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <div>
                    <h3 class="text-xl font-bold text-slate-800">Riwayat Retur Barang</h3>
                    <p class="text-sm text-slate-500 mt-1">Pantau status pengajuan pengembalian barang Anda.</p>
                </div>
            </div>

            <div class="p-6 sm:p-8">
                @if($returns->count() > 0)
                    <div class="space-y-6">
                        @foreach($returns as $return)
                            <div class="bg-white border border-slate-200 rounded-2xl p-6 flex flex-col md:flex-row justify-between items-start gap-6 shadow-sm hover:shadow-md transition duration-200">
                                <div class="flex gap-4 w-full md:w-auto flex-grow">
                                    <div class="w-20 h-20 bg-rose-50 rounded-xl overflow-hidden flex-shrink-0 border border-rose-100 flex items-center justify-center">
                                        @if($return->proof_image)
                                            <img src="{{ Storage::url($return->proof_image) }}" alt="Bukti Retur" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-8 h-8 text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"></path></svg>
                                        @endif
                                    </div>

                                    <div>
                                        <a href="{{ route('customer.orders.show', $return->order_id) }}" class="text-xs font-bold text-emerald-600 hover:underline mb-1 inline-block">
                                            #ORD-{{ str_pad($return->order_id, 6, '0', STR_PAD_LEFT) }}
                                        </a>
                                        <h4 class="text-base font-bold text-slate-800 leading-tight mb-1">
                                            {{ $return->order->user->name ?? 'Pesanan Anda' }}
                                        </h4>
                                        <p class="text-sm font-bold text-rose-500 mb-2">Alasan: {{ $return->reason }}</p>
                                        <p class="text-sm text-slate-600">{{ $return->notes ?? 'Tidak ada catatan tambahan.' }}</p>
                                    </div>
                                </div>

                                <div class="flex flex-col items-end w-full md:w-auto">
                                    <span class="text-xs text-slate-400 font-medium mb-3">
                                        Diajukan: {{ $return->created_at->format('d M Y') }}
                                    </span>

                                    @if($return->status === 'Menunggu Verifikasi')
                                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider bg-amber-100 text-amber-700 border border-amber-200">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Menunggu Verifikasi
                                        </span>
                                    @elseif($return->status === 'Disetujui')
                                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider bg-emerald-100 text-emerald-700 border border-emerald-200">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Retur Disetujui
                                        </span>
                                    @elseif($return->status === 'Ditolak')
                                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider bg-rose-100 text-rose-700 border border-rose-200">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            Retur Ditolak
                                        </span>
                                    @elseif($return->status === 'Selesai')
                                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider bg-blue-100 text-blue-700 border border-blue-200">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Retur Selesai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200">
                                            {{ $return->status }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-8">
                        {{ $returns->links() }}
                    </div>
                @else
                    <div class="text-center py-16 bg-slate-50/50 rounded-3xl border border-dashed border-slate-200">
                        <div class="w-20 h-20 bg-rose-100 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-slate-700 mb-2">Belum Ada Retur</h4>
                        <p class="text-slate-500 mb-6 max-w-md mx-auto">Anda belum pernah mengajukan pengembalian barang. Retur dapat diajukan jika barang rusak atau kedaluwarsa.</p>
                        <a href="{{ route('customer.orders.index') }}" class="inline-block bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-6 rounded-xl transition shadow-sm">Lihat Pesanan Saya</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
