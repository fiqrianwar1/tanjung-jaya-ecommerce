<x-app-layout>
    <x-slot name="header">Retur Barang</x-slot>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Daftar Retur Barang</h2>
            <p class="text-sm text-slate-500">Kelola pengajuan pengembalian barang dari pelanggan.</p>
        </div>
    </div>

    <div x-data="{ retModal: false, retId: null, retAction: 'Disetujui', retNotes: '' }" class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold">Tgl Pengajuan</th>
                        <th class="px-6 py-4 font-semibold">Invoice Terkait</th>
                        <th class="px-6 py-4 font-semibold">Alasan Retur</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($returns as $return)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-slate-800 text-sm">
                                {{ $return->created_at->format('d M Y') }}
                            </div>
                            <div class="text-xs text-slate-500">
                                {{ $return->created_at->format('H:i') }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-emerald-600 text-sm">
                                INV-{{ str_pad($return->order_id, 6, '0', STR_PAD_LEFT) }}
                            </div>
                            <div class="text-xs text-slate-500">{{ $return->order->user->name ?? 'User Terhapus' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-slate-700 truncate max-w-[220px]" title="{{ $return->reason }}">
                                {{ $return->reason }}
                            </div>
                            @if($return->notes)
                                <div class="text-xs text-slate-400 truncate max-w-[220px]" title="{{ $return->notes }}">{{ $return->notes }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($return->status === 'Menunggu Verifikasi')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 shadow-sm">Verifikasi</span>
                            @elseif($return->status === 'Disetujui')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-sm">Disetujui</span>
                            @elseif($return->status === 'Ditolak')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200 shadow-sm">Ditolak</span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200 shadow-sm">{{ $return->status }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($return->status === 'Menunggu Verifikasi')
                                <form action="{{ route('admin.returns.update', $return->id) }}" method="POST" class="inline-flex items-center gap-1">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="Disetujui">
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-500 hover:text-white transition-colors border border-emerald-100 text-xs font-bold" title="Setujui retur">
                                        Setujui
                                    </button>
                                </form>
                                <form action="{{ route('admin.returns.update', $return->id) }}" method="POST" class="inline-flex items-center gap-1 ml-1">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="Ditolak">
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-500 hover:text-white transition-colors border border-rose-100 text-xs font-bold" title="Tolak retur">
                                        Tolak
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-slate-400 italic">
                                    @if($return->proof_image || $return->notes)
                                        Sudah diproses
                                    @else
                                        -
                                    @endif
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-500">
                            Belum ada pengajuan retur barang.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-200 bg-slate-50">
            {{ $returns->links() }}
        </div>

    </div>
</x-app-layout>
