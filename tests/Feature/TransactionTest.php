<?php

use App\Enums\TransactionStatus;
use Illuminate\Http\Response;

beforeEach(function (): void {
    $this->actingAs(\App\Models\User::factory()->create(), 'sanctum');
});

it('should create a transaction', function (): void {
    $response = $this->postJson(route('transactions.store'), [
        'amount' => 100.43,
        'document' => '1234567890',
        'currency' => 'USD',
    ]);

    $response->assertStatus(Response::HTTP_CREATED);
});

it('should not create a transaction with invalid data', function (): void {
    $response = $this->postJson(route('transactions.store'), [
        'amount' => 'invalid',
        'document' => '',
    ]);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['amount', 'document']);
});

it('should retrieve a transaction', function (): void {
    $transaction = \App\Models\Transaction::factory()->create([
        'amount' => 200.75,
        'document' => '0987654321',
    ]);

    $response = $this->getJson(route('transactions.show', $transaction));

    $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'data' => [
                'id' => $transaction->id,
                'amount' => 200.75,
                'document' => '0987654321',
            ],
        ]);
});

it('should not retrieve a non-existing transaction', function (): void {
    $response = $this->getJson(route('transactions.show', 999999));

    $response->assertStatus(Response::HTTP_NOT_FOUND)
        ->assertJson([
            'message' => 'Transaction not found',
        ]);
});

it('should update a transaction', function (): void {
    $transaction = \App\Models\Transaction::factory()->create([
        'amount' => 150.00,
        'document' => '1122334455',
        'currency' => 'USD',
    ]);

    $response = $this->putJson(route('transactions.update', $transaction->id), [
        'amount' => 175.50,
        'document' => '5566778899',
        'currency' => 'USD',
    ]);

    $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'data' => [
                'id' => $transaction->id,
                'amount' => 175.50,
                'document' => '5566778899',
            ],
        ]);
});

it('should not update a transaction with invalid data', function (): void {
    $transaction = \App\Models\Transaction::factory()->create();

    $response = $this->putJson(route('transactions.update', $transaction), [
        'amount' => 'invalid',
        'document' => '',
    ]);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['amount', 'document']);
});

it('should delete a transaction', function (): void {
    $transaction = \App\Models\Transaction::factory()->create();

    $response = $this->deleteJson(route('transactions.destroy', $transaction->id));

    $response->assertStatus(Response::HTTP_NO_CONTENT);

    $this->assertDatabaseMissing('transactions', [
        'id' => $transaction->id,
    ]);
});

it('should not delete a non-existing transaction', function (): void {
    $response = $this->deleteJson(route('transactions.destroy', 999999));

    $response->assertStatus(Response::HTTP_NOT_FOUND)
        ->assertJson([
            'message' => 'Transaction not found',
        ]);
});

it('should list all transactions', function (): void {
    $transactions = \App\Models\Transaction::factory()->count(3)->create();

    $response = $this->getJson(route('transactions.index'));

    $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'data' => $transactions->toArray(),
        ]);

    $this->assertCount(3, $response->json('data'));
});

it('should filter transactions by period', function (): void {
    $startDate = now()->subDays(10);
    $endDate = now()->subDays(5);

    $transactions = \App\Models\Transaction::factory()->count(3)->create([
        'created_at' => $startDate,
    ]);

    \App\Models\Transaction::factory()->count(2)->create([
        'created_at' => now()->subDays(16),
    ]);

    $parameters = [
        'start_date' => $startDate->toDateString(),
        'end_date' => $endDate->toDateString(),
    ];

    $response = $this->getJson(route('transactions.index', $parameters));

    $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'data' => $transactions->toArray(),
        ]);

    $this->assertCount(3, $response->json('data'));
});

it('should not filter transactions with invalid dates', function (): void {
    \App\Models\Transaction::factory()->count(3)->create();

    $parameters = [
        'start_date' => 'invalid',
        'end_date' => 'invalid',
    ];

    $response = $this->getJson(route('transactions.index', $parameters));

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['start_date', 'end_date']);
});

it('should filter transactions by status', function (): void {
    $transactions = \App\Models\Transaction::factory()->count(3)->create([
        'status' => TransactionStatus::COMPLETED->value,
    ]);

    \App\Models\Transaction::factory()->count(2)->create([
        'status' => TransactionStatus::PENDING->value,
    ]);

    $parameters = [
        'status' => TransactionStatus::COMPLETED->value,
    ];
    $response = $this->getJson(route('transactions.index', $parameters));

    $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'data' => $transactions->toArray(),
        ]);

    $this->assertCount(3, $response->json('data'));
});