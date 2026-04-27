<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('symbol', 10)->unique(); // e.g., AAPL, TSLA
            $table->string('name'); // Apple Inc.
            $table->string('exchange'); // NASDAQ, NYSE
            $table->string('sector')->nullable(); // Technology, Healthcare
            $table->string('industry')->nullable(); // Consumer Electronics
            $table->decimal('market_cap', 20, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('symbol');
            $table->index('sector');
            $table->index('exchange');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
