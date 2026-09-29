@if(auth()->user()->role === 'Admin')
    <a href="{{ route('home') }}" class="sidebar-link flex items-center px-3 py-2.5 text-slate-700 rounded-lg font-medium hover:bg-emerald-50 {{ request()->routeIs('home') ? 'active' : '' }}">
        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('home') ? 'text-emerald-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
        <span x-show="!desktopSidebarCollapsed" class="ml-3 whitespace-nowrap">Lihat Toko</span>
    </a>

    <div x-show="!desktopSidebarCollapsed" class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 mt-5 px-3 whitespace-nowrap transition-opacity duration-300">Modul Admin</div>
    <div x-show="desktopSidebarCollapsed" style="display: none;" class="w-full border-t border-slate-200 my-4"></div>

    <a href="{{ route('admin.products.index') }}" title="Katalog Produk" class="sidebar-link flex items-center px-3 py-2.5 text-slate-700 rounded-lg font-medium hover:bg-emerald-50 {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.products.*') ? 'text-emerald-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
        <span x-show="!desktopSidebarCollapsed" class="ml-3 whitespace-nowrap">Katalog Produk</span>
    </a>
    <a href="{{ route('admin.categories.index') }}" title="Kategori" class="sidebar-link flex items-center px-3 py-2.5 text-slate-700 rounded-lg font-medium hover:bg-emerald-50 {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.categories.*') ? 'text-emerald-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
        <span x-show="!desktopSidebarCollapsed" class="ml-3 whitespace-nowrap">Kategori</span>
    </a>
    <a href="{{ route('admin.orders.index') }}" title="Pesanan" class="sidebar-link flex items-center px-3 py-2.5 text-slate-700 rounded-lg font-medium hover:bg-emerald-50 {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.orders.*') ? 'text-emerald-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
        <span x-show="!desktopSidebarCollapsed" class="ml-3 whitespace-nowrap">Pesanan</span>
    </a>
    <a href="{{ route('admin.returns.index') }}" title="Retur Barang" class="sidebar-link flex items-center px-3 py-2.5 text-slate-700 rounded-lg font-medium hover:bg-emerald-50 {{ request()->routeIs('admin.returns.*') ? 'active' : '' }}">
        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.returns.*') ? 'text-emerald-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
        <span x-show="!desktopSidebarCollapsed" class="ml-3 whitespace-nowrap">Retur Barang</span>
    </a>
    <a href="{{ route('admin.users.index') }}" title="Pelanggan & User" class="sidebar-link flex items-center px-3 py-2.5 text-slate-700 rounded-lg font-medium hover:bg-emerald-50 {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.users.*') ? 'text-emerald-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        <span x-show="!desktopSidebarCollapsed" class="ml-3 whitespace-nowrap">Pelanggan & User</span>
    </a>
    <a href="{{ route('admin.audit-logs.index') }}" title="Audit Log" class="sidebar-link flex items-center px-3 py-2.5 text-slate-700 rounded-lg font-medium hover:bg-emerald-50 {{ request()->routeIs('admin.audit-logs.*') ? 'active' : '' }}">
        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.audit-logs.*') ? 'text-emerald-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        <span x-show="!desktopSidebarCollapsed" class="ml-3 whitespace-nowrap">Audit Log</span>
    </a>
@elseif(auth()->user()->role === 'Gudang')
    <a href="{{ route('home') }}" class="sidebar-link flex items-center px-3 py-2.5 text-slate-700 rounded-lg font-medium hover:bg-emerald-50 {{ request()->routeIs('home') ? 'active' : '' }}">
        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('home') ? 'text-emerald-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
        <span x-show="!desktopSidebarCollapsed" class="ml-3 whitespace-nowrap">Lihat Toko</span>
    </a>

    <div x-show="!desktopSidebarCollapsed" class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 mt-5 px-3 whitespace-nowrap transition-opacity duration-300">Modul Gudang</div>
    <div x-show="desktopSidebarCollapsed" style="display: none;" class="w-full border-t border-slate-200 my-4"></div>

    <a href="{{ route('gudang.stocks.index') }}" title="Stok & Inventaris" class="sidebar-link flex items-center px-3 py-2.5 text-slate-700 rounded-lg font-medium hover:bg-emerald-50 {{ request()->routeIs('gudang.stocks.*') ? 'active' : '' }}">
        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('gudang.stocks.*') ? 'text-emerald-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
        <span x-show="!desktopSidebarCollapsed" class="ml-3 whitespace-nowrap">Stok & Inventaris</span>
    </a>
    <a href="{{ route('gudang.orders.index') }}" title="Pesanan (Pengiriman)" class="sidebar-link flex items-center px-3 py-2.5 text-slate-700 rounded-lg font-medium hover:bg-emerald-50 {{ request()->routeIs('gudang.orders.*') ? 'active' : '' }}">
        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('gudang.orders.*') ? 'text-emerald-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
        <span x-show="!desktopSidebarCollapsed" class="ml-3 whitespace-nowrap">Pesanan (Pengiriman)</span>
    </a>
    <a href="{{ route('gudang.returns.index') }}" title="Terima Retur Fisik" class="sidebar-link flex items-center px-3 py-2.5 text-slate-700 rounded-lg font-medium hover:bg-emerald-50 {{ request()->routeIs('gudang.returns.*') ? 'active' : '' }}">
        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('gudang.returns.*') ? 'text-emerald-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
        <span x-show="!desktopSidebarCollapsed" class="ml-3 whitespace-nowrap">Terima Retur Fisik</span>
    </a>
@elseif(auth()->user()->role === 'Manager')
    <a href="{{ route('home') }}" class="sidebar-link flex items-center px-3 py-2.5 text-slate-700 rounded-lg font-medium hover:bg-emerald-50 {{ request()->routeIs('home') ? 'active' : '' }}">
        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('home') ? 'text-emerald-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
        <span x-show="!desktopSidebarCollapsed" class="ml-3 whitespace-nowrap">Lihat Toko</span>
    </a>

    <div x-show="!desktopSidebarCollapsed" class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 mt-5 px-3 whitespace-nowrap transition-opacity duration-300">Modul Eksekutif</div>
    <div x-show="desktopSidebarCollapsed" style="display: none;" class="w-full border-t border-slate-200 my-4"></div>

    <a href="{{ route('manager.dashboard') }}" title="Dashboard Eksekutif" class="sidebar-link flex items-center px-3 py-2.5 text-slate-700 rounded-lg font-medium hover:bg-emerald-50 {{ request()->routeIs('manager.dashboard') ? 'active' : '' }}">
        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('manager.dashboard') ? 'text-emerald-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
        <span x-show="!desktopSidebarCollapsed" class="ml-3 whitespace-nowrap">Dashboard Eksekutif</span>
    </a>
    <a href="{{ route('manager.reports') }}" title="Laporan Keuangan" class="sidebar-link flex items-center px-3 py-2.5 text-slate-700 rounded-lg font-medium hover:bg-emerald-50 {{ request()->routeIs('manager.reports') ? 'active' : '' }}">
        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('manager.reports') ? 'text-emerald-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
        <span x-show="!desktopSidebarCollapsed" class="ml-3 whitespace-nowrap">Laporan Keuangan</span>
    </a>
    <a href="{{ route('manager.subscriptions.index') }}" title="Langganan Laporan" class="sidebar-link flex items-center px-3 py-2.5 text-slate-700 rounded-lg font-medium hover:bg-emerald-50 {{ request()->routeIs('manager.subscriptions.*') ? 'active' : '' }}">
        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('manager.subscriptions.*') ? 'text-emerald-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
        <span x-show="!desktopSidebarCollapsed" class="ml-3 whitespace-nowrap">Langganan Laporan</span>
    </a>
@endif
