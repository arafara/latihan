<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('screeners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // e.g., "Oversold Stocks", "Golden Cross"
            $table->text('description')->nullable();
            $table->json('filters'); // Store filter criteria as JSON
            $table->boolean('is_public')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_run_at')->nullable();
            $table->integer('last_result_count')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('is_active');
        });

        Schema::create('screener_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('screener_id')->constrained()->cascadeOnDelete();
            $table->foreignId('stock_id')->constrained()->cascadeOnDelete();
            $table->json('match_details')->nullable(); // Which filters matched
            $table->timestamps();

            $table->unique(['screener_id', 'stock_id']);
            $table->index('stock_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('screener_results');
        Schema::dropIfExists('screeners');
    }
};
