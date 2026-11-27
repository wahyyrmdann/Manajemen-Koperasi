<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Daftar Stok Barang
                </h2>
                <p class="text-sm text-gray-500 mt-1">Kuantitas stok setiap barang di gudang koperasi beserta transaksi draft yang menunggu.</p>
            </div>
            <div class="text-right">
                <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Total Kuantitas Gudang</p>
                <p class="text-2xl font-bold text-amber-600 font-mono">{{ number_format($totalStock) }}</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 pb-10">

        {{-- Filter Bar --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 mb-5">
            <form method="GET" action="{{ route('stock.index') }}" class="flex flex-col sm:flex-row gap-3 items-end">
                {{-- Pencarian --}}
                <div class="flex-1">
                    <label for="search" class="block text-xs font-medium text-gray-600 mb-1">Cari</label>
                    <input id="search" name="search" type="text"
                        value="{{ request('search') }}"
                        placeholder="Kode atau nama barang…"
                        class="w-full border border-gray-300 rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition" />
                </div>

                {{-- Filter Status Stok --}}
                <div class="sm:w-44">
                    <label for="stock_status" class="block text-xs font-medium text-gray-600 mb-1">Status Stok</label>
                    <select id="stock_status" name="stock_status"
                        class="w-full border border-gray-300 rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                        <option value="">Semua Status</option>
                        <option value="available" {{ request('stock_status') === 'available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="low"       {{ request('stock_status') === 'low'       ? 'selected' : '' }}>Menipis</option>
                        <option value="empty"     {{ request('stock_status') === 'empty'     ? 'selected' : '' }}>Habis</option>
                    </select>
                </div>

                {{-- Tombol --}}
                <div class="flex gap-2">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-amber-600 text-white text-sm font-semibold rounded-lg hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 transition">
                        <svg class="w-4 h-4 me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
                        </svg>
                        Cari
                    </button>
                    @if(request()->hasAny(['search','stock_status']))
                        <a href="{{ route('stock.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 focus:outline-none transition">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Tabel Stok Barang --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-200 flex items-center justify-between bg-gray-50/50">
                <div>
                    <h3 class="text-base font-bold text-gray-800">Stok Barang Gudang</h3>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Total {{ $products->total() }} jenis barang ditemukan.
                    </p>
                </div>
                <span class="text-xs text-gray-400">Halaman {{ $products->currentPage() }} dari {{ $products->lastPage() }}</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <th class="py-3.5 px-4">Kode</th>
                            <th class="py-3.5 px-4">Nama Barang</th>
                            <th class="py-3.5 px-4">Satuan</th>
                            <th class="py-3.5 px-4 text-right">Stok Saat Ini</th>
                            <th class="py-3.5 px-4 text-right">Masuk (Draft)</th>
                            <th class="py-3.5 px-4 text-right">Keluar (Draft)</th>
                            <th class="py-3.5 px-4 text-center">Status</th>
                            <th class="py-3.5 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($products as $product)
                            <tr class="hover:bg-amber-50/40 transition">
                                <td class="py-3 px-4 font-mono font-medium text-gray-900 whitespace-nowrap">
                                    {{ $product->code }}
                                </td>
                                <td class="py-3 px-4 font-medium text-gray-800">
                                    {{ $product->name }}
                                </td>
                                <td class="py-3 px-4 text-gray-500 italic">
                                    {{ $product->unit }}
                                </td>
                                <td class="py-3 px-4 text-right font-semibold text-gray-800">
                                    {{ number_format($product->stock) }}
                                </td>
                                <td class="py-3 px-4 text-right text-gray-600">
                                    {{ number_format($product->incoming_draft_qty ?? 0) }}
                                </td>
                                <td class="py-3 px-4 text-right text-gray-600">
                                    {{ number_format($product->outgoing_draft_qty ?? 0) }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if ($product->stock_status === 'available')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-300">
                                            Tersedia
                                        </span>
                                    @elseif ($product->stock_status === 'low')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-300">
                                            Menipis
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800 border border-rose-300">
                                            Habis
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    {{-- Tombol aksi: placeholder, form belum aktif --}}
                                    <button type="button"
                                        title="Formulir detail belum diaktifkan"
                                        class="inline-flex items-center px-3 py-1 rounded-md bg-gray-100 hover:bg-gray-200 text-xs font-medium text-gray-600 transition cursor-pointer">
                                        Lihat
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-14 text-center text-gray-400">
                                    <svg class="mx-auto h-10 w-10 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p class="text-sm font-medium text-gray-500">Tidak ada data stok barang.</p>
                                    <p class="text-xs text-gray-400 mt-1">Coba ubah filter pencarian Anda.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($products->hasPages())
                <div class="px-5 py-4 border-t border-gray-200 bg-gray-50/50">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>