<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('customer.reviews.index') }}" class="text-emerald-600 hover:underline font-medium">&larr; Kembali ke Ulasan Saya</a>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-4">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
            <h2 class="text-2xl font-black text-slate-800 tracking-tight mb-2">Edit Ulasan</h2>
            <p class="text-slate-500 mb-8">Ubah ulasan dan rating Anda untuk produk ini.</p>
            
            <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl mb-8 border border-slate-100">
                <div class="w-16 h-16 bg-white rounded-xl overflow-hidden flex-shrink-0 border border-slate-200 flex items-center justify-center">
                    @if($review->product && $review->product->image)
                        <img src="{{ Str::startsWith($review->product->image, 'http') ? $review->product->image : Storage::url($review->product->image) }}" class="w-full h-full object-cover">
                    @endif
                </div>
                <div>
                    <h3 class="font-bold text-slate-800">{{ $review->product->name ?? 'Produk' }}</h3>
                    <p class="text-xs text-slate-500 mt-1">Diberikan pada: {{ $review->created_at->format('d M Y') }}</p>
                </div>
            </div>

            <form action="{{ route('customer.reviews.update', $review->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Rating</label>
                    <select name="rating" required class="w-full sm:w-1/2 border-slate-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 text-sm font-bold text-amber-600 shadow-sm cursor-pointer">
                        <option value="5" {{ $review->rating == 5 ? 'selected' : '' }}>⭐⭐⭐⭐⭐ 5</option>
                        <option value="4" {{ $review->rating == 4 ? 'selected' : '' }}>⭐⭐⭐⭐ 4</option>
                        <option value="3" {{ $review->rating == 3 ? 'selected' : '' }}>⭐⭐⭐ 3</option>
                        <option value="2" {{ $review->rating == 2 ? 'selected' : '' }}>⭐⭐ 2</option>
                        <option value="1" {{ $review->rating == 1 ? 'selected' : '' }}>⭐ 1</option>
                    </select>
                    @error('rating') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="mb-8">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Komentar</label>
                    <textarea name="comment" rows="4" placeholder="Bagaimana kualitas produk ini? Ceritakan pengalamanmu..." class="w-full border-slate-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 text-sm shadow-sm resize-none" required>{{ old('comment', $review->comment) }}</textarea>
                    @error('comment') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('customer.reviews.index') }}" class="px-6 py-2.5 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition">Batal</a>
                    <button type="submit" class="px-8 py-2.5 rounded-xl font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition shadow-md shadow-emerald-500/20">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
