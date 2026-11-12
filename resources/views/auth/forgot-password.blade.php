<x-guest-layout>
    <div class="w-full max-w-[450px]">
        <div class="w-full bg-white rounded-2xl shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden relative p-6 sm:p-8">
            <div class="h-1.5 bg-red-600 w-full absolute top-0 left-0"></div>

            <div class="text-center mb-5">
                <h2 class="text-lg font-bold text-slate-800">Lupa Password</h2>
                <p class="text-xs text-slate-500 mt-1">
                    Koperasi Desa Merah Putih
                </p>
                <p class="text-xs text-slate-400 mt-1">
                    Masukkan alamat email Anda untuk menerima tautan reset password.
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">Email</label>
                    <input id="email" class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-600/20 focus:border-red-600 transition duration-150" type="email" name="email" value="{{ old('email') }}" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="pt-2 flex items-center justify-between">
                    <a href="{{ route('login') }}" class="text-xs font-medium text-slate-500 hover:text-red-600 transition">
                        ← Kembali ke login
                    </a>
                    <button type="submit" class="py-2.5 px-4 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-xl transition shadow-sm">
                        Kirim Tautan Reset
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
