<?php

declare(strict_types=1);

use App\Enums\TransactionStatus;

describe('Transaction Model', function (): void {
    it('should create a transaction', function (): void {
        $transaction = new App\Models\Transaction();
        $transaction->amount = 100.12; // Set amount in dollars
        $transaction->currency = 'usd'; // Set currency to USD

        expect($transaction->amount)->toBe(100.12);
        expect($transaction->currency)->toBe('USD');
    });

    it('should fail with invalid amount', function (): void {
        expect(function () {
            $transaction = new App\Models\Transaction();
            $transaction->amount = -50.00; // Set negative amount
        })->toThrow(\InvalidArgumentException::class, 'Amount must be a positive value.');
    });
});

describe('Transaction Table', function (): void {
    it('should have the correct table name', function (): void {
        $transaction = new App\Models\Transaction();

        expect($transaction->getTable())->toBe('transactions');
    });

    it('should return all transactions', function (): void {
        $transactions = App\Models\Transaction::all();

        expect($transactions)->toBeInstanceOf(\Illuminate\Database\Eloquent\Collection::class);
    });

    it('should find a transaction by ID', function (): void {
        // Seed the database with a transaction first
        App\Models\Transaction::create([
            'document' => 'doc123',
            'amount' => 100.12, // Set amount in dollars
            'currency' => 'USD',
            'status' => TransactionStatus::PENDING->value,
        ]);

        $transaction = App\Models\Transaction::find(1);

        expect($transaction)->not->toBeNull();
        expect($transaction->id)->toBe(1);
    });
});