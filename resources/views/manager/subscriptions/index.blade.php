<x-app-layout>
    @section('title', 'Langganan Laporan')

    <x-slot name="header">Langganan Laporan</x-slot>

    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-800">Langganan Laporan Otomatis</h2>
        <p class="text-sm text-slate-500">Atur jenis dan frekuensi laporan yang ingin Anda terima secara berkala.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Tambah -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 h-fit">
            <h3 class="font-bold text-slate-800 mb-1">Tambah Langganan</h3>
            <p class="text-sm text-slate-500 mb-6">Pilih jenis laporan dan frekuensi pengirimannya.</p>

            <form action="{{ route('manager.subscriptions.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Jenis Laporan <span class="text-rose-500">*</span></label>
                    <select name="report_type" required class="w-full border-slate-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 text-sm shadow-sm">
                        <option value="Laporan Penjualan">Laporan Penjualan</option>
                        <option value="Laporan Stok">Laporan Stok</option>
                        <option value="Laporan Retur">Laporan Retur</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Frekuensi <span class="text-rose-500">*</span></label>
                    <select name="frequency" required class="w-full border-slate-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 text-sm shadow-sm">
                        <option value="Harian">Harian</option>
                        <option value="Mingguan" selected>Mingguan</option>
                        <option value="Bulanan">Bulanan</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl transition shadow-md shadow-emerald-500/25">
                    Berlangganan Laporan
                </button>
            </form>
        </div>

        <!-- Daftar Langganan -->
        <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <h3 class="font-bold text-slate-800">Langganan Aktif</h3>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ $subscriptions->count() }} Langganan</span>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($subscriptions as $subscription)
                <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/70 transition">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <div class="font-bold text-slate-800">{{ $subscription->report_type }}</div>
                            <div class="text-xs text-slate-500 mt-0.5">Dibuat {{ $subscription->created_at->format('d M Y') }}</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-600 border border-indigo-100">
                            {{ $subscription->frequency }}
                        </span>

                        <form action="{{ route('manager.subscriptions.destroy', $subscription->id) }}" method="POST" onsubmit="return confirm('Hentikan langganan laporan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-500 hover:text-white transition-colors border border-rose-100" title="Hentikan langganan">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="py-16 text-center px-6">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                        <svg class="w-9 h-9 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <h4 class="font-bold text-slate-700 mb-1">Belum Ada Langganan</h4>
                    <p class="text-sm text-slate-500">Tambahkan langganan laporan pada panel di samping.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>