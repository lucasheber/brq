<?php

declare(strict_types = 1);

namespace App\Services;

use App\Enums\RiskStatus;
use App\Models\Transaction;
use Illuminate\Support\Carbon;

final class RiskAnalysisService
{
    public function analyze(Transaction $transaction): int
    {
        $score = 0;

        $score += $this->hourlyTransactionsScore($transaction);

        return $score + $this->recentlyTransactionsScore($transaction);
    }

    public function riskStatus(int $score): RiskStatus
    {
        return match (true) {
            $score >= 8 => RiskStatus::HIGH_RISK,
            $score >= 4 => RiskStatus::MEDIUM_RISK,
            default     => RiskStatus::LOW_RISK,
        };
    }

    private function hourlyTransactionsScore(Transaction $transaction): int
    {
        // Transactions above 1000 and between 22 PM and 6 AM are considered high risk
        $hour = Carbon::parse($transaction->created_at)->hour;

        if (($hour < 6 || $hour >= 22) && $transaction->amount >= 1000) {
            return 8;
        }

        return 0;
    }

    private function recentlyTransactionsScore(Transaction $transaction): int
    {
        // Transactions recently marked as medium or high risk
        $recentTransactions = Transaction::where('user_id', '=', $transaction->user_id)
            ->where('created_at', '>=', now()->subMinute())
            ->where('id', '!=', $transaction->id)
            ->count();

        if ($recentTransactions > 0) {
            return 4;
        }

        return 0;
    }
}
