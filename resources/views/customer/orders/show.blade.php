<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('customer.orders.index') }}" class="text-emerald-600 hover:underline font-medium">&larr; Kembali ke Pesanan Saya</a>
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-4">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">Detail Pesanan</h2>
                <p class="text-slate-500 font-medium">No. Pesanan: <span class="font-bold text-slate-700">#ORD-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span></p>
            </div>
            <div>
                @if($order->status === 'processing')
                    <span class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider bg-gradient-to-r from-amber-400 to-orange-400 text-white shadow-md shadow-amber-500/20">Sedang Diproses</span>
                @elseif($order->status === 'shipped')
                    <span class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider bg-gradient-to-r from-blue-400 to-indigo-500 text-white shadow-md shadow-blue-500/20">Dalam Pengiriman</span>
                @elseif($order->status === 'completed')
                    <span class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider bg-gradient-to-r from-emerald-400 to-teal-500 text-white shadow-md shadow-emerald-500/20">Pesanan Selesai</span>
                @elseif($order->status === 'returned')
                    <span class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider bg-gradient-to-r from-red-500 to-rose-500 text-white shadow-md shadow-red-500/20">Diretur</span>
                @endif
            </div>
        </div>

        <!-- Tracking Timeline -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 mb-8 overflow-hidden relative">
            <h3 class="text-lg font-bold text-slate-800 mb-6 relative z-10">Status Pelacakan</h3>
            
            <div class="relative z-10 ml-2 md:ml-4">
                <!-- Vertical Line -->
                <div class="absolute left-5 top-2 bottom-2 w-1 bg-slate-100 rounded-full z-0"></div>
                
                <div class="space-y-8">
                    <!-- Menunggu Pembayaran -->
                    <div class="relative flex items-center group z-10">
                        <div class="w-10 h-10 rounded-full border-4 border-white shadow-sm flex items-center justify-center bg-emerald-500 text-white flex-shrink-0 z-10">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div class="ml-6">
                            <h4 class="font-bold text-slate-800">Menunggu Pembayaran</h4>
                            <p class="text-sm text-slate-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    <!-- Diproses Gudang -->
                    <div class="relative flex items-center group z-10">
                        <div class="w-10 h-10 rounded-full border-4 border-white shadow-sm flex items-center justify-center flex-shrink-0 z-10 {{ in_array($order->status, ['processing', 'shipped', 'completed', 'returned']) ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-400' }}">
                            @if(in_array($order->status, ['processing', 'shipped', 'completed', 'returned']))
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            @endif
                        </div>
                        <div class="ml-6">
                            <h4 class="font-bold {{ in_array($order->status, ['processing', 'shipped', 'completed', 'returned']) ? 'text-slate-800' : 'text-slate-400' }}">Pesanan Diproses</h4>
                            <p class="text-sm text-slate-500">Gudang sedang menyiapkan barang pesanan Anda.</p>
                        </div>
                    </div>

                    <!-- Dalam Pengiriman -->
                    <div class="relative flex items-center group z-10">
                        <div class="w-10 h-10 rounded-full border-4 border-white shadow-sm flex items-center justify-center flex-shrink-0 z-10 {{ in_array($order->status, ['shipped', 'completed', 'returned']) ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-400' }}">
                            @if(in_array($order->status, ['shipped', 'completed', 'returned']))
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                            @endif
                        </div>
                        <div class="ml-6">
                            <h4 class="font-bold {{ in_array($order->status, ['shipped', 'completed', 'returned']) ? 'text-slate-800' : 'text-slate-400' }}">Dalam Pengiriman</h4>
                            @if(in_array($order->status, ['shipped', 'completed', 'returned']))
                            <p class="text-sm font-bold text-blue-600 mt-0.5">Resi: TJ-{{ md5($order->id) }}</p>
                            @else
                            <p class="text-sm text-slate-400">Menunggu kurir mengambil barang.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Selesai -->
                    <div class="relative flex items-center group z-10">
                        <div class="w-10 h-10 rounded-full border-4 border-white shadow-sm flex items-center justify-center flex-shrink-0 z-10 {{ in_array($order->status, ['completed', 'returned']) ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-400' }}">
                            @if(in_array($order->status, ['completed', 'returned']))
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            @endif
                        </div>
                        <div class="ml-6">
                            <h4 class="font-bold {{ in_array($order->status, ['completed', 'returned']) ? 'text-slate-800' : 'text-slate-400' }}">Pesanan Selesai</h4>
                            @if(in_array($order->status, ['completed', 'returned']))
                            <p class="text-sm text-slate-500">Barang telah diterima. Terima kasih telah berbelanja.</p>
                            @else
                            <p class="text-sm text-slate-400">Menunggu konfirmasi barang diterima.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 mb-8">
            <h3 class="text-lg font-bold text-slate-800 mb-6">Barang yang Dipesan</h3>
            
            <!-- Status Retur (jika sudah pernah diajukan) -->
            @if($order->returnRequest)
            <div class="mb-6 bg-amber-50 border border-amber-200 rounded-2xl p-5 flex items-start gap-3">
                <div class="bg-amber-100 text-amber-600 p-2 rounded-xl shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div>
                    <div class="font-bold text-amber-900">Pengajuan Retur Terkirim</div>
                    <p class="text-sm text-amber-800 mt-0.5">Status: <span class="font-semibold">{{ $order->returnRequest->status }}</span></p>
                    <p class="text-sm text-amber-700 mt-1">Alasan: {{ $order->returnRequest->reason }}</p>
                </div>
            </div>
            @endif

            <div class="space-y-6">
                @foreach($order->orderItems as $item)
                <div class="flex flex-col sm:flex-row gap-4 py-4 border-b border-slate-100 last:border-0 last:pb-0">
                    <div class="w-20 h-20 bg-slate-50 rounded-xl overflow-hidden flex-shrink-0 border border-slate-100 flex items-center justify-center">
                        @if($item->product->image)
                            <img src="{{ Str::startsWith($item->product->image, 'http') ? $item->product->image : Storage::url($item->product->image) }}" class="w-full h-full object-cover">
                        @else
                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        @endif
                    </div>

                    <div class="flex-grow">
                        <a href="{{ route('products.show', $item->product_id) }}" class="font-bold text-slate-800 hover:text-emerald-600 transition">{{ $item->product->name }}</a>
                        <p class="text-sm text-slate-500 mt-1">{{ $item->qty }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>

                        <!-- Review Form -->
                        @if($order->status === 'completed')
                        <div class="mt-4 bg-slate-50/50 p-5 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden max-w-xl">
                            <h5 class="text-sm font-bold text-slate-700 mb-3 relative z-10">Beri Ulasan untuk Produk ini</h5>
                            <form action="{{ route('customer.reviews.store') }}" method="POST" class="flex flex-col gap-3 relative z-10">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                                <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider w-16">Rating</label>
                                    <select name="rating" required class="text-sm font-bold text-amber-600 border-slate-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 bg-white shadow-sm w-40 cursor-pointer">
                                        <option value="5">⭐⭐⭐⭐⭐ 5</option>
                                        <option value="4">⭐⭐⭐⭐ 4</option>
                                        <option value="3">⭐⭐⭐ 3</option>
                                        <option value="2">⭐⭐ 2</option>
                                        <option value="1">⭐ 1</option>
                                    </select>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Komentar</label>
                                    <textarea name="comment" rows="2" placeholder="Bagaimana kualitas produk ini? Ceritakan pengalamanmu..." class="text-sm border-slate-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 w-full bg-white shadow-sm resize-none" required></textarea>
                                </div>
                                <div class="text-right mt-1">
                                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-6 rounded-xl text-sm transition-all shadow-md shadow-emerald-500/20">
                                        Kirim Ulasan
                                    </button>
                                </div>
                            </form>
                        </div>
                        @endif
                    </div>

                    <div class="text-right">
                        <p class="font-black text-slate-800">Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-8 pt-6 border-t border-slate-100 flex justify-between items-center">
                <span class="text-slate-500 font-bold uppercase tracking-wider">Total Pembayaran</span>
                <span class="text-2xl font-black text-emerald-600">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Return Request Form -->
        @if(in_array($order->status, ['completed', 'shipped']) && ! $order->returnRequest)
        <div class="bg-rose-50/50 rounded-3xl shadow-sm border border-rose-100 p-8 mb-8">
            <div class="flex items-center mb-6">
                <div class="bg-rose-100 text-rose-600 p-2.5 rounded-xl mr-3 border border-rose-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Ada Masalah dengan Pesanan?</h3>
                    <p class="text-sm text-slate-500 mt-0.5">Ajukan pengembalian (Retur) jika barang rusak, cacat, atau kedaluwarsa.</p>
                </div>
            </div>

            <form action="{{ route('customer.returns.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Barang yang Diretur</label>
                        <input type="text" name="item_name" list="order-item-list" required placeholder="Ketik atau pilih nama barang..." class="w-full border-slate-300 rounded-xl focus:ring-rose-500 focus:border-rose-500 text-sm shadow-sm">
                        <datalist id="order-item-list">
                            @foreach($order->orderItems as $item)
                                <option value="{{ $item->product->name }}"></option>
                            @endforeach
                        </datalist>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Alasan Retur</label>
                        <select name="reason" required class="w-full border-slate-300 rounded-xl focus:ring-rose-500 focus:border-rose-500 text-sm shadow-sm">
                            <option value="">-- Pilih Alasan --</option>
                            <option value="Barang Rusak/Cacat">Barang Rusak Fisik / Cacat Pabrik</option>
                            <option value="Barang Kedaluwarsa">Barang Kedaluwarsa (Expired)</option>
                            <option value="Barang Tidak Sesuai">Barang Tidak Sesuai Pesanan</option>
                            <option value="Lainnya">Lainnya...</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Penjelasan Detail</label>
                    <textarea name="description" rows="3" required placeholder="Jelaskan kondisi barang secara detail..." class="w-full border-slate-300 rounded-xl focus:ring-rose-500 focus:border-rose-500 text-sm shadow-sm"></textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Unggah Bukti Foto (Opsional)</label>
                    <input type="file" name="proof_image" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100 transition">
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-bold py-2.5 px-8 rounded-xl transition shadow-md shadow-rose-500/20">
                        Kirim Pengajuan Retur
                    </button>
                </div>
            </form>
        </div>
        @endif

    </div>
</x-app-layout>
