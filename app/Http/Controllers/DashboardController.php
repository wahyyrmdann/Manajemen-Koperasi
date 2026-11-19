<?php

namespace App\Http\Controllers;

use App\Enums\PartnerType;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\Partner;
use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard view.
     */
    public function index(Request $request): View
    {
        // 4 Summary Metric Cards (Backend Integrated)
        $totalProductTypes = Product::count();
        $totalStock = Product::sum('stock') ?? 0;

        $draftIncomingCount = StockTransaction::where('type', TransactionType::In)
            ->where('status', TransactionStatus::Draft)
            ->count();

        $doneIncomingCount = StockTransaction::where('type', TransactionType::In)
            ->where('status', TransactionStatus::Done)
            ->count();

        $totalIncomingCount = StockTransaction::where('type', TransactionType::In)->count();

        $draftOutgoingCount = StockTransaction::where('type', TransactionType::Out)
            ->where('status', TransactionStatus::Draft)
            ->count();

        $doneOutgoingCount = StockTransaction::where('type', TransactionType::Out)
            ->where('status', TransactionStatus::Done)
            ->count();

        $totalOutgoingCount = StockTransaction::where('type', TransactionType::Out)->count();

        $supplierCount = Partner::where('type', PartnerType::Supplier)->count();
        $memberCount = Partner::where('type', PartnerType::Anggota)->count();

        // Tabel Transaksi Utama (10 transaksi terbaru gabungan masuk dan keluar)
        $recentTransactions = StockTransaction::with(['partner', 'items'])
            ->withCount('items')
            ->withSum('items', 'quantity')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'totalProductTypes',
            'totalStock',
            'draftIncomingCount',
            'doneIncomingCount',
            'totalIncomingCount',
            'draftOutgoingCount',
            'doneOutgoingCount',
            'totalOutgoingCount',
            'supplierCount',
            'memberCount',
            'recentTransactions'
        ));
    }
}
