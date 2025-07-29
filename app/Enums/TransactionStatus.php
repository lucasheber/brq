<?php

declare(strict_types = 1);

namespace App\Enums;

enum TransactionStatus: string
{
    case PENDING    = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED  = 'completed';
    case FAILED     = 'failed';
    case REFUNDED   = 'refunded';
    case CANCELLED  = 'cancelled';
    case CHARGEBACK = 'chargeback';
}
