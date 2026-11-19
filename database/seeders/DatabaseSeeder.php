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
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            PartnerSeeder::class,
            ProductSeeder::class,
        ]);
    }
}

class AdminSeeder extends Seeder
{
    /**
     * Seed the admin account.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => env('ADMIN_EMAIL', 'admin@koperasi.test'),
            'password' => env('ADMIN_PASSWORD', 'password'),
            'role' => UserRole::Admin,
        ]);
    }
}

class PartnerSeeder extends Seeder
{
    /**
     * Seed sample partners.
     */
    public function run(): void
    {
        // Supplier
        Partner::create([
            'name' => 'PT Sumber Pangan',
            'type' => PartnerType::Supplier,
            'phone' => '021-5551234',
            'address' => 'Jl. Industri No. 10, Jakarta',
        ]);

        Partner::create([
            'name' => 'CV Maju Jaya',
            'type' => PartnerType::Supplier,
            'phone' => '031-4445678',
            'address' => 'Jl. Raya Surabaya No. 22',
        ]);

        // Anggota
        Partner::create([
            'name' => 'Budi Santoso',
            'type' => PartnerType::Anggota,
            'phone' => '0812-3456-7890',
            'address' => 'Jl. Melati No. 5, Bandung',
        ]);

        Partner::create([
            'name' => 'Siti Aminah',
            'type' => PartnerType::Anggota,
            'phone' => '0856-7890-1234',
            'address' => 'Jl. Kenanga No. 12, Yogyakarta',
        ]);
    }
}

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