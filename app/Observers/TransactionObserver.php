<?php

declare(strict_types = 1);

namespace App\Observers;

use App\Models\Transaction;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        // Dispatch the risk analysis event
        event(new \App\Events\TransactionRiskAnalyzed($transaction));
    }
}
