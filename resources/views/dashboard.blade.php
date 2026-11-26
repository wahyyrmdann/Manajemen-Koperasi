<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Dashboard') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Ringkasan aktivitas inventaris dan transaksi gudang koperasi</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        {{-- 4 Kartu Ringkasan --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            {{-- Card 1: Total Jenis Barang --}}
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Jenis Barang</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalProductTypes) }}</p>
                    <p class="text-xs text-gray-400 mt-1">Item produk terdaftar</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>

            {{-- Card 2: Total Stok --}}
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Stok</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalStock) }}</p>
                    <p class="text-xs text-gray-400 mt-1">Total kuantitas gudang</p>
                </div>
                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>

            {{-- Card 3: Barang Masuk Draft --}}
            <a href="{{ route('incoming.index', ['status' => 'draft']) }}"
                class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between hover:border-emerald-300 hover:shadow-md transition group">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Barang Masuk Draft</p>
                    <p class="text-2xl font-bold text-emerald-600 mt-1">{{ number_format($draftIncomingCount) }}</p>
                    <p class="text-xs text-gray-400 mt-1 group-hover:text-emerald-500 transition">Lihat daftar draft →</p>
                </div>
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center group-hover:bg-emerald-100 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18" />
                    </svg>
                </div>
            </a>

            {{-- Card 4: Barang Keluar Draft --}}
            <a href="{{ route('outgoing.index', ['status' => 'draft']) }}"
                class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between hover:border-amber-300 hover:shadow-md transition group">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Barang Keluar Draft</p>
                    <p class="text-2xl font-bold text-amber-600 mt-1">{{ number_format($draftOutgoingCount) }}</p>
                    <p class="text-xs text-gray-400 mt-1 group-hover:text-amber-500 transition">Lihat daftar draft →</p>
                </div>
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center group-hover:bg-amber-100 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </div>
            </a>
        </div>

        {{-- Tabel Transaksi Terbaru --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-gray-50/50">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Transaksi Terbaru</h3>
                    <p class="text-xs text-gray-500 mt-0.5">10 transaksi terakhir (gabungan barang masuk dan keluar).</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <th class="py-3.5 px-4">Referensi</th>
                            <th class="py-3.5 px-4">Tipe</th>
                            <th class="py-3.5 px-4">Mitra</th>
                            <th class="py-3.5 px-4">Tanggal</th>
                            <th class="py-3.5 px-4 text-center">Jumlah Item</th>
                            <th class="py-3.5 px-4 text-right">Total Qty</th>
                            <th class="py-3.5 px-4 text-center">Status</th>
                            <th class="py-3.5 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($recentTransactions as $tx)
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="py-3 px-4 font-mono font-medium text-gray-900">
                                    {{ $tx->reference }}
                                </td>
                                <td class="py-3 px-4">
                                    @if ($tx->type->value === 'in')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                            Masuk
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                            Keluar
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 font-medium text-gray-800">
                                    {{ $tx->partner->name ?? '-' }}
                                </td>
                                <td class="py-3 px-4 text-gray-600 whitespace-nowrap">
                                    {{ $tx->transaction_date ? $tx->transaction_date->format('d/m/Y') : '-' }}
                                </td>
                                <td class="py-3 px-4 text-center text-gray-600">
                                    {{ $tx->items_count ?? 0 }}
                                </td>
                                <td class="py-3 px-4 text-right font-semibold text-gray-800">
                                    {{ number_format($tx->items_sum_quantity ?? 0) }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if ($tx->status->value === 'draft')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-300">
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
                                    <a href="{{ $tx->type->value === 'in' ? route('incoming.show', $tx) : route('outgoing.show', $tx) }}"
                                       class="inline-flex items-center px-3 py-1 rounded bg-gray-100 hover:bg-gray-200 text-xs font-medium text-gray-700 transition">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-10 text-center text-gray-400">
                                    <svg class="mx-auto h-10 w-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    Belum ada transaksi inventaris.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
