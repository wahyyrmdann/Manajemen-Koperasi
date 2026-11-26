<?php

namespace App\Http\Controllers;

use App\Enums\PartnerType;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\Partner;
use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OutgoingTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = StockTransaction::where('type', TransactionType::Out)
            ->with(['partner', 'items'])
            ->withCount('items')
            ->withSum('items', 'quantity');

        // Filter: pencarian (referensi, nama anggota)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhereHas('partner', fn($p) => $p->where('name', 'like', "%{$search}%"));
            });
        }

        // Filter: status
        if ($status = $request->input('status')) {
            $statusEnum = TransactionStatus::tryFrom($status);
            if ($statusEnum) {
                $query->where('status', $statusEnum);
            }
        }

        // Filter: rentang tanggal
        if ($startDate = $request->input('start_date')) {
            $query->whereDate('transaction_date', '>=', $startDate);
        }
        if ($endDate = $request->input('end_date')) {
            $query->whereDate('transaction_date', '<=', $endDate);
        }

        $transactions = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

        return view('outgoing.index', compact('transactions'));
    }

    public function create()
    {
        $members = Partner::where('type', PartnerType::Anggota)->orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('outgoing.form', [
            'transaction' => null,
            'members'     => $members,
            'products'    => $products,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);

        $transaction = DB::transaction(function () use ($validated) {
            $nextNumber = StockTransaction::where('type', TransactionType::Out)->count() + 1;
            $reference = 'WH/OUT/' . str_pad((string) $nextNumber, 5, '0', STR_PAD_LEFT);

            while (StockTransaction::where('reference', $reference)->exists()) {
                $nextNumber++;
                $reference = 'WH/OUT/' . str_pad((string) $nextNumber, 5, '0', STR_PAD_LEFT);
            }

            $tx = StockTransaction::create([
                'reference'        => $reference,
                'type'             => TransactionType::Out,
                'partner_id'       => $validated['partner_id'],
                'user_id'          => Auth::id(),
                'transaction_date' => $validated['transaction_date'],
                'origin'           => $validated['origin'] ?? null,
                'notes'            => $validated['notes'] ?? null,
                'status'           => TransactionStatus::Draft,
            ]);

            foreach ($validated['items'] as $item) {
                $tx->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                ]);
            }

            return $tx;
        });

        if ($request->input('action') === 'validate') {
            return $this->performValidation($transaction);
        }

        return redirect()
            ->route('outgoing.show', $transaction)
            ->with('success', "Transaksi {$transaction->reference} berhasil disimpan sebagai Draft.");
    }

    public function show(StockTransaction $transaction)
    {
        abort_unless($transaction->type === TransactionType::Out, 404);

        $transaction->load(['partner', 'user', 'items.product']);
        $members = Partner::where('type', PartnerType::Anggota)->orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('outgoing.form', [
            'transaction' => $transaction,
            'members'     => $members,
            'products'    => $products,
        ]);
    }

    public function update(Request $request, StockTransaction $transaction)
    {
        abort_unless($transaction->type === TransactionType::Out, 404);

        if ($transaction->status !== TransactionStatus::Draft) {
            return redirect()
                ->route('outgoing.show', $transaction)
                ->with('error', 'Transaksi yang sudah selesai atau dibatalkan tidak dapat diubah.');
        }

        $validated = $this->validateRequest($request);

        DB::transaction(function () use ($transaction, $validated) {
            $transaction->update([
                'partner_id'       => $validated['partner_id'],
                'transaction_date' => $validated['transaction_date'],
                'origin'           => $validated['origin'] ?? null,
                'notes'            => $validated['notes'] ?? null,
            ]);

            $transaction->items()->delete();

            foreach ($validated['items'] as $item) {
                $transaction->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                ]);
            }
        });

        if ($request->input('action') === 'validate') {
            return $this->performValidation($transaction);
        }

        return redirect()
            ->route('outgoing.show', $transaction)
            ->with('success', "Perubahan pada {$transaction->reference} berhasil disimpan.");
    }

    public function validateTransaction(StockTransaction $transaction)
    {
        abort_unless($transaction->type === TransactionType::Out, 404);

        if ($transaction->status !== TransactionStatus::Draft) {
            return redirect()
                ->route('outgoing.show', $transaction)
                ->with('error', 'Hanya transaksi berstatus Draft yang dapat divalidasi.');
        }

        return $this->performValidation($transaction);
    }

    public function cancel(StockTransaction $transaction)
    {
        abort_unless($transaction->type === TransactionType::Out, 404);

        if ($transaction->status !== TransactionStatus::Draft) {
            return redirect()
                ->route('outgoing.show', $transaction)
                ->with('error', 'Hanya transaksi berstatus Draft yang dapat dibatalkan.');
        }

        $transaction->update([
            'status' => TransactionStatus::Cancelled,
        ]);

        return redirect()
            ->route('outgoing.show', $transaction)
            ->with('success', "Transaksi {$transaction->reference} telah dibatalkan.");
    }

    private function performValidation(StockTransaction $transaction)
    {
        $transaction->load('items.product');

        foreach ($transaction->items as $item) {
            $product = $item->product;
            if ($product->stock < $item->quantity) {
                return redirect()
                    ->route('outgoing.show', $transaction)
                    ->with('error', "Stok {$product->name} tidak cukup (tersedia {$product->stock}, diminta {$item->quantity}).");
            }
        }

        DB::transaction(function () use ($transaction) {
            foreach ($transaction->items as $item) {
                $item->product->decrement('stock', $item->quantity);
            }

            $transaction->update([
                'status'       => TransactionStatus::Done,
                'validated_at' => now(),
            ]);
        });

        return redirect()
            ->route('outgoing.show', $transaction)
            ->with('success', "Transaksi {$transaction->reference} berhasil divalidasi. Stok barang telah berkurang.");
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'partner_id'         => ['required', 'exists:partners,id'],
            'transaction_date'   => ['required', 'date'],
            'origin'             => ['nullable', 'string', 'max:255'],
            'notes'              => ['nullable', 'string'],
            'items'              => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id', 'distinct'],
            'items.*.quantity'   => ['required', 'integer', 'min:1'],
        ], [
            'partner_id.required'         => 'Anggota penerima belum dipilih.',
            'transaction_date.required'   => 'Tanggal transaksi wajib diisi.',
            'items.required'              => 'Belum ada item barang yang ditambahkan.',
            'items.min'                   => 'Belum ada item barang yang ditambahkan.',
            'items.*.product_id.required' => 'Barang pada setiap baris wajib dipilih.',
            'items.*.product_id.distinct' => 'Produk duplikat tidak diperbolehkan dalam satu transaksi.',
            'items.*.quantity.required'   => 'Jumlah harus diisi.',
            'items.*.quantity.min'        => 'Jumlah harus lebih dari 0.',
        ]);
    }
}
