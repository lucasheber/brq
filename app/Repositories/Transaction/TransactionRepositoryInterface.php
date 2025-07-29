<?php

declare(strict_types = 1);

namespace App\Repositories\Transaction;

interface TransactionRepositoryInterface
{
    /**
     * Retrieve all transactions.
     */
    public function all(): \Illuminate\Database\Eloquent\Collection;

    /**
     * Create a new transaction.
     */
    public function create(array $data): \App\Models\Transaction;

    /**
     * Retrieve a transaction by ID.
     */
    public function find(int $id): ?\App\Models\Transaction;

    /**
     * Filter transactions based on given criteria.
     */
    public function filter(array $filters): \Illuminate\Database\Eloquent\Collection;

    /**
     * Update an existing transaction.
     */
    public function update(int $id, array $data): \App\Models\Transaction;

    /**
     * Delete a transaction by ID.
     */
    public function delete(int $id): int | false;
}
