<?php

namespace App\Enums;

enum TransactionStatus: string
{
    case Draft = 'draft';
    case Done = 'done';
    case Cancelled = 'cancelled';
}

