<x-app-layout>
    <x-slot name="header">Stok & Inventaris</x-slot>

    @php
        $lowStock = $products->where('stock', '<=', 10)->count();
        $totalUnits = $products->sum('stock');
    @endphp

    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-800">Monitor Stok Gudang</h2>
        <p class="text-sm text-slate-500">Catat barang masuk, barang keluar, dan lakukan stok opname.</p>
    </div>

    <div x-data="{ stockModal: false, productField: '', typeField: 'in', qtyField: 1 }">
        <!-- Kartu Ringkasan -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Unit Stok</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                </div>
                <div class="text-3xl font-black text-slate-800">{{ number_format($totalUnits, 0, ',', '.') }}</div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Produk Stok Menipis</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                </div>
                <div class="text-3xl font-black text-slate-800">{{ $lowStock }}</div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex items-center justify-center">
                <button @click="stockModal = true" class="w-full bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-white font-bold py-4 rounded-2xl transition-all shadow-lg shadow-emerald-500/25 flex items-center justify-center transform hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-emerald-400/40">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Catat Mutasi Stok
                </button>
            </div>
        </div>

        <!-- Filter -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 mb-6">
            <form action="{{ route('gudang.stocks.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Cari Produk</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama produk..." class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm">
                </div>
                <div class="w-48">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Kondisi Stok</label>
                    <select name="status" class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm">
                        <option value="">Semua</option>
                        <option value="low" {{ request('status') === 'low' ? 'selected' : '' }}>Menipis (&le; 10)</option>
                        <option value="safe" {{ request('status') === 'safe' ? 'selected' : '' }}>Aman (&gt; 10)</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2 rounded-lg text-sm font-medium transition shadow-sm">Filter</button>
                    @if(request()->anyFilled(['search', 'status']))
                        <a href="{{ route('gudang.stocks.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2 rounded-lg text-sm font-medium transition border border-slate-200">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Daftar Produk -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mb-8">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-bold text-slate-800">Kondisi Stok Produk</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs uppercase tracking-wider">
                            <th class="px-6 py-4 font-semibold">Produk</th>
                            <th class="px-6 py-4 font-semibold">Kategori</th>
                            <th class="px-6 py-4 font-semibold text-center">Stok Layak</th>
                            <th class="px-6 py-4 font-semibold text-center">Stok Rusak</th>
                            <th class="px-6 py-4 font-semibold text-center">Kondisi</th>
                            <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($products as $product)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 overflow-hidden">
                                        @if($product->image)
                                            <img src="{{ Str::startsWith($product->image, 'http') ? $product->image : Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-semibold text-slate-800 text-sm truncate max-w-xs" title="{{ $product->name }}">{{ $product->name }}</div>
                                        <div class="text-xs text-slate-500">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-600 border border-indigo-100">{{ $product->category->name ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-center font-bold {{ $product->stock <= 10 ? 'text-amber-600' : 'text-emerald-600' }}">{{ $product->stock }}</td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('gudang.stocks.update', $product->id) }}" method="POST" class="inline-flex items-center gap-1">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="bad_stock" min="0" value="{{ $product->bad_stock }}" class="w-16 text-center border-slate-300 rounded-lg text-sm shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                                    <button type="submit" class="p-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-emerald-500 hover:text-white transition" title="Simpan stok rusak">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($product->stock <= 0)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">Habis</span>
                                @elseif($product->stock <= 10)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">Menipis</span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Aman</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button @click="stockModal = true; productField = '{{ $product->id }}'" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white transition-colors border border-emerald-100" title="Catat mutasi">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500">Tidak ada produk yang cocok dengan filter.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-200 bg-slate-50">
                {{ $products->links() }}
            </div>
        </div>

        <!-- Riwayat Mutasi Stok -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mb-8">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <h3 class="font-bold text-slate-800">Riwayat Mutasi Stok</h3>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Terbaru</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs uppercase tracking-wider">
                            <th class="px-6 py-4 font-semibold">Waktu</th>
                            <th class="px-6 py-4 font-semibold">Produk</th>
                            <th class="px-6 py-4 font-semibold text-center">Tipe</th>
                            <th class="px-6 py-4 font-semibold text-center">Jumlah</th>
                            <th class="px-6 py-4 font-semibold">Petugas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-6 py-4 text-slate-500 font-mono text-xs">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4 font-medium text-slate-800">{{ $log->product->name ?? 'Produk Terhapus' }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($log->type === 'in')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Masuk</span>
                                @elseif($log->type === 'out')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">Keluar</span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">Penyesuaian</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-slate-800">{{ $log->qty }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $log->user->name ?? 'Sistem' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-500">Belum ada riwayat mutasi stok.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-200 bg-slate-50">
                {{ $logs->links() }}
            </div>
        </div>

        <!-- Modal Mutasi Stok -->
        <div x-show="stockModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div x-show="stockModal" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="stockModal = false"></div>

                <div x-show="stockModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative w-full max-w-lg bg-white shadow-2xl rounded-3xl p-8">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-slate-800">Catat Mutasi Stok</h3>
                            <p class="text-sm text-slate-500 mt-1">Barang masuk, keluar, atau penyesuaian stok opname.</p>
                        </div>
                        <button @click="stockModal = false" class="text-slate-400 hover:text-rose-500 transition-colors p-1 bg-slate-50 hover:bg-rose-50 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <form action="{{ route('gudang.stocks.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Produk <span class="text-rose-500">*</span></label>
                            <select name="product_id" x-model="productField" required class="w-full border-slate-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 text-sm shadow-sm">
                                <option value="">-- Pilih Produk --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} (stok: {{ $product->stock }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Jenis Mutasi <span class="text-rose-500">*</span></label>
                            <select name="type" x-model="typeField" required class="w-full border-slate-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 text-sm shadow-sm">
                                <option value="in">Barang Masuk (tambah stok)</option>
                                <option value="out">Barang Keluar (kurangi stok)</option>
                                <option value="adjustment">Penyesuaian (set stok akhir)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                <span x-text="typeField === 'adjustment' ? 'Stok Akhir' : 'Jumlah'"></span> <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" name="qty" x-model="qtyField" min="1" required class="w-full border-slate-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 text-sm shadow-sm">
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button" @click="stockModal = false" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 rounded-xl text-slate-700 font-bold transition">Batal</button>
                            <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 rounded-xl text-white font-bold transition shadow-md shadow-emerald-500/25">Simpan Mutasi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
