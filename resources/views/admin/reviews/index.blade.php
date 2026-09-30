<x-app-layout>
    @section('title', 'Moderasi Ulasan')

    <x-slot name="header">Moderasi Ulasan</x-slot>

    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Moderasi Ulasan Pelanggan</h2>
            <p class="text-sm text-slate-500">Sembunyikan ulasan tidak pantas atau berikan balasan resmi toko.</p>
        </div>
        <form action="{{ route('admin.reviews.index') }}" method="GET" class="flex items-center gap-2">
            <select name="status" onchange="this.form.submit()" class="rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm">
                <option value="">Semua Status</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Tampil</option>
                <option value="hidden" {{ request('status') === 'hidden' ? 'selected' : '' }}>Disembunyikan</option>
            </select>
            <select name="rating" onchange="this.form.submit()" class="rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm">
                <option value="">Semua Rating</option>
                @for($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}" {{ (string) request('rating') === (string) $i ? 'selected' : '' }}>{{ $i }} Bintang</option>
                @endfor
            </select>
        </form>
    </div>

    <div class="space-y-5 mb-8">
        @forelse($reviews as $review)
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Produk -->
                <div class="flex items-center gap-4 md:w-64 shrink-0">
                    <div class="w-16 h-16 bg-slate-50 rounded-xl overflow-hidden flex-shrink-0 border border-slate-100 flex items-center justify-center">
                        @if($review->product && $review->product->image)
                            <img src="{{ Str::startsWith($review->product->image, 'http') ? $review->product->image : Storage::url($review->product->image) }}" class="w-full h-full object-cover">
                        @else
                            <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <div class="font-bold text-slate-800 text-sm truncate">{{ $review->product->name ?? 'Produk Terhapus' }}</div>
                        <div class="text-xs text-slate-500 mt-0.5">{{ $review->user->name ?? 'User Terhapus' }}</div>
                        <div class="text-xs text-slate-400">{{ $review->created_at->format('d M Y') }}</div>
                    </div>
                </div>

                <!-- Isi Ulasan -->
                <div class="flex-grow">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            @endfor
                            <span class="ml-2 text-xs font-bold text-slate-600">{{ $review->rating }}/5</span>
                        </div>
                        @if($review->status === 'published')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Tampil</span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">Disembunyikan</span>
                        @endif
                    </div>

                    <p class="text-slate-600 text-sm italic border-l-4 border-emerald-500 pl-4 py-1 bg-slate-50 rounded-r-lg mb-4">
                        "{{ $review->comment ?: 'Tidak ada komentar.' }}"
                    </p>

                    @if($review->admin_reply)
                    <div class="mb-4 p-3 bg-indigo-50 border border-indigo-100 rounded-xl">
                        <div class="text-xs font-bold text-indigo-700 mb-1">Balasan Admin:</div>
                        <div class="text-sm text-indigo-900">{{ $review->admin_reply }}</div>
                    </div>
                    @endif

                    <!-- Aksi -->
                    <div class="flex flex-wrap gap-3" x-data="{ replyOpen: false }">
                        <form action="{{ route('admin.reviews.update', $review->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="{{ $review->status === 'published' ? 'hidden' : 'published' }}">
                            <button type="submit" class="text-sm font-bold {{ $review->status === 'published' ? 'text-slate-600 bg-slate-100 hover:bg-slate-200' : 'text-emerald-600 bg-emerald-50 hover:bg-emerald-100' }} px-4 py-2 rounded-xl transition border {{ $review->status === 'published' ? 'border-slate-200' : 'border-emerald-200' }}">
                                {{ $review->status === 'published' ? 'Sembunyikan' : 'Tampilkan Kembali' }}
                            </button>
                        </form>

                        <button @click="replyOpen = !replyOpen" class="text-sm font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 px-4 py-2 rounded-xl transition border border-indigo-100">
                            Balas Ulasan
                        </button>

                        <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Hapus ulasan ini secara permanen?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 px-4 py-2 rounded-xl transition border border-rose-100">
                                Hapus
                            </button>
                        </form>

                        <!-- Form Balasan -->
                        <div x-show="replyOpen" x-cloak x-transition class="w-full mt-3">
                            <form action="{{ route('admin.reviews.update', $review->id) }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="{{ $review->status }}">
                                <input type="text" name="admin_reply" value="{{ $review->admin_reply }}" placeholder="Tulis balasan resmi toko..." class="flex-grow border-slate-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 text-sm shadow-sm">
                                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-6 py-2 rounded-xl transition shadow-md shadow-emerald-500/25">
                                    Kirim
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 py-16 text-center">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                <svg class="w-9 h-9 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
            </div>
            <h4 class="font-bold text-slate-700 mb-1">Belum Ada Ulasan</h4>
            <p class="text-sm text-slate-500">Ulasan pelanggan akan muncul di sini untuk dimoderasi.</p>
        </div>
        @endforelse
    </div>

    @if($reviews->hasPages())
    <div class="mt-6">
        {{ $reviews->links() }}
    </div>
    @endif
</x-app-layout>