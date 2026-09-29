<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            {{ __('Ulasan Saya') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 sm:p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <div>
                    <h3 class="text-xl font-bold text-slate-800">Daftar Ulasan</h3>
                    <p class="text-sm text-slate-500 mt-1">Kelola semua ulasan produk yang pernah Anda berikan.</p>
                </div>
            </div>

            <div class="p-6 sm:p-8">
                @if($reviews->count() > 0)
                    <div class="space-y-6">
                        @foreach($reviews as $review)
                            <div class="bg-white border border-slate-200 rounded-2xl p-6 flex flex-col md:flex-row gap-6 shadow-sm hover:shadow-md transition duration-200">
                                <div class="w-24 h-24 bg-slate-100 rounded-xl overflow-hidden flex-shrink-0 border border-slate-200 flex items-center justify-center">
                                    @if($review->product && $review->product->image)
                                        <img src="{{ Str::startsWith($review->product->image, 'http') ? $review->product->image : Storage::url($review->product->image) }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    @endif
                                </div>
                                
                                <div class="flex-grow">
                                    <div class="flex justify-between items-start mb-2">
                                        <a href="{{ route('products.show', $review->product_id) }}" class="text-lg font-bold text-slate-800 hover:text-emerald-600 transition">
                                            {{ $review->product->name ?? 'Produk Tidak Diketahui' }}
                                        </a>
                                        <span class="text-xs text-slate-400 font-medium bg-slate-100 px-2.5 py-1 rounded-lg">
                                            {{ $review->created_at->format('d M Y') }}
                                        </span>
                                    </div>
                                    
                                    <div class="flex items-center mb-3">
                                        <div class="flex text-amber-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-amber-400 fill-current' : 'text-slate-200' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                            @endfor
                                        </div>
                                        <span class="ml-2 text-sm font-bold text-slate-600">{{ $review->rating }}/5</span>
                                    </div>
                                    
                                    <p class="text-slate-600 text-sm italic border-l-4 border-emerald-500 pl-4 py-1 mb-4 bg-slate-50 rounded-r-lg">
                                        "{{ $review->comment ?: 'Tidak ada komentar.' }}"
                                    </p>
                                    
                                    <div class="flex gap-3">
                                        <a href="{{ route('customer.reviews.edit', $review->id) }}" class="text-sm font-bold text-emerald-600 bg-emerald-50 hover:bg-emerald-100 px-4 py-2 rounded-xl transition border border-emerald-100">Edit Ulasan</a>
                                        
                                        <form action="{{ route('customer.reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus ulasan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 px-4 py-2 rounded-xl transition border border-rose-100">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-8">
                        {{ $reviews->links() }}
                    </div>
                @else
                    <div class="text-center py-16 bg-slate-50/50 rounded-3xl border border-dashed border-slate-200">
                        <div class="w-20 h-20 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-slate-700 mb-2">Belum Ada Ulasan</h4>
                        <p class="text-slate-500 mb-6 max-w-md mx-auto">Anda belum memberikan ulasan pada produk apapun. Beli produk dan berikan ulasan agar pengguna lain tahu!</p>
                        <a href="{{ route('customer.orders.index') }}" class="inline-block bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-6 rounded-xl transition shadow-sm">Lihat Pesanan Saya</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
