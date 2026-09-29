<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('admin.products.index') }}" class="text-emerald-600 hover:underline mr-2">&larr; Kembali</a>
        @if(isset($product)) Edit Produk @else Tambah Produk Baru @endif
    </x-slot>

    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-lg font-bold text-slate-800">
                @if(isset($product)) Informasi Produk (Edit) @else Informasi Produk Baru @endif
            </h3>
            <p class="text-sm text-slate-500 mt-1">Lengkapi form di bawah ini dengan detail yang akurat.</p>
        </div>

        @if ($errors->any())
        <div class="m-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
            <div class="flex">
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan pada input Anda:</h3>
                    <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <form action="{{ isset($product) ? route('admin.products.update', $product->id) : route('admin.products.store') }}" method="POST" class="p-6" enctype="multipart/form-data">
            @csrf
            @if(isset($product))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 py-2.5 px-3 border" placeholder="Contoh: Kipas Angin Cosmos">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Foto Produk</label>
                    @if(isset($product) && $product->image)
                        <div class="mb-3">
                            <img src="{{ Str::startsWith($product->image, 'http') ? $product->image : Storage::url($product->image) }}" alt="Current Image" class="w-32 h-32 object-cover rounded-xl border border-slate-200">
                        </div>
                    @endif
                    <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 py-2 px-3 border bg-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                    <p class="text-xs text-slate-500 mt-1">Biarkan kosong jika tidak ingin mengubah gambar. Format: JPG, PNG, WEBP (Max 2MB)</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                    <select name="category_id" required class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 py-2.5 px-3 border bg-white">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Harga (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" min="0" name="price" value="{{ old('price', $product->price ?? '') }}" required class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 py-2.5 px-3 border" placeholder="Contoh: 150000">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Stok (Baik) <span class="text-red-500">*</span></label>
                    <input type="number" min="0" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" required class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 py-2.5 px-3 border">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Stok (Rusak)</label>
                    <input type="number" min="0" name="bad_stock" value="{{ old('bad_stock', $product->bad_stock ?? 0) }}" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 py-2.5 px-3 border">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Status Visibilitas</label>
                    <div class="flex space-x-6">
                        <label class="flex items-center">
                            <input type="radio" name="status" value="active" {{ old('status', $product->status ?? 'active') == 'active' ? 'checked' : '' }} class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300">
                            <span class="ml-2 text-sm text-slate-700">Aktif (Tampil)</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="status" value="inactive" {{ old('status', $product->status ?? '') == 'inactive' ? 'checked' : '' }} class="h-4 w-4 text-slate-600 focus:ring-slate-500 border-gray-300">
                            <span class="ml-2 text-sm text-slate-700">Nonaktif (Disembunyikan)</span>
                        </label>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Deskripsi Produk</label>
                    <textarea name="description" rows="4" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 py-2.5 px-3 border">{{ old('description', $product->description ?? '') }}</textarea>
                </div>
            </div>

            <div class="flex justify-end pt-5 border-t border-slate-100">
                <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 bg-white border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50 font-medium mr-3 transition shadow-sm">Batal</a>
                <button type="submit" class="px-5 py-2.5 bg-emerald-600 rounded-lg text-white font-medium hover:bg-emerald-700 transition shadow-md shadow-emerald-500/30">
                    Simpan Produk
                </button>
            </div>
        </form>
    </div>
</x-app-layout>