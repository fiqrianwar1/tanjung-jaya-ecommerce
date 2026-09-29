@php
    $code = $exception->getStatusCode() ?? 500;

    $presets = [
        403 => ['title' => 'Akses Ditolak', 'message' => 'Anda tidak memiliki hak akses untuk membuka halaman ini.'],
        404 => ['title' => 'Halaman Tidak Ditemukan', 'message' => 'Halaman yang Anda cari mungkin telah dipindahkan atau dihapus.'],
        419 => ['title' => 'Sesi Kedaluwarsa', 'message' => 'Sesi Anda telah berakhir karena tidak ada aktivitas. Silakan muat ulang halaman.'],
        429 => ['title' => 'Terlalu Banyak Permintaan', 'message' => 'Anda mengirim permintaan terlalu sering. Mohon tunggu sebentar.'],
        500 => ['title' => 'Terjadi Kesalahan', 'message' => 'Sistem kami sedang mengalami gangguan. Tim teknis telah diberi tahu.'],
        503 => ['title' => 'Layanan Tidak Tersedia', 'message' => 'Kami sedang melakukan pemeliharaan. Silakan coba beberapa saat lagi.'],
    ];

    $info = $presets[$code] ?? $presets[500];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $code }} — {{ $info['title'] }} | {{ config('app.name', 'Tanjung Jaya') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Inter', sans-serif; }
        </style>
    </head>
    <body class="font-sans antialiased bg-slate-50 min-h-screen flex items-center justify-center p-6 relative overflow-hidden">
        <!-- Dekorasi latar -->
        <div class="absolute top-0 right-0 w-[480px] h-[480px] bg-emerald-100 rounded-full filter blur-3xl opacity-50 transform translate-x-1/3 -translate-y-1/3 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-[520px] h-[520px] bg-teal-100 rounded-full filter blur-3xl opacity-40 transform -translate-x-1/3 translate-y-1/3 pointer-events-none"></div>

        <div class="relative z-10 w-full max-w-lg">
            <div class="bg-white rounded-3xl shadow-[0_20px_60px_-20px_rgba(0,0,0,0.15)] border border-slate-100 p-10 sm:p-12 text-center">
                <a href="{{ url('/') }}" class="inline-block mb-8">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Tanjung Jaya Logo" class="h-14 w-auto object-contain mx-auto drop-shadow-sm">
                </a>

                <div class="text-7xl sm:text-8xl font-black tracking-tighter bg-clip-text text-transparent bg-gradient-to-br from-emerald-500 to-teal-600 mb-4">
                    {{ $code }}
                </div>

                <h1 class="text-2xl font-black text-slate-800 mb-3">{{ $info['title'] }}</h1>
                <p class="text-slate-500 leading-relaxed mb-10">{{ $info['message'] }}</p>

                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ url('/') }}" class="inline-flex items-center justify-center bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-white font-bold px-6 py-3 rounded-xl transition-all shadow-lg shadow-emerald-500/25 transform hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-emerald-400/40">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Kembali ke Beranda
                    </a>

                    @auth
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-6 py-3 rounded-xl transition-colors border border-slate-200">
                        Dashboard Saya
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-6 py-3 rounded-xl transition-colors border border-slate-200">
                        Masuk
                    </a>
                    @endauth
                </div>

                <div class="mt-10 pt-6 border-t border-slate-100 text-xs text-slate-400 font-medium">
                    &copy; {{ date('Y') }} Tanjung Jaya Corporation. All rights reserved.
                </div>
            </div>
        </div>
    </body>
</html>