<?php

namespace Database\Seeders;

use App\Enums\PartnerType;
use App\Enums\UserRole;
use App\Models\Partner;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database in a single seeder file.
     */
    public function run(): void
    {
        // 1. Seed Admin (Dapat login menggunakan email 'admin@koperasi.test', username 'admin', atau 'Administrator' dengan password 'password')
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@koperasi.test')],
            [
                'name' => 'Administrator',
                'password' => env('ADMIN_PASSWORD', 'password'),
                'role' => UserRole::Admin,
            ]
        );

        // 2. Seed Partners (Suppliers & Anggota)
        $partners = [
            // Suppliers
            [
                'name' => 'PT Sumber Pangan',
                'type' => PartnerType::Supplier,
                'phone' => '021-5551234',
                'address' => 'Jl. Industri No. 10, Jakarta',
            ],
            [
                'name' => 'CV Maju Jaya',
                'type' => PartnerType::Supplier,
                'phone' => '031-4445678',
                'address' => 'Jl. Raya Surabaya No. 22',
            ],
            // Anggota
            [
                'name' => 'Budi Santoso',
                'type' => PartnerType::Anggota,
                'phone' => '0812-3456-7890',
                'address' => 'Jl. Melati No. 5, Bandung',
            ],
            [
                'name' => 'Siti Aminah',
                'type' => PartnerType::Anggota,
                'phone' => '0856-7890-1234',
                'address' => 'Jl. Kenanga No. 12, Yogyakarta',
            ],
        ];

        foreach ($partners as $partner) {
            Partner::firstOrCreate(
                ['name' => $partner['name']],
                $partner
            );
        }

        // 3. Seed Products
        $products = [
            ['code' => 'BRG-001', 'name' => 'Gula Pasir 50kg', 'unit' => 'Zak', 'stock' => 50],
            ['code' => 'BRG-002', 'name' => 'Beras Premium 25kg', 'unit' => 'Karton', 'stock' => 40],
            ['code' => 'BRG-003', 'name' => 'Minyak Goreng 1L', 'unit' => 'Karton', 'stock' => 100],
            ['code' => 'BRG-004', 'name' => 'Tepung Terigu 1kg', 'unit' => 'Kg', 'stock' => 0],
            ['code' => 'BRG-005', 'name' => 'Kopi Bubuk 250g', 'unit' => 'Karton', 'stock' => 15],
        ];

        foreach ($products as $prod) {
            Product::firstOrCreate(
                ['code' => $prod['code']],
                $prod
            );
        }
    }
}
