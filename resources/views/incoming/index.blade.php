<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Daftar Barang Masuk
                </h2>
                <p class="text-sm text-gray-500 mt-1">Riwayat dan daftar transaksi penerimaan barang dari supplier.</p>
            </div>
            <a href="{{ route('incoming.create') }}"
                class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Baru
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 pb-10">

        {{-- Filter Bar --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 mb-5">
            <form method="GET" action="{{ route('incoming.index') }}" class="flex flex-col sm:flex-row gap-3 items-end">
                {{-- Pencarian --}}
                <div class="flex-1">
                    <label for="search" class="block text-xs font-medium text-gray-600 mb-1">Cari</label>
                    <input id="search" name="search" type="text"
                        value="{{ request('search') }}"
                        placeholder="Referensi, supplier, atau dokumen asal…"
                        class="w-full border border-gray-300 rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition" />
                </div>

                {{-- Filter Status --}}
                <div class="sm:w-44">
                    <label for="status" class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                    <select id="status" name="status"
                        class="w-full border border-gray-300 rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                        <option value="">Semua Status</option>
                        <option value="draft"      {{ request('status') === 'draft'     ? 'selected' : '' }}>Draft</option>
                        <option value="done"       {{ request('status') === 'done'      ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled"  {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>

                {{-- Filter Tanggal Mulai --}}
                <div class="sm:w-40">
                    <label for="start_date" class="block text-xs font-medium text-gray-600 mb-1">Dari Tanggal</label>
                    <input id="start_date" name="start_date" type="date"
                        value="{{ request('start_date') }}"
                        class="w-full border border-gray-300 rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition" />
                </div>

                {{-- Filter Tanggal Akhir --}}
                <div class="sm:w-40">
                    <label for="end_date" class="block text-xs font-medium text-gray-600 mb-1">Sampai Tanggal</label>
                    <input id="end_date" name="end_date" type="date"
                        value="{{ request('end_date') }}"
                        class="w-full border border-gray-300 rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition" />
                </div>

                {{-- Tombol --}}
                <div class="flex gap-2">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                        <svg class="w-4 h-4 me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
                        </svg>
                        Cari
                    </button>
                    @if(request()->hasAny(['search','status','start_date','end_date']))
                        <a href="{{ route('incoming.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 focus:outline-none transition">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Tabel Barang Masuk --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-200 flex items-center justify-between bg-gray-50/50">
                <div>
                    <h3 class="text-base font-bold text-gray-800">Transaksi Barang Masuk</h3>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Total {{ $transactions->total() }} transaksi ditemukan.
                    </p>
                </div>
                <span class="text-xs text-gray-400">Halaman {{ $transactions->currentPage() }} dari {{ $transactions->lastPage() }}</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <th class="py-3.5 px-4">Referensi</th>
                            <th class="py-3.5 px-4">Supplier</th>
                            <th class="py-3.5 px-4">Tanggal</th>
                            <th class="py-3.5 px-4">Dokumen Asal</th>
                            <th class="py-3.5 px-4 text-center">Jumlah Item</th>
                            <th class="py-3.5 px-4 text-right">Total Qty</th>
                            <th class="py-3.5 px-4 text-center">Status</th>
                            <th class="py-3.5 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($transactions as $tx)
                            <tr class="hover:bg-emerald-50/40 transition">
                                <td class="py-3 px-4 font-mono font-medium text-gray-900 whitespace-nowrap">
                                    {{ $tx->reference ?? '-' }}
                                </td>
                                <td class="py-3 px-4 font-medium text-gray-800">
                                    {{ $tx->partner->name ?? '-' }}
                                </td>
                                <td class="py-3 px-4 text-gray-600 whitespace-nowrap">
                                    {{ $tx->transaction_date ? $tx->transaction_date->format('d/m/Y') : '-' }}
                                </td>
                                <td class="py-3 px-4 text-gray-500 italic">
                                    {{ $tx->origin ?? '-' }}
                                </td>
                                <td class="py-3 px-4 text-center text-gray-700">
                                    {{ $tx->items_count ?? 0 }}
                                </td>
                                <td class="py-3 px-4 text-right font-semibold text-gray-800">
                                    {{ number_format($tx->items_sum_quantity ?? 0) }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if ($tx->status->value === 'draft')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 border border-gray-300">
                                            Draft
                                        </span>
                                    @elseif ($tx->status->value === 'done')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-300">
                                            Selesai
                                        </span>
                                    @elseif ($tx->status->value === 'cancelled')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800 border border-rose-300">
                                            Dibatalkan
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <a href="{{ route('incoming.show', $tx) }}"
                                        class="inline-flex items-center px-3 py-1 rounded-md bg-gray-100 hover:bg-gray-200 text-xs font-medium text-gray-700 transition">
                                        {{ $tx->status->value === 'draft' ? 'Edit' : 'Lihat' }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-14 text-center text-gray-400">
                                    <svg class="mx-auto h-10 w-10 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M7 16l-4-4m0 0l4-4m-4 4h18M13 5l7 7-7 7" />
                                    </svg>
                                    <p class="text-sm font-medium text-gray-500">Tidak ada transaksi barang masuk.</p>
                                    <p class="text-xs text-gray-400 mt-1">Coba ubah filter pencarian Anda.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($transactions->hasPages())
                <div class="px-5 py-4 border-t border-gray-200 bg-gray-50/50">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
