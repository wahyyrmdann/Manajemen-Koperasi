<?php

namespace App\Http\Controllers;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard view.
     */
    public function index(Request $request): Response
    {
        // 4 Summary Cards
        $totalProductTypes = Product::count();
        $totalStock = Product::sum('stock') ?? 0;
        $draftIncomingCount = StockTransaction::where('type', TransactionType::In)
            ->where('status', TransactionStatus::Draft)
            ->count();
        $draftOutgoingCount = StockTransaction::where('type', TransactionType::Out)
            ->where('status', TransactionStatus::Draft)
            ->count();

        // Tabel Transaksi Utama (10 transaksi terbaru gabungan masuk dan keluar)
        $recentTransactions = StockTransaction::with(['partner', 'items'])
            ->withCount('items')
            ->withSum('items', 'quantity')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Prevent browser from caching the dashboard page so data is always fresh
        return response()
            ->view('dashboard', compact(
                'totalProductTypes',
                'totalStock',
                'draftIncomingCount',
                'draftOutgoingCount',
                'recentTransactions'
            ))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
