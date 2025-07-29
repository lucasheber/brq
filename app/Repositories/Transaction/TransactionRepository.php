<?php

declare(strict_types = 1);

namespace App\Repositories\Transaction;

use Illuminate\Support\Facades\Auth;

class TransactionRepository implements TransactionRepositoryInterface
{
    /**
     * Retrieve all transactions.
     */
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return \App\Models\Transaction::all();
    }

    /**
     * Create a new transaction.
     */
    public function create(array $data): \App\Models\Transaction
    {
        return Auth::user()->transactions()->create($data);
    }

    /**
     * Retrieve a transaction by ID.
     */
    public function find(int $id): ?\App\Models\Transaction
    {
        return \App\Models\Transaction::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();
    }

    /**
     * Filter transactions based on given criteria.
     */
    public function filter(array $filters): \Illuminate\Database\Eloquent\Collection
    {
        $query = \App\Models\Transaction::query();

        if (isset($filters['start_date'])) {
            $query->where('created_at', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $query->where('created_at', '<=', $filters['end_date']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->get();
    }

    /**
     * Update an existing transaction.
     */
    public function update(int $id, array $data): \App\Models\Transaction
    {
        $transaction = $this->find($id);

        if ($transaction instanceof \App\Models\Transaction) {
            $transaction->update($data);
        }

        return $transaction;
    }

    /**
     * Delete a transaction by ID.
     */
    public function delete(int $id): int | false
    {
        $transaction = $this->find($id);

        if ($transaction instanceof \App\Models\Transaction) {
            return $transaction->delete();
        }

        return false;
    }
}
