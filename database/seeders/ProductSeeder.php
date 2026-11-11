<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Seed sample products with stock = 0.
     */
    public function run(): void
    {
        $products = [
            ['code' => 'BRG-001', 'name' => 'Gula Pasir 50kg', 'unit' => 'Zak'],
            ['code' => 'BRG-002', 'name' => 'Beras Premium 25kg', 'unit' => 'Karton'],
            ['code' => 'BRG-003', 'name' => 'Minyak Goreng 1L', 'unit' => 'Karton'],
            ['code' => 'BRG-004', 'name' => 'Tepung Terigu 1kg', 'unit' => 'Kg'],
            ['code' => 'BRG-005', 'name' => 'Kopi Bubuk 250g', 'unit' => 'Karton'],
        ];

        foreach ($products as $product) {
            Product::create(array_merge($product, ['stock' => 0]));
        }
    }
}

