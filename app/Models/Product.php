<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /**
     * Batas stok yang dianggap "menipis" (stok > 0 dan <= batas ini).
     */
    public const LOW_STOCK_THRESHOLD = 20;

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

    /**
     * Status stok: habis, menipis, atau tersedia.
     */
    public function getStockStatusAttribute(): string
    {
        if ($this->stock <= 0) {
            return 'empty';
        }

        return $this->stock <= self::LOW_STOCK_THRESHOLD ? 'low' : 'available';
    }

    /**
     * Filter berdasarkan status stok (available | low | empty).
     */
    public function scopeStockStatus(Builder $query, ?string $status): Builder
    {
        return match ($status) {
            'empty' => $query->where('stock', '<=', 0),
            'low' => $query->where('stock', '>', 0)->where('stock', '<=', self::LOW_STOCK_THRESHOLD),
            'available' => $query->where('stock', '>', self::LOW_STOCK_THRESHOLD),
            default => $query,
        };
    }

    /**
     * Filter pencarian berdasarkan kode atau nama barang.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('code', 'like', "%{$term}%")
              ->orWhere('name', 'like', "%{$term}%");
        });
    }
}