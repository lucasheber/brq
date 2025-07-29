<?php

use App\Listeners\AnalyzeTransactionRisk;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Events\CallQueuedListener;

test('if transaction is changed to high risks', function (): void {

    Event::fake([
        App\Events\TransactionRiskAnalyzed::class,
    ]);

    $user = App\Models\User::factory()->create();

   $transaction = Transaction::factory()->create([
        'user_id'    => $user->id,
        'amount'     => 1000.03, // pick a value that guarantees HIGH
        'document'   => '1234567890', // Example document number
        'currency'   => 'USD',
        'created_at' => now()->timezone('America/Sao_Paulo')->setHour(23), // 11 PM
        'status'     => \App\Enums\TransactionStatus::PENDING,
    ]);

   // Run the listener synchronously
    $listener = app(AnalyzeTransactionRisk::class);
    $listener->handle(new \App\Events\TransactionRiskAnalyzed($transaction));

    $transaction->refresh();

    expect($transaction->risk_analysis_status)
        ->toBe(\App\Enums\RiskStatus::HIGH_RISK);

    Event::assertDispatched( \App\Events\TransactionRiskAnalyzed::class, fn($event) => $event->transaction->is($transaction));
});

test('risk analysis listener is queued on transaction save', function (): void {
    Queue::fake();

    $user = User::factory()->create();

    $transaction = Transaction::factory()->create([
        'user_id'    => $user->id,
        'amount'     => 100_000, // ensure it will be high risk if you later run it
        'created_at' => now()->setHour(23),
        'status'     => \App\Enums\TransactionStatus::PENDING,
    ]);

    // If your event is fired by an observer on created model, saving is enough.
    // Otherwise, explicitly:
    // event(new TransactionCreated($transaction));

    Queue::assertPushed(CallQueuedListener::class, function ($job): bool {
        /** @var CallQueuedListener $job */
        return $job->class === AnalyzeTransactionRisk::class;
    });
});
