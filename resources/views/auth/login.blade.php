<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-2xl lg:text-3xl font-black text-slate-800 tracking-tight">Selamat Datang Kembali</h2>
        <p class="text-slate-500 mt-2 font-medium">Masuk untuk melanjutkan ke sistem Tanjung Jaya.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Email Address</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400 group-focus-within:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="block w-full pl-10 pr-4 py-3.5 border border-slate-200 rounded-2xl bg-slate-50/50 hover:bg-slate-50 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 sm:text-sm transition-all text-slate-900 font-bold shadow-sm" placeholder="nama@email.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-2">
                <label for="password" class="block text-sm font-bold text-slate-700">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm font-bold text-emerald-600 hover:text-emerald-500 transition-colors">
                        Lupa password?
                    </a>
                @endif
            </div>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400 group-focus-within:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password" class="block w-full pl-10 pr-4 py-3.5 border border-slate-200 rounded-2xl bg-slate-50/50 hover:bg-slate-50 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 sm:text-sm transition-all text-slate-900 font-bold shadow-sm" placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-slate-300 rounded transition-colors cursor-pointer">
            <label for="remember_me" class="ml-2 block text-sm font-medium text-slate-600 cursor-pointer">
                Ingat saya
            </label>
        </div>

        <div>
            <button type="submit" class="w-full flex justify-center py-4 px-4 border border-emerald-400/30 rounded-2xl shadow-[0_8px_20px_-6px_rgba(16,185,129,0.5)] text-sm font-black text-white bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 focus:outline-none focus:ring-4 focus:ring-emerald-500/20 transform transition-all hover:-translate-y-1 hover:shadow-[0_12px_25px_-6px_rgba(16,185,129,0.6)]">
                Masuk
            </button>
        </div>
    </form>

    <div class="mt-8 text-center text-sm font-medium text-slate-600">
        Belum punya akun? 
        <a href="{{ route('register') }}" class="font-bold text-emerald-600 hover:text-emerald-500 transition-colors">Daftar sekarang</a>
    </div>
</x-guest-layout>
