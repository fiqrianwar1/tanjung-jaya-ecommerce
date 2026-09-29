<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Tanjung Jaya') }} - Autentikasi</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Inter', sans-serif; }
            .bg-glass { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); }
        </style>
    </head>
    <body class="font-sans text-slate-900 antialiased bg-slate-50 min-h-screen flex">
        
        <!-- Left Side: Branding Banner (Hidden on Mobile) -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-emerald-600 via-teal-700 to-slate-900 p-12 flex-col justify-between relative overflow-hidden">
            <!-- Decorative Background Elements -->
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-white opacity-5 rounded-full mix-blend-overlay filter blur-3xl transform translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-emerald-400 opacity-20 rounded-full mix-blend-multiply filter blur-3xl transform -translate-x-1/4 translate-y-1/4"></div>
            
            <div class="relative z-10">
                <a href="/" class="text-4xl font-black text-white tracking-tighter drop-shadow-md">
                    Tanjung Jaya<span class="text-emerald-300">.</span>
                </a>
            </div>
            
            <div class="relative z-10 mb-10">
                <h1 class="text-5xl font-black text-white mb-6 leading-tight drop-shadow-lg">
                    Sistem Manajemen<br>
                    <span class="text-emerald-300">Terintegrasi.</span>
                </h1>
                <p class="text-xl text-emerald-50 font-medium max-w-lg leading-relaxed drop-shadow">
                    Bergabunglah untuk mengelola inventaris, pesanan, dan operasional bisnis Tanjung Jaya Corporation dengan presisi dan kecepatan tinggi.
                </p>
            </div>
            
            <div class="relative z-10 text-emerald-200/60 text-sm font-medium">
                &copy; {{ date('Y') }} Tanjung Jaya Corporation. Hak Cipta Dilindungi.
            </div>
        </div>

        <!-- Right Side: Auth Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 relative">
            <!-- Mobile Background Decorations -->
            <div class="absolute inset-0 bg-gradient-to-br from-slate-50 to-emerald-50/50 lg:hidden -z-10"></div>
            <div class="absolute top-0 left-0 w-full h-64 bg-emerald-600 lg:hidden -z-10 rounded-b-[40px] opacity-10"></div>

            <div class="w-full max-w-md bg-white lg:bg-transparent rounded-3xl lg:rounded-none shadow-2xl lg:shadow-none border border-slate-100 lg:border-none p-8 sm:p-10 lg:p-0">
                <!-- Mobile Logo -->
                <div class="lg:hidden flex justify-center mb-8">
                    <a href="/" class="text-4xl font-black text-emerald-600 tracking-tighter">
                        Tanjung Jaya<span class="text-slate-800">.</span>
                    </a>
                </div>

                {{ $slot }}
            </div>
        </div>
    </body>
</html>
