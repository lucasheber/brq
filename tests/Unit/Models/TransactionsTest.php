<?php

declare(strict_types=1);

describe('Transaction Model', function (): void {
    it('should create a transaction', function (): void {
        $transaction = new App\Models\Transaction();
        $transaction->setAmount(100);
        $transaction->setCurrency('USD');

        expect($transaction->getAmount())->toBe(100);
        expect($transaction->getCurrency())->toBe('USD');
    });

    it('should fail with invalid amount', function (): void {
        $transaction = new App\Models\Transaction();
        $transaction->setAmount(-50);

        expect(fn() => $transaction->validate())->toThrow(\InvalidArgumentException::class);
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
        $transaction = App\Models\Transaction::find(1);

        expect($transaction)->not->toBeNull();
        expect($transaction->getId())->toBe(1);
    });
});