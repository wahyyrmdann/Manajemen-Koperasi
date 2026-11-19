<?php

namespace App\Http\Controllers;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\StockTransaction;
use Illuminate\Http\Request;

class IncomingTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = StockTransaction::where('type', TransactionType::In)
            ->with(['partner', 'items'])
            ->withCount('items')
            ->withSum('items', 'quantity');

        // Filter: pencarian (referensi, nama supplier, dokumen asal)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhereHas('partner', fn($p) => $p->where('name', 'like', "%{$search}%"))
                  ->orWhere('origin', 'like', "%{$search}%");
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

        return view('incoming.index', compact('transactions'));
    }
}
