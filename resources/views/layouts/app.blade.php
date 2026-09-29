<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Tanjung Jaya') }} - @yield('title', 'Dashboard')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body { font-family: 'Inter', sans-serif; }
            .glass { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
            .sidebar-link { transition: all 0.2s ease-in-out; }
            .sidebar-link:hover { background: rgba(59, 130, 246, 0.08); color: #2563eb; transform: translateX(4px); }
            .sidebar-link.active { background: rgba(59, 130, 246, 0.1); color: #2563eb; font-weight: 600; border-left: 3px solid #2563eb; }
        </style>
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-800" x-data="{ sidebarOpen: false, desktopSidebarCollapsed: false, profileOpen: false, flashOpen: true }">
        
        <!-- Flash Messages -->
        @if (session('success') || session('error') || session('warning'))
        <div x-show="flashOpen" x-transition.opacity class="fixed top-5 right-5 z-50 max-w-sm w-full">
            <div class="p-4 rounded-xl shadow-lg border-l-4 {{ session('success') ? 'bg-emerald-50 border-emerald-500' : (session('error') ? 'bg-red-50 border-red-500' : 'bg-amber-50 border-amber-500') }} flex justify-between items-start">
                <div>
                    <h3 class="font-semibold {{ session('success') ? 'text-emerald-800' : (session('error') ? 'text-red-800' : 'text-amber-800') }}">Notifikasi</h3>
                    <p class="text-sm mt-1 {{ session('success') ? 'text-emerald-700' : (session('error') ? 'text-red-700' : 'text-amber-700') }}">
                        {{ session('success') ?? session('error') ?? session('warning') }}
                    </p>
                </div>
                <button @click="flashOpen = false" class="text-slate-400 hover:text-slate-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>
        @endif

        <div class="flex h-screen overflow-hidden">
            
            <!-- Sidebar (For Admin, Gudang, Manager) -->
            @if(auth()->check() && in_array(auth()->user()->role, ['Admin', 'Gudang', 'Manager']))
            <!-- Desktop Sidebar -->
            <aside :class="desktopSidebarCollapsed ? 'w-20' : 'w-64'" class="hidden lg:flex flex-col bg-white border-r border-slate-200 shadow-sm z-20 transition-all duration-300 ease-in-out">
                <div class="h-16 flex items-center justify-center border-b border-slate-100 px-4">
                    <span x-show="!desktopSidebarCollapsed" class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-emerald-600 to-teal-600 whitespace-nowrap">
                        Tanjung Jaya
                    </span>
                    <span x-show="desktopSidebarCollapsed" style="display: none;" class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-emerald-600 to-teal-600">
                        TJ
                    </span>
                </div>
                <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1 overflow-x-hidden">
                    @include('layouts.partials.sidebar-menu')
                </nav>
            </aside>

            <!-- Mobile Sidebar Backdrop -->
            <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/50 z-30 lg:hidden" @click="sidebarOpen = false"></div>
            
            <!-- Mobile Sidebar -->
            <aside x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed inset-y-0 left-0 w-64 bg-white shadow-2xl z-40 lg:hidden flex flex-col">
                <div class="h-16 flex items-center justify-between px-6 border-b border-slate-100">
                    <span class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-emerald-600 to-teal-600">
                        Tanjung Jaya
                    </span>
                    <button @click="sidebarOpen = false" class="text-slate-500 hover:text-slate-700 focus:outline-none bg-slate-100 p-1.5 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <nav class="flex-1 overflow-y-auto py-4 px-2 space-y-1">
                    @include('layouts.partials.sidebar-menu')
                </nav>
            </aside>
            @endif

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col overflow-hidden relative">
                
                <!-- Top Navigation -->
                <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 z-30 sticky top-0 shadow-sm">
                    <div class="flex items-center flex-1">
                        @if(auth()->check() && in_array(auth()->user()->role, ['Admin', 'Gudang', 'Manager']))
                        <!-- Mobile Toggle -->
                        <button @click="sidebarOpen = true" class="text-slate-500 hover:text-emerald-600 focus:outline-none lg:hidden mr-4 transition bg-slate-50 p-2 rounded-lg border border-slate-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                        </button>
                        <!-- Desktop Toggle -->
                        <button @click="desktopSidebarCollapsed = !desktopSidebarCollapsed" class="hidden lg:block text-slate-500 hover:text-emerald-600 focus:outline-none mr-4 transition bg-slate-50 p-2 rounded-lg border border-slate-200" title="Toggle Sidebar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                        @endif
                        
                        @if(!auth()->check() || auth()->user()->role === 'Customer')
                        <a href="{{ route('home') }}" class="flex items-center mr-8">
                            <img src="{{ asset('images/logo.jpg') }}" alt="Tanjung Jaya Logo" class="h-10 w-auto object-contain">
                        </a>
                        @endif

                        <!-- Search Bar for Customer/Guest -->
                        @if(!auth()->check() || auth()->user()->role === 'Customer')
                        <div class="hidden md:flex flex-1 max-w-3xl mr-8">
                            <form action="{{ route('home') }}" method="GET" class="w-full flex">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari di Tanjung Jaya" class="w-full border border-slate-300 rounded-l-lg px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                                <button type="submit" class="bg-slate-100 hover:bg-slate-200 border border-l-0 border-slate-300 rounded-r-lg px-4 flex items-center justify-center transition-colors">
                                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </button>
                            </form>
                        </div>
                        @endif
                        
                        <!-- Header Slot Fallback -->
                        @isset($header)
                            <h1 class="text-lg font-semibold text-slate-800 ml-2 hidden sm:block">{{ $header }}</h1>
                        @endisset
                    </div>

                    <!-- Right Nav -->
                    <div class="flex items-center space-x-3 sm:space-x-5">
                        @if(!auth()->check() || auth()->user()->role === 'Customer')
                        <div class="flex items-center space-x-2">
                            @auth
                            <a href="{{ route('customer.wishlists.index') }}" class="relative text-slate-500 hover:text-rose-500 transition p-1" title="Wishlist">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            </a>
                            <a href="{{ route('customer.orders.index') }}" class="relative text-slate-500 hover:text-emerald-500 transition p-1" title="Pesanan Saya">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            </a>
                            <a href="{{ route('customer.reviews.index') }}" class="relative text-slate-500 hover:text-amber-500 transition p-1" title="Ulasan Saya">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></polygon></svg>
                            </a>
                            <a href="{{ route('customer.returns.index') }}" class="relative text-slate-500 hover:text-blue-500 transition p-1" title="Retur Saya">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"></path></svg>
                            </a>
                            @endauth
                            <a href="{{ route('customer.carts.index') }}" class="relative text-slate-500 hover:text-emerald-500 transition p-1" title="Keranjang">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </a>
                        </div>
                        <div class="h-6 w-px bg-slate-300 hidden md:block mx-2"></div>
                        @endif

                        @auth
                        <div class="relative">
                            <button @click="profileOpen = !profileOpen" @click.outside="profileOpen = false" class="flex items-center space-x-2 text-slate-600 hover:text-emerald-500 transition focus:outline-none py-1">
                                <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <span class="hidden md:block font-medium text-sm">{{ explode(' ', auth()->user()->name)[0] }}</span>
                            </button>

                            <!-- Dropdown -->
                            <div x-show="profileOpen" x-transition.opacity class="absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-xl py-2 border border-slate-100 z-50">
                                <div class="px-4 py-3 border-b border-slate-50 mb-1 bg-slate-50/50">
                                    <p class="text-sm font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-slate-500 truncate mt-0.5">{{ auth()->user()->email }}</p>
                                    <span class="inline-block mt-2 px-2.5 py-0.5 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-md uppercase tracking-wide">{{ auth()->user()->role }}</span>
                                </div>
                                @if(auth()->user()->role === 'Customer')
                                <a href="{{ route('customer.orders.index') }}" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-emerald-500 transition flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                    Pesanan Saya
                                </a>
                                @endif
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-emerald-500 transition flex items-center border-t border-slate-100">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    Pengaturan Profil
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition flex items-center border-t border-slate-100">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                        @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-emerald-500 hover:text-emerald-600 hover:bg-emerald-50 border border-emerald-500 px-4 py-1.5 rounded-lg transition-colors">Masuk</a>
                        <a href="{{ route('register') }}" class="text-sm font-bold bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-1.5 rounded-lg transition-colors border border-emerald-500">Daftar</a>
                        @endauth
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto flex flex-col">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full">
                        {{ $slot }}
                    </div>
                    
                    <!-- Footer -->
                    <footer class="bg-white border-t border-slate-200 pt-16 pb-8 mt-auto relative overflow-hidden w-full shrink-0">
                        <!-- Subtle background decoration -->
                        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-emerald-50 rounded-full blur-3xl opacity-50 pointer-events-none"></div>
                        
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
                                <!-- Company Info -->
                                <div class="col-span-1">
                                    <a href="{{ route('home') }}" class="inline-block mb-5">
                                        <img src="{{ asset('images/logo.jpg') }}" alt="Tanjung Jaya Logo" class="h-12 w-auto object-contain drop-shadow-sm">
                                    </a>
                                    <p class="text-slate-500 mb-6 leading-relaxed text-sm">
                                        Solusi belanja terbaik untuk kebutuhan Anda. Kami berkomitmen memberikan produk berkualitas dengan harga bersahabat dan layanan terpercaya.
                                    </p>
                                    <div class="flex space-x-3">
                                        <!-- Facebook -->
                                        <a href="https://web.facebook.com/profile.php?id=61579544114753" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:border-blue-500 hover:bg-blue-50 transition-all transform hover:-translate-y-1 shadow-sm" title="Facebook">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
                                        </a>
                                        <!-- Instagram -->
                                        <a href="https://www.instagram.com/tanjungjayacorporation?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400 hover:text-pink-600 hover:border-pink-500 hover:bg-pink-50 transition-all transform hover:-translate-y-1 shadow-sm" title="Instagram">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                        </a>
                                        <!-- TikTok -->
                                        <a href="https://www.tiktok.com/@tanjungjaya_corporation?is_from_webapp=1&sender_device=pc" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-900 hover:border-slate-800 hover:bg-slate-200 transition-all transform hover:-translate-y-1 shadow-sm" title="TikTok">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                                        </a>
                                        <!-- YouTube -->
                                        <a href="https://www.youtube.com/@tanjungjayacorporation" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400 hover:text-red-600 hover:border-red-500 hover:bg-red-50 transition-all transform hover:-translate-y-1 shadow-sm" title="YouTube">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                        </a>
                                        <!-- Google Maps -->
                                        <a href="https://maps.app.goo.gl/SaACFCrE4nHxP9jk6" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400 hover:text-emerald-600 hover:border-emerald-500 hover:bg-emerald-50 transition-all transform hover:-translate-y-1 shadow-sm" title="Google Maps">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 010-5 2.5 2.5 0 010 5z"/></svg>
                                        </a>
                                    </div>
                                </div>
                                
                                <!-- Address -->
                                <div class="col-span-1 md:col-span-2">
                                    <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-6">Kantor Pusat</h4>
                                    <div class="bg-gradient-to-br from-slate-50 to-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] flex flex-col sm:flex-row gap-6 items-start hover:shadow-[0_8px_30px_-4px_rgba(0,0,0,0.08)] transition-shadow duration-300">
                                        <div class="bg-emerald-100/80 p-4 rounded-2xl text-emerald-600 shrink-0 shadow-inner">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        </div>
                                        <div class="flex-1">
                                            <h5 class="font-extrabold text-slate-800 text-lg md:text-xl mb-2">PT. WARNA TANJUNG JAYA & PT. SUMBER TANJUNG JAYA</h5>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                                <div class="flex items-start space-x-3">
                                                    <svg class="w-5 h-5 text-slate-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                    <p class="text-slate-600 text-sm leading-relaxed">Jl. Haryono MT No 12-14,<br>Kertak Baru Ilir Banjarmasin</p>
                                                </div>
                                                <div class="space-y-4">
                                                    <div class="flex items-center space-x-3">
                                                        <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                                                        <p class="text-slate-600 text-sm font-medium">NPWP: 001.741.164.6-731.000</p>
                                                    </div>
                                                    <div class="flex items-center space-x-3">
                                                        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                                        <span class="text-emerald-600 text-sm font-bold">0511-3362552, 3360492</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="border-t border-slate-200 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                                <div class="text-slate-500 text-sm font-medium">
                                    &copy; {{ date('Y') }} Tanjung Jaya Corporation. All rights reserved.
                                </div>
                                <div class="flex flex-wrap justify-center space-x-6 text-sm">
                                    <a href="{{ route('home') }}" class="text-slate-500 hover:text-emerald-600 font-medium transition">Katalog Produk</a>
                                    <a href="https://maps.app.goo.gl/SaACFCrE4nHxP9jk6" target="_blank" rel="noopener noreferrer" class="text-slate-500 hover:text-emerald-600 font-medium transition">Lokasi Toko</a>
                                    @auth
                                    <a href="{{ route('profile.edit') }}" class="text-slate-500 hover:text-emerald-600 font-medium transition">Akun Saya</a>
                                    @else
                                    <a href="{{ route('login') }}" class="text-slate-500 hover:text-emerald-600 font-medium transition">Masuk</a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </footer>
                </main>
            </div>
        </div>
        
        <!-- Chatbot Widget (Only for Customers or Guests) -->
        @if(!auth()->check() || auth()->user()->role === 'Customer')
            <x-chatbot-widget />
        @endif
    </body>
</html>
