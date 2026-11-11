<?php

namespace App\Models;

use App\Enums\PartnerType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partner extends Model
{
    protected $fillable = [
        'name',
        'type',
        'phone',
        'address',
    ];

    protected function casts(): array
    {
        return [
            'type' => PartnerType::class,
        ];
    }

    /**
     * Get stock transactions associated with this partner.
     */
    public function stockTransactions(): HasMany
    {
        return $this->hasMany(StockTransaction::class);
    }
}

