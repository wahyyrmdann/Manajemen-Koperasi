<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Daftar jenis barang (produk terdaftar).
     */
    public function index(Request $request)
    {
        $products = Product::query()
            ->search($request->input('search'))
            ->when($request->input('unit'), fn ($q, $unit) => $q->where('unit', $unit))
            ->stockStatus($request->input('stock_status'))
            ->orderBy('code')
            ->paginate(10)
            ->withQueryString();

        $units = Product::query()->select('unit')->distinct()->orderBy('unit')->pluck('unit');

        return view('products.index', compact('products', 'units'));
    }
}