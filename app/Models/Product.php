<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'code',
        'name',
        'unit',
        'stock',
    ];

    protected function casts(): array
    {
        return [
            'stock' => 'integer',
        ];
    }

    /**
     * Get transaction items for this product.
     */
    public function stockTransactionItems(): HasMany
    {
        return $this->hasMany(StockTransactionItem::class);
    }
}

