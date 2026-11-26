@php
    $isNew = is_null($transaction);
    $isEditable = $isNew || $transaction->status->value === 'draft';
    $statusValue = $transaction ? $transaction->status->value : 'draft';

    $productsMap = $products->mapWithKeys(fn($p) => [
        $p->id => [
            'id'    => $p->id,
            'code'  => $p->code,
            'name'  => $p->name,
            'unit'  => $p->unit,
            'stock' => $p->stock,
        ]
    ]);

    $initialItems = old('items');
    if (is_null($initialItems)) {
        if ($transaction && $transaction->items->isNotEmpty()) {
            $initialItems = $transaction->items->map(fn($item) => [
                'product_id' => $item->product_id,
                'quantity'   => $item->quantity,
            ])->values()->toArray();
        } else {
            $initialItems = [['product_id' => '', 'quantity' => 1]];
        }
    }
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                        @if($isNew)
                            Buat Barang Masuk
                        @elseif($isEditable)
                            Edit Barang Masuk
                        @else
                            Detail Barang Masuk
                        @endif
                    </h2>
                    @if($statusValue === 'draft')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 border border-gray-300">
                            Draft
                        </span>
                    @elseif($statusValue === 'done')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-300">
                            Selesai
                        </span>
                    @elseif($statusValue === 'cancelled')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800 border border-rose-300">
                            Dibatalkan
                        </span>
                    @endif
                </div>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $transaction ? $transaction->reference : 'Referensi akan dibuat otomatis setelah disimpan.' }}
                </p>
            </div>
            <a href="{{ route('incoming.index') }}"
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition shadow-sm">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 pb-12"
         x-data="incomingForm({{ Js::from($productsMap) }}, {{ Js::from($initialItems) }})">

        <form method="POST"
              action="{{ $isNew ? route('incoming.store') : route('incoming.update', $transaction) }}"
              id="main-form"
              @submit="return validateForm()">
            @csrf
            @if(!$isNew)
                @method('PUT')
            @endif

            {{-- Bagian Header --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">
                <h3 class="text-base font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100">Informasi Transaksi</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Referensi --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Referensi</label>
                        <input type="text" readonly
                               value="{{ $transaction ? $transaction->reference : '(Otomatis)' }}"
                               class="w-full bg-gray-50 border border-gray-200 rounded-lg text-sm px-3 py-2 text-gray-700 font-mono" />
                    </div>

                    {{-- Tanggal --}}
                    <div>
                        <label for="transaction_date" class="block text-xs font-medium text-gray-600 mb-1">
                            Tanggal <span class="text-rose-500">*</span>
                        </label>
                        <input id="transaction_date" name="transaction_date" type="date"
                               value="{{ old('transaction_date', $transaction?->transaction_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
                               {{ $isEditable ? '' : 'disabled' }}
                               class="w-full border rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 disabled:bg-gray-50 disabled:text-gray-600"
                               :class="errors.transaction_date ? 'border-rose-400' : 'border-gray-300'" />
                        <p x-show="errors.transaction_date" x-text="errors.transaction_date" class="text-xs text-rose-600 mt-1"></p>
                    </div>

                    {{-- Terima Dari (Supplier) --}}
                    <div>
                        <label for="partner_id" class="block text-xs font-medium text-gray-600 mb-1">
                            Terima Dari (Supplier) <span class="text-rose-500">*</span>
                        </label>
                        <select id="partner_id" name="partner_id"
                                {{ $isEditable ? '' : 'disabled' }}
                                class="w-full border rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 disabled:bg-gray-50 disabled:text-gray-600"
                                :class="errors.partner_id ? 'border-rose-400' : 'border-gray-300'">
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"
                                    {{ (string) old('partner_id', $transaction?->partner_id) === (string) $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                        <p x-show="errors.partner_id" x-text="errors.partner_id" class="text-xs text-rose-600 mt-1"></p>
                    </div>

                    {{-- Dokumen Asal --}}
                    <div>
                        <label for="origin" class="block text-xs font-medium text-gray-600 mb-1">Dokumen Asal (PO / Surat Jalan)</label>
                        <input id="origin" name="origin" type="text"
                               value="{{ old('origin', $transaction?->origin) }}"
                               placeholder="Contoh: PO-2026-001"
                               {{ $isEditable ? '' : 'readonly' }}
                               class="w-full border border-gray-300 rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 read-only:bg-gray-50 read-only:text-gray-600" />
                    </div>

                    {{-- Dibuat Oleh --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Dibuat Oleh</label>
                        <input type="text" readonly
                               value="{{ $transaction?->user?->name ?? Auth::user()->name }}"
                               class="w-full bg-gray-50 border border-gray-200 rounded-lg text-sm px-3 py-2 text-gray-700" />
                    </div>

                    {{-- Divalidasi Pada --}}
                    @if($transaction && $transaction->validated_at)
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Divalidasi Pada</label>
                            <input type="text" readonly
                                   value="{{ $transaction->validated_at->format('d/m/Y H:i') }}"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg text-sm px-3 py-2 text-gray-700" />
                        </div>
                    @endif

                    {{-- Catatan --}}
                    <div class="md:col-span-2">
                        <label for="notes" class="block text-xs font-medium text-gray-600 mb-1">Catatan</label>
                        <textarea id="notes" name="notes" rows="2"
                                  {{ $isEditable ? '' : 'readonly' }}
                                  placeholder="Catatan tambahan (opsional)…"
                                  class="w-full border border-gray-300 rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 read-only:bg-gray-50 read-only:text-gray-600">{{ old('notes', $transaction?->notes) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Tabel Item --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6">
                <div class="p-5 border-b border-gray-200 flex items-center justify-between bg-gray-50/50">
                    <div>
                        <h3 class="text-base font-bold text-gray-800">Daftar Barang</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Tambahkan barang yang diterima beserta jumlahnya.</p>
                    </div>
                    @if($isEditable)
                        <button type="button" @click="addRow()"
                                class="inline-flex items-center px-3 py-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-xs font-semibold hover:bg-emerald-100 transition cursor-pointer">
                            Tambah Baris
                        </button>
                    @endif
                </div>

                {{-- Pesan error item --}}
                <div x-show="errors.items" class="px-5 py-3 bg-rose-50 border-b border-rose-200">
                    <p class="text-xs text-rose-700 font-medium" x-text="errors.items"></p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <th class="py-3 px-4 w-2/5">Barang</th>
                                <th class="py-3 px-4">Satuan</th>
                                <th class="py-3 px-4 text-right">Stok Saat Ini</th>
                                <th class="py-3 px-4 text-right w-32">Jumlah</th>
                                @if($isEditable)
                                    <th class="py-3 px-4 text-center w-20">Hapus</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <template x-for="(row, index) in items" :key="index">
                                <tr class="hover:bg-gray-50/60">
                                    <td class="py-2.5 px-4">
                                        <select :name="`items[${index}][product_id]`"
                                                x-model="row.product_id"
                                                {{ $isEditable ? '' : 'disabled' }}
                                                class="w-full border rounded-lg text-sm px-2.5 py-1.5 focus:outline-none focus:ring-2 focus:ring-emerald-500 disabled:bg-gray-50"
                                                :class="row.error ? 'border-rose-400' : 'border-gray-300'">
                                            <option value="">-- Pilih Barang --</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">
                                                    {{ $product->code }} - {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <p x-show="row.error" x-text="row.error" class="text-xs text-rose-600 mt-1"></p>
                                    </td>
                                    <td class="py-2.5 px-4 text-gray-600" x-text="getProduct(row.product_id)?.unit ?? '-'"></td>
                                    <td class="py-2.5 px-4 text-right font-medium text-gray-700" x-text="getProduct(row.product_id)?.stock ?? '-'"></td>
                                    <td class="py-2.5 px-4">
                                        <input type="number" min="1"
                                               :name="`items[${index}][quantity]`"
                                               x-model.number="row.quantity"
                                               {{ $isEditable ? '' : 'readonly' }}
                                               class="w-full border rounded-lg text-sm px-2.5 py-1.5 text-right focus:outline-none focus:ring-2 focus:ring-emerald-500 read-only:bg-gray-50"
                                               :class="row.qtyError ? 'border-rose-400' : 'border-gray-300'" />
                                        <p x-show="row.qtyError" x-text="row.qtyError" class="text-xs text-rose-600 mt-1"></p>
                                    </td>
                                    @if($isEditable)
                                        <td class="py-2.5 px-4 text-center">
                                            <button type="button" @click="removeRow(index)"
                                                    x-show="items.length > 1"
                                                    class="text-rose-500 hover:text-rose-700 text-xs font-semibold px-2 py-1 rounded hover:bg-rose-50 transition cursor-pointer">
                                                Hapus
                                            </button>
                                        </td>
                                    @endif
                                </tr>
                            </template>
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-50 border-t border-gray-200 font-semibold text-gray-800">
                                <td colspan="3" class="py-3 px-4 text-right">Total Qty</td>
                                <td class="py-3 px-4 text-right text-emerald-700 text-base" x-text="totalQty()"></td>
                                @if($isEditable)
                                    <td></td>
                                @endif
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            @if($isEditable)
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <button type="submit" name="action" value="save"
                                class="inline-flex items-center px-5 py-2.5 bg-gray-800 text-white text-xs font-semibold uppercase tracking-widest rounded-lg hover:bg-gray-900 transition shadow-sm cursor-pointer">
                            Simpan Draft
                        </button>
                        <button type="submit" name="action" value="validate"
                                class="inline-flex items-center px-5 py-2.5 bg-emerald-600 text-white text-xs font-semibold uppercase tracking-widest rounded-lg hover:bg-emerald-700 transition shadow-sm cursor-pointer">
                            Validasi (Tambah Stok)
                        </button>
                    </div>

                    @if(!$isNew)
                        <button type="button"
                                onclick="if(confirm('Yakin ingin membatalkan transaksi ini?')) document.getElementById('cancel-form').submit();"
                                class="inline-flex items-center px-4 py-2.5 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-semibold uppercase tracking-widest rounded-lg hover:bg-rose-100 transition cursor-pointer">
                            Batalkan
                        </button>
                    @endif
                </div>
            @endif
        </form>

        @if(!$isNew && $isEditable)
            <form id="cancel-form" method="POST" action="{{ route('incoming.cancel', $transaction) }}" class="hidden">
                @csrf
            </form>
        @endif
    </div>

    <script>
        function incomingForm(productsMap, initialItems) {
            return {
                products: productsMap,
                items: initialItems.length ? initialItems : [{ product_id: '', quantity: 1 }],
                errors: {},
                getProduct(id) {
                    return id ? (this.products[id] ?? null) : null;
                },
                addRow() {
                    this.items.push({ product_id: '', quantity: 1 });
                },
                removeRow(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                    }
                },
                totalQty() {
                    return this.items.reduce((sum, item) => sum + (parseInt(item.quantity) || 0), 0);
                },
                validateForm() {
                    this.errors = {};
                    let valid = true;

                    // Reset row errors
                    this.items.forEach(row => { row.error = ''; row.qtyError = ''; });

                    // Header validations
                    const partnerId = document.getElementById('partner_id').value;
                    if (!partnerId) {
                        this.errors.partner_id = 'Pilih supplier terlebih dahulu.';
                        valid = false;
                    }

                    const txDate = document.getElementById('transaction_date').value;
                    if (!txDate) {
                        this.errors.transaction_date = 'Tanggal transaksi wajib diisi.';
                        valid = false;
                    }

                    // Items validations
                    const hasAnyItem = this.items.some(r => r.product_id);
                    if (!hasAnyItem) {
                        this.errors.items = 'Tambahkan minimal satu barang pada daftar item.';
                        valid = false;
                    }

                    const selectedIds = [];
                    this.items.forEach((row, idx) => {
                        if (!row.product_id) {
                            row.error = 'Pilih barang untuk baris ini.';
                            valid = false;
                        } else if (selectedIds.includes(row.product_id)) {
                            row.error = 'Barang ini sudah ada di baris lain. Pilih barang yang berbeda.';
                            valid = false;
                        } else {
                            selectedIds.push(row.product_id);
                        }

                        if (!row.quantity || parseInt(row.quantity) < 1) {
                            row.qtyError = 'Jumlah minimal adalah 1.';
                            valid = false;
                        }
                    });

                    return valid;
                }
            };
        }
    </script>
</x-app-layout>
