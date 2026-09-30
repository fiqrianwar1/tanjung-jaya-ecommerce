<x-app-layout>
    @section('title', 'Pesanan Saya')

    <x-slot name="header">
        Pesanan Saya
    </x-slot>

    <div class="max-w-5xl mx-auto pb-12 mt-6">
        <h2 class="text-2xl font-bold text-slate-800 mb-6">Riwayat Pesanan</h2>

        @if($orders->isEmpty())
            <div class="bg-gradient-to-br from-white to-slate-50 rounded-3xl shadow-[0_8px_30px_-4px_rgba(0,0,0,0.05)] border border-slate-100 p-16 text-center relative overflow-hidden">
                <div class="absolute -top-24 -left-24 w-64 h-64 bg-emerald-50 rounded-full mix-blend-multiply filter blur-3xl opacity-60 pointer-events-none"></div>
                <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-slate-100 rounded-full mix-blend-multiply filter blur-3xl opacity-60 pointer-events-none"></div>
                
                <div class="relative z-10 w-32 h-32 bg-white shadow-xl shadow-slate-200/50 rounded-full flex items-center justify-center mx-auto mb-8 border border-slate-100">
                    <svg class="w-14 h-14 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <h3 class="text-2xl font-black text-slate-800 mb-3 relative z-10 tracking-tight">Belum ada pesanan</h3>
                <p class="text-slate-500 mb-8 relative z-10 text-lg">Anda belum pernah melakukan pemesanan apapun. Silakan belanja terlebih dahulu.</p>
                <a href="{{ route('home') }}" class="relative z-10 inline-flex items-center bg-slate-800 hover:bg-slate-700 text-white font-bold py-3.5 px-8 rounded-xl transition shadow-lg shadow-slate-800/20 transform hover:-translate-y-1">
                    Kembali ke Beranda
                </a>
            </div>
        @else
            <div class="space-y-6">
                @foreach($orders as $order)
                <div class="bg-white rounded-3xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-100 overflow-hidden hover:shadow-[0_8px_30px_-4px_rgba(0,0,0,0.08)] transition-shadow duration-300">
                    <div class="border-b border-slate-100 bg-slate-50/50 backdrop-blur-sm px-6 md:px-8 py-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal Pesanan</div>
                            <div class="font-bold text-slate-800">{{ $order->created_at->format('d M Y, H:i') }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Belanja</div>
                            <div class="font-black text-emerald-600 text-lg">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                        </div>
                        <div>
                            @if($order->status === 'processing')
                                <span class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider bg-gradient-to-r from-amber-400 to-orange-400 text-white shadow-md shadow-amber-500/20">Sedang Diproses</span>
                            @elseif($order->status === 'shipped')
                                <span class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider bg-gradient-to-r from-blue-400 to-indigo-500 text-white shadow-md shadow-blue-500/20">Dalam Pengiriman</span>
                            @elseif($order->status === 'completed')
                                <span class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider bg-gradient-to-r from-emerald-400 to-teal-500 text-white shadow-md shadow-emerald-500/20">Selesai</span>
                            @elseif($order->status === 'returned')
                                <span class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider bg-gradient-to-r from-red-500 to-rose-500 text-white shadow-md shadow-red-500/20">Diretur</span>
                            @endif
                            
                            <a href="{{ route('customer.orders.show', $order->id) }}" class="mt-3 md:mt-0 md:ml-4 inline-flex items-center bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-xl text-xs font-bold transition-colors">
                                Detail & Lacak
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                    
                    <div class="p-6 md:p-8">
                        <ul class="divide-y divide-slate-100">
                            @foreach($order->orderItems as $item)
                            <li class="py-4 flex gap-4">
                                <div class="w-16 h-16 bg-slate-100 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden relative">
                                    @if($item->product->image)
                                        <img src="{{ Str::startsWith($item->product->image, 'http') ? $item->product->image : Storage::url($item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    @endif
                                </div>
                                <div class="w-full">
                                    <h4 class="font-bold text-slate-800">{{ $item->product->name }}</h4>
                                    <p class="text-sm text-slate-500 mt-1 mb-2">{{ $item->qty }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
