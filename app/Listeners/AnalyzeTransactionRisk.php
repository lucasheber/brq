<?php

declare(strict_types = 1);

namespace App\Listeners;

use App\Events\TransactionRiskAnalyzed;
use App\Services\RiskAnalysisService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AnalyzeTransactionRisk implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    public string $queue = 'risk-analysis';

    public int $delay = 60; // Delay in seconds

    public function __construct(private RiskAnalysisService $risk)
    {
    }

    public function handle(TransactionRiskAnalyzed $event): void
    {
        $score = $this->risk->analyze($event->transaction);

        $transaction                       = $event->transaction->fresh();
        $transaction->risk_analysis_status = $this->risk->riskStatus($score);

        // save without dispatching another event
        $transaction->saveQuietly();
    }
}
