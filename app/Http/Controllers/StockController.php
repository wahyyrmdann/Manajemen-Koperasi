<?php

namespace App\Http\Controllers;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\Product;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Daftar stok barang di gudang beserta kuantitas yang masih berstatus draft.
     */
    public function index(Request $request)
    {
        $draftQty = fn (TransactionType $type) => fn ($q) => $q->whereHas(
            'stockTransaction',
            fn ($t) => $t->where('type', $type)->where('status', TransactionStatus::Draft)
        );

        $products = Product::query()
            ->withSum(['stockTransactionItems as incoming_draft_qty' => $draftQty(TransactionType::In)], 'quantity')
            ->withSum(['stockTransactionItems as outgoing_draft_qty' => $draftQty(TransactionType::Out)], 'quantity')
            ->search($request->input('search'))
            ->stockStatus($request->input('stock_status'))
            ->orderBy('code')
            ->paginate(10)
            ->withQueryString();

        $totalStock = Product::sum('stock') ?? 0;

        return view('stock.index', compact('products', 'totalStock'));
    }
}