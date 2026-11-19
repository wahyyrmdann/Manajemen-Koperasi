<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 space-y-6">

        <!-- Header / Pusat Kendali Logistik -->
        <div class="rounded-2xl p-6 sm:p-7 text-white bg-gradient-to-r from-[#0a1a36] via-[#0f2854] to-[#1e3a8a] relative overflow-hidden border border-blue-900/60 shadow-lg">
            <div class="absolute -top-12 -right-12 w-64 h-64 bg-amber-400/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-5">
                <div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-blue-950/80 text-amber-300 border border-blue-800/80 mb-2.5 shadow-xs">
                        <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span>PUSAT KENDALI LOGISTIK KOPERASI</span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                        Selamat Datang, {{ Auth::user()->name }}
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-2xl leading-relaxed">
                        Ringkasan aktivitas inventaris dan transaksi gudang koperasi secara real-time.
                    </p>
                </div>

                <!-- Tombol Aksi Cepat & Status -->
                <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                    <a href="{{ route('incoming.index') }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-amber-500 hover:bg-amber-600 active:bg-amber-700 text-blue-950 font-bold text-xs rounded-xl shadow-sm hover:shadow transition uppercase tracking-wider">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Barang Masuk</span>
                    </a>

                    <a href="{{ route('outgoing.index') }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-blue-700 hover:bg-blue-600 active:bg-blue-800 text-white font-bold text-xs rounded-xl shadow-sm hover:shadow transition border border-blue-500/50 uppercase tracking-wider">
                        <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Barang Keluar</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 4 Kartu Ringkasan Metrik (Backend Integrated) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">

            <!-- Card 1: Total Jenis Barang -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex items-center justify-between hover:shadow-md transition">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Jenis Barang</p>
                    <p class="text-2xl sm:text-3xl font-bold text-slate-900 font-mono mt-1">
                        {{ number_format($totalProductTypes) }}
                    </p>
                    <p class="text-xs text-slate-400 mt-1">Item produk terdaftar</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 text-blue-700 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>

            <!-- Card 2: Total Stok -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex items-center justify-between hover:shadow-md transition">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Stok</p>
                    <p class="text-2xl sm:text-3xl font-bold text-slate-900 font-mono mt-1">
                        {{ number_format($totalStock) }}
                    </p>
                    <p class="text-xs text-slate-400 mt-1">Total kuantitas gudang</p>
                </div>
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>

            <!-- Card 3: Barang Masuk Draft (Klik ke daftar draft) -->
            <a href="{{ route('incoming.index', ['status' => 'draft']) }}"
               class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex items-center justify-between hover:border-amber-300 hover:shadow-md transition group">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Barang Masuk Draft</p>
                    <p class="text-2xl sm:text-3xl font-bold text-amber-600 font-mono mt-1">
                        {{ number_format($draftIncomingCount) }}
                    </p>
                    <p class="text-xs text-slate-400 mt-1 group-hover:text-amber-600 transition flex items-center gap-1 font-medium">
                        <span>Lihat daftar draft</span>
                        <span>→</span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-amber-100 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18" />
                    </svg>
                </div>
            </a>

            <!-- Card 4: Barang Keluar Draft (Klik ke daftar draft) -->
            <a href="{{ route('outgoing.index', ['status' => 'draft']) }}"
               class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex items-center justify-between hover:border-blue-300 hover:shadow-md transition group">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Barang Keluar Draft</p>
                    <p class="text-2xl sm:text-3xl font-bold text-blue-700 font-mono mt-1">
                        {{ number_format($draftOutgoingCount) }}
                    </p>
                    <p class="text-xs text-slate-400 mt-1 group-hover:text-blue-700 transition flex items-center gap-1 font-medium">
                        <span>Lihat daftar draft</span>
                        <span>→</span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-50 text-blue-700 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-blue-100 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </div>
            </a>
        </div>

        <!-- Section: Alur Operasional Utama (Elemen 3, 4, 5, 6 Tabel 5.2.2) -->
        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 tracking-tight">Alur Operasional Utama</h2>
                    <p class="text-xs text-slate-500">Pilar tata kelola pergerakan barang logistik Koperasi Desa Merah Putih</p>
                </div>
                <div class="text-[11px] font-semibold text-blue-900 flex items-center gap-1.5 bg-blue-50 border border-blue-200/60 px-3 py-1 rounded-lg w-fit">
                    <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Terintegrasi Database Sistem</span>
                </div>
            </div>

            <!-- Two Interactive Operational Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <!-- Elemen 3: Kartu Penerimaan (Barang Masuk) -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm relative overflow-hidden flex flex-col justify-between hover:shadow-md transition group">
                    <div>
                        <!-- Header with Tag & Elemen 4: Tombol jumlah Siap (Penerimaan) -->
                        <div class="flex items-center justify-between gap-2 mb-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-blue-50 text-blue-900 border border-blue-200 uppercase tracking-wider">
                                MODUL INBOUND
                            </span>

                            <!-- Elemen 4: Tombol jumlah Siap (Penerimaan) -->
                            <a href="{{ route('incoming.index', ['status' => 'draft']) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300 hover:bg-amber-200 transition shadow-xs cursor-pointer group-hover:scale-102"
                               title="Klik untuk membuka daftar penerimaan berstatus Siap/Draft">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-600 {{ $draftIncomingCount > 0 ? 'animate-pulse' : '' }}"></span>
                                <span>{{ $draftIncomingCount }} Berstatus Siap / Draft</span>
                            </a>
                        </div>

                        <!-- Card Body -->
                        <div class="flex items-start gap-3.5">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-700 to-[#0c1e3d] text-amber-300 flex items-center justify-center shrink-0 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Penerimaan (Barang Masuk)</h3>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                    Ringkasan aktivitas barang masuk koperasi. Kelola penerimaan pasokan, vendor mitra/supplier, verifikasi kuantitas, dan pencatatan transaksi masuk (UI-003).
                                </p>
                            </div>
                        </div>

                        <!-- 3 Sub-Stats dari Database -->
                        <div class="grid grid-cols-3 gap-2.5 py-4 mt-4 border-y border-slate-100 text-center">
                            <div class="bg-slate-50/80 rounded-xl p-2.5">
                                <p class="text-[10px] text-slate-400 font-semibold uppercase">Mitra Supplier</p>
                                <p class="text-sm font-bold text-slate-800 mt-0.5">{{ $supplierCount }} Supplier</p>
                            </div>
                            <div class="bg-slate-50/80 rounded-xl p-2.5">
                                <p class="text-[10px] text-slate-400 font-semibold uppercase">Total Masuk</p>
                                <p class="text-sm font-bold text-slate-800 mt-0.5">{{ $totalIncomingCount }} Transaksi</p>
                            </div>
                            <div class="bg-slate-50/80 rounded-xl p-2.5">
                                <p class="text-[10px] text-slate-400 font-semibold uppercase">Telah Selesai</p>
                                <p class="text-sm font-bold text-emerald-600 mt-0.5">{{ $doneIncomingCount }} Selesai</p>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Action Button to UI-003 -->
                    <div class="mt-5 pt-1 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <span class="text-[11px] text-slate-400 font-medium">Akses Dokumen Penerimaan Barang (LPB)</span>
                        <a href="{{ route('incoming.index') }}"
                           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-[#0c1e3d] hover:bg-[#132d5e] active:bg-[#08152b] text-amber-300 font-semibold text-xs shadow-md shadow-blue-950/20 transition group">
                            <span>Buka Daftar Penerimaan (UI-003)</span>
                            <svg class="w-4 h-4 text-amber-400 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Elemen 5: Kartu Order Pengiriman (Barang Keluar) -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm relative overflow-hidden flex flex-col justify-between hover:shadow-md transition group">
                    <div>
                        <!-- Header with Tag & Elemen 6: Tombol jumlah Siap (Order Pengiriman) -->
                        <div class="flex items-center justify-between gap-2 mb-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200 uppercase tracking-wider">
                                MODUL OUTBOUND
                            </span>

                            <!-- Elemen 6: Tombol jumlah Siap (Order Pengiriman) -->
                            <a href="{{ route('outgoing.index', ['status' => 'draft']) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-900 border border-blue-300 hover:bg-blue-200 transition shadow-xs cursor-pointer group-hover:scale-102"
                               title="Klik untuk membuka order pengiriman berstatus Siap/Draft">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-600 {{ $draftOutgoingCount > 0 ? 'animate-pulse' : '' }}"></span>
                                <span>{{ $draftOutgoingCount }} Berstatus Siap / Draft</span>
                            </a>
                        </div>

                        <!-- Card Body -->
                        <div class="flex items-start gap-3.5">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-900 to-[#0c1e3d] text-amber-300 flex items-center justify-center shrink-0 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Order Pengiriman (Barang Keluar)</h3>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                    Ringkasan aktivitas barang keluar koperasi. Pemrosesan alur pengiriman, pesanan anggota/cabang, dan verifikasi pelepasan barang gudang (UI-005).
                                </p>
                            </div>
                        </div>

                        <!-- 3 Sub-Stats dari Database -->
                        <div class="grid grid-cols-3 gap-2.5 py-4 mt-4 border-y border-slate-100 text-center">
                            <div class="bg-slate-50/80 rounded-xl p-2.5">
                                <p class="text-[10px] text-slate-400 font-semibold uppercase">Mitra Anggota</p>
                                <p class="text-sm font-bold text-slate-800 mt-0.5">{{ $memberCount }} Anggota</p>
                            </div>
                            <div class="bg-slate-50/80 rounded-xl p-2.5">
                                <p class="text-[10px] text-slate-400 font-semibold uppercase">Total Keluar</p>
                                <p class="text-sm font-bold text-slate-800 mt-0.5">{{ $totalOutgoingCount }} Transaksi</p>
                            </div>
                            <div class="bg-slate-50/80 rounded-xl p-2.5">
                                <p class="text-[10px] text-slate-400 font-semibold uppercase">Telah Selesai</p>
                                <p class="text-sm font-bold text-emerald-600 mt-0.5">{{ $doneOutgoingCount }} Selesai</p>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Action Button to UI-005 -->
                    <div class="mt-5 pt-1 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <span class="text-[11px] text-slate-400 font-medium">Akses Surat Perintah Muat (SPM)</span>
                        <a href="{{ route('outgoing.index') }}"
                           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-[#0c1e3d] hover:bg-[#132d5e] active:bg-[#08152b] text-amber-300 font-semibold text-xs shadow-md shadow-blue-950/20 transition group">
                            <span>Buka Daftar Pengiriman (UI-005)</span>
                            <svg class="w-4 h-4 text-amber-400 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <!-- Tabel Transaksi Terbaru (Backend Real Data) -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-200/80 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-slate-50/60">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Transaksi Terbaru</h3>
                    <p class="text-xs text-slate-500 mt-0.5">10 transaksi terakhir dari database (gabungan barang masuk dan keluar).</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs sm:text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold text-slate-600 uppercase tracking-wider">
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
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($recentTransactions as $tx)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3 px-4 font-mono font-semibold text-blue-900">
                                    {{ $tx->reference }}
                                </td>
                                <td class="py-3 px-4">
                                    @if ($tx->type->value === 'in')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                            Masuk
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                            Keluar
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 font-medium text-slate-800">
                                    {{ $tx->partner->name ?? '-' }}
                                </td>
                                <td class="py-3 px-4 text-slate-600 whitespace-nowrap">
                                    {{ $tx->transaction_date ? $tx->transaction_date->format('d/m/Y') : '-' }}
                                </td>
                                <td class="py-3 px-4 text-center font-mono text-slate-600">
                                    {{ $tx->items_count ?? 0 }}
                                </td>
                                <td class="py-3 px-4 text-right font-mono font-semibold text-slate-800">
                                    {{ number_format($tx->items_sum_quantity ?? 0) }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if ($tx->status->value === 'draft')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-300">
                                            Draft
                                        </span>
                                    @elseif ($tx->status->value === 'done')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                            Selesai
                                        </span>
                                    @elseif ($tx->status->value === 'cancelled')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-800 border border-rose-300">
                                            Dibatalkan
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if ($tx->type->value === 'in')
                                        <a href="{{ route('incoming.index', ['search' => $tx->reference]) }}" class="inline-flex items-center px-3 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-xs font-semibold text-blue-900 transition">
                                            Detail
                                        </a>
                                    @else
                                        <a href="{{ route('outgoing.index', ['search' => $tx->reference]) }}" class="inline-flex items-center px-3 py-1 rounded-lg bg-amber-50 hover:bg-amber-100 text-xs font-semibold text-amber-900 transition">
                                            Detail
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="h-10 w-10 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <span class="text-sm font-medium">Belum ada transaksi inventaris.</span>
                                        <span class="text-xs text-slate-400 mt-0.5">Catatan transaksi barang masuk dan keluar akan tampil di sini secara otomatis.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
