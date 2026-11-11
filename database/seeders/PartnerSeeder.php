<?php

namespace Database\Seeders;

use App\Enums\PartnerType;
use App\Models\Partner;
use Illuminate\Database\Seeder;

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

