<?php

declare(strict_types = 1);

use App\Enums\TransactionStatus;
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            // Uncomment the following line if you have a user_id foreign key
            // $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('document');
            $table->integer('amount')->unsigned();
            $table->string('currency', 3);
            $table->enum('status', array_column(TransactionStatus::cases(), 'value'))
                ->default(TransactionStatus::PENDING->value)
                ->comment('Transaction status');
            $table->string('reason')->nullable()->comment('Reason for transaction status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
