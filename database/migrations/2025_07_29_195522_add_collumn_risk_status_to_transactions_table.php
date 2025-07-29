<?php

declare(strict_types = 1);

use App\Enums\RiskStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table
                ->enum('risk_analysis_status', array_column(RiskStatus::cases(), 'value'))
                ->nullable()
                ->comment('Status of the risk analysis for the transaction')
                ->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('risk_analysis_status');
        });
    }
};
