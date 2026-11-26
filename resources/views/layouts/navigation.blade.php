<nav x-data="{ open: false, userOpen: false }" class="bg-[#0c1e3d] border-b border-blue-900/60 text-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left: Logo & Menu Navigasi -->
            <div class="flex items-center gap-6 lg:gap-8">
                <!-- Brand / Logo -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group focus:outline-none">
                    <div class="w-10 h-10 rounded-xl bg-white p-1 flex items-center justify-center shadow-sm overflow-hidden shrink-0 group-hover:scale-105 transition-transform">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <svg class="w-6 h-6 text-blue-700 hidden" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                    </div>
                    <div class="flex flex-col text-left">
                        <span class="font-bold text-sm sm:text-base text-white tracking-tight leading-tight group-hover:text-amber-300 transition-colors">
                            Koperasi Desa Merah Putih
                        </span>
                        <span class="text-[11px] text-amber-400 font-medium tracking-wide">
                            Sistem Inventaris
                        </span>
                    </div>
                </a>

                <!-- Menu Navigasi Utama (Desktop) -->
                <div class="hidden md:flex items-center space-x-1 lg:space-x-2">
                    <a href="{{ route('dashboard') }}"
                       class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-blue-900/90 text-amber-300 border border-blue-700/60 shadow-inner' : 'text-slate-300 hover:text-white hover:bg-blue-900/40' }}">
                        Dashboard
                    </a>

                    <a href="{{ route('incoming.index') }}"
                       class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('incoming.*') ? 'bg-blue-900/90 text-amber-300 border border-blue-700/60 shadow-inner' : 'text-slate-300 hover:text-white hover:bg-blue-900/40' }}">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span>Barang Masuk</span>
                    </a>

                    <a href="{{ route('outgoing.index') }}"
                       class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('outgoing.*') ? 'bg-blue-900/90 text-amber-300 border border-blue-700/60 shadow-inner' : 'text-slate-300 hover:text-white hover:bg-blue-900/40' }}">
                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                        <span>Barang Keluar</span>
                    </a>
                </div>
            </div>

            <!-- Right: Tanggal & Menu Pengguna -->
            <div class="flex items-center gap-3 sm:gap-4">
                <!-- Tanggal Aktif -->
                <div class="hidden lg:flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-950/60 border border-blue-800/40 text-xs text-slate-300 font-medium">
                    <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>{{ \Carbon\Carbon::now()->translatedFormat('l, d M Y') }}</span>
                </div>

                <!-- Menu Pengguna -->
                <div class="relative" @click.away="userOpen = false">
                    <button @click="userOpen = !userOpen" type="button"
                            class="flex items-center gap-2.5 p-1 sm:px-2.5 sm:py-1 rounded-xl hover:bg-blue-900/60 border border-transparent hover:border-blue-700/50 transition focus:outline-none group">
                        <!-- User Avatar -->
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 text-blue-950 font-bold text-xs flex items-center justify-center shadow-sm shrink-0 border border-amber-300">
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                        </div>

                        <!-- Name & Role -->
                        <div class="hidden sm:flex flex-col text-left">
                            <span class="text-xs font-semibold text-white group-hover:text-amber-300 transition-colors leading-tight">
                                {{ Auth::user()->name }}
                            </span>
                            <span class="text-[10px] text-slate-300 font-normal">
                                {{ Auth::user()->isAdmin() ? 'Admin Gudang' : 'Karyawan Logistik' }}
                            </span>
                        </div>

                        <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-amber-300 transition-transform duration-200" :class="{ 'rotate-180': userOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown Content -->
                    <div x-show="userOpen"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-56 rounded-xl bg-white shadow-xl border border-slate-100 py-1.5 z-50 text-slate-800"
                         style="display: none;">
                        <div class="px-4 py-2 border-b border-slate-100">
                            <p class="text-xs font-bold text-slate-800">{{ Auth::user()->name }}</p>
                            <p class="text-[11px] text-slate-500 truncate">{{ Auth::user()->email }}</p>
                            <span class="mt-1 inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-100 text-amber-900 border border-amber-200">
                                {{ Auth::user()->isAdmin() ? 'Hak Akses: Admin' : 'Hak Akses: Karyawan' }}
                            </span>
                        </div>

                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-xs text-slate-700 hover:bg-blue-50 hover:text-blue-900 transition">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>Profil & Akun</span>
                        </a>

                        <div class="border-t border-slate-100 my-1"></div>

                        <!-- Opsi Keluar -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition cursor-pointer">
                                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                <span>Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Mobile Hamburger Button -->
                <button @click="open = !open" class="md:hidden p-2 rounded-lg text-slate-300 hover:text-white hover:bg-blue-900/50 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div x-show="open" class="md:hidden border-t border-blue-900/60 bg-[#0c1e3d] px-4 pt-3 pb-4 space-y-1.5" style="display: none;">
        <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg text-xs font-semibold {{ request()->routeIs('dashboard') ? 'bg-blue-900 text-amber-300' : 'text-slate-300 hover:bg-blue-900/50' }}">
            Dashboard
        </a>
        <a href="{{ route('incoming.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold {{ request()->routeIs('incoming.*') ? 'bg-blue-900 text-amber-300' : 'text-slate-300 hover:bg-blue-900/50' }}">
            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
            <span>Barang Masuk</span>
        </a>
        <a href="{{ route('outgoing.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold {{ request()->routeIs('outgoing.*') ? 'bg-blue-900 text-amber-300' : 'text-slate-300 hover:bg-blue-900/50' }}">
            <span class="w-2 h-2 rounded-full bg-amber-400"></span>
            <span>Barang Keluar</span>
        </a>
        <div class="border-t border-blue-900/80 pt-2 mt-2">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 text-xs font-semibold text-rose-400 hover:bg-blue-900/50 rounded-lg">
                    Keluar
                </button>
            </form>
        </div>
    </div>
</nav>
