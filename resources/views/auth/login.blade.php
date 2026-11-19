<x-guest-layout>
    <div class="w-full max-w-[440px] flex flex-col items-center">
        <!-- Login Card -->
        <div class="w-full bg-white rounded-2xl shadow-xl shadow-slate-200/70 border border-slate-100 overflow-hidden relative">
            <!-- Top Yellow & Blue Accent Line -->
            <div class="h-1.5 bg-gradient-to-r from-[#0c1e3d] via-blue-700 to-amber-400 w-full"></div>

            <div class="p-6 sm:p-8">
                <!-- Elemen 1: Judul dan Identitas Aplikasi -->
                <div class="text-center">
                    <!-- Logo Koperasi (Gambar ke-2) -->
                    <div class="flex justify-center mb-3">
                        <div class="h-20 w-auto flex items-center justify-center p-1">
                            <img src="{{ asset('images/logo.png') }}"
                                 alt="Logo Koperasi Desa Merah Putih"
                                 class="h-full w-auto max-h-20 object-contain drop-shadow-xs"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                            />
                            <div class="hidden flex-col items-center justify-center">
                                <div class="w-12 h-12 rounded-xl bg-[#0c1e3d] text-amber-300 flex items-center justify-center font-bold text-lg shadow-sm">
                                    KP
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Koperasi Badge (Kuning Biru) -->
                    <div class="flex justify-center mb-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-900 border border-blue-200/80 shadow-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                            Koperasi Desa Merah Putih
                        </span>
                    </div>

                    <!-- Judul Sistem -->
                    <h1 class="text-xl sm:text-[22px] font-bold text-slate-800 tracking-tight">
                        Sistem Manajemen Inventaris
                    </h1>

                    <!-- Subtitle -->
                    <p class="text-xs text-slate-500 mt-1">
                        Koperasi Desa Merah Putih
                    </p>
                </div>

                <!-- Elemen 5: Area Pesan Kesalahan -->
                @if (isset($errors) && $errors->any())
                    <div class="mt-5 bg-red-50/90 border border-red-200/90 rounded-xl p-3.5 flex items-start gap-3 text-left transition-all" role="alert">
                        <div class="flex-shrink-0 text-red-500 mt-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke-width="2"></circle>
                                <line x1="12" y1="8" x2="12" y2="12" stroke-width="2" stroke-linecap="round"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16" stroke-width="2" stroke-linecap="round"></line>
                            </svg>
                        </div>
                        <div class="flex-1 text-xs">
                            <h4 class="font-bold text-red-800 text-sm">Gagal Masuk</h4>
                            <p class="text-red-600 mt-0.5 leading-relaxed font-normal">
                                @if ($errors->has('email'))
                                    {{ $errors->first('email') }}
                                @elseif ($errors->has('password'))
                                    {{ $errors->first('password') }}
                                @else
                                    Username atau password salah. Silakan periksa kembali kredensial Anda.
                                @endif
                            </p>
                        </div>
                    </div>
                @elseif (session('status'))
                    <div class="mt-5 bg-emerald-50 border border-emerald-200 rounded-xl p-3.5 flex items-start gap-3 text-left">
                        <div class="flex-shrink-0 text-emerald-600 mt-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="text-xs text-emerald-700">
                            {{ session('status') }}
                        </div>
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="mt-5 space-y-4">
                    @csrf

                    <!-- Elemen 2: Kolom Username / Email -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Username / Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <rect x="3" y="4" width="18" height="16" rx="2" stroke-width="2"></rect>
                                    <circle cx="9" cy="10" r="2" stroke-width="2"></circle>
                                    <line x1="15" y1="8" x2="17" y2="8" stroke-width="2" stroke-linecap="round"></line>
                                    <line x1="15" y1="12" x2="17" y2="12" stroke-width="2" stroke-linecap="round"></line>
                                    <line x1="7" y1="16" x2="17" y2="16" stroke-width="2" stroke-linecap="round"></line>
                                </svg>
                            </div>
                            <input
                                id="email"
                                type="text"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="Masukkan username atau alamat email"
                                class="w-full pl-10 pr-3.5 py-2.5 bg-slate-50/70 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-700/20 focus:border-blue-700 transition duration-150"
                            />
                        </div>
                    </div>

                    <!-- Elemen 3: Kolom Password -->
                    <div x-data="{ showPassword: false }">
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-semibold text-slate-700">
                                Password
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-medium text-blue-700 hover:text-amber-600 hover:underline transition">
                                    Lupa Password?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <rect x="5" y="11" width="14" height="10" rx="2" stroke-width="2"></rect>
                                    <path d="M8 11V7a4 4 0 018 0v4" stroke-width="2"></path>
                                </svg>
                            </div>
                            <input
                                id="password"
                                :type="showPassword ? 'text' : 'password'"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••••••"
                                class="w-full pl-10 pr-10 py-2.5 bg-slate-50/70 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-700/20 focus:border-blue-700 transition duration-150"
                            />
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer"
                                tabindex="-1"
                                aria-label="Toggle password visibility"
                            >
                                <!-- Eye Open Icon -->
                                <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <!-- Eye Closed Icon -->
                                <svg x-show="showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="flex items-center pt-0.5">
                        <input
                            id="remember_me"
                            type="checkbox"
                            name="remember"
                            class="w-4 h-4 rounded border-slate-300 text-blue-700 focus:ring-blue-700 focus:ring-offset-0 cursor-pointer"
                        />
                        <label for="remember_me" class="ms-2.5 text-xs text-slate-600 select-none cursor-pointer font-normal">
                            Ingat Saya di Perangkat Ini
                        </label>
                    </div>

                    <!-- Elemen 4: Tombol Masuk -->
                    <div class="pt-2">
                        <button
                            type="submit"
                            class="w-full py-2.5 sm:py-3 px-4 bg-[#0c1e3d] hover:bg-[#132d5e] active:bg-[#08152b] text-amber-300 font-bold text-sm rounded-xl shadow-md shadow-blue-950/20 transition duration-150 flex items-center justify-center gap-2 group cursor-pointer border border-blue-900/60"
                        >
                            <span>Masuk</span>
                            <svg class="w-4 h-4 text-amber-400 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
