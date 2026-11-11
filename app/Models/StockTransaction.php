<?php

namespace App\Models;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockTransaction extends Model
{
    protected $fillable = [
        'reference',
        'type',
        'partner_id',
        'user_id',
        'transaction_date',
        'origin',
        'status',
        'validated_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'type' => TransactionType::class,
            'status' => TransactionStatus::class,
            'transaction_date' => 'date',
            'validated_at' => 'datetime',
        ];
    }

    /**
     * Get the partner (supplier/anggota) for this transaction.
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * Get the user who created this transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the line items for this transaction.
     */
    public function items(): HasMany
    {
        return $this->hasMany(StockTransactionItem::class);
    }
}

