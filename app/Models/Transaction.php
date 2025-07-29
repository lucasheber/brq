<?php

declare(strict_types = 1);

namespace App\Models;

use App\Enums\TransactionStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    public function amount(): Attribute
    {
        return Attribute::make(
            get: fn ($value): float => (float)$value / 100,
            set: function (float $value): int {
                if ($value < 0) {
                    throw new \InvalidArgumentException('Amount must be a positive value.');
                }

                // Store amount in cents
                return (int)($value * 100);
            }
        );
    }

    public function currency(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => strtoupper((string) $value),
            set: fn (string $value) => $this->attributes['currency'] = strtoupper($value)
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function casts(): array
    {
        return [
            'status' => TransactionStatus::class,
        ];
    }
}
