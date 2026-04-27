<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('technical_indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            
            // Moving Averages
            $table->decimal('sma_20', 12, 4)->nullable();
            $table->decimal('sma_50', 12, 4)->nullable();
            $table->decimal('sma_200', 12, 4)->nullable();
            $table->decimal('ema_12', 12, 4)->nullable();
            $table->decimal('ema_26', 12, 4)->nullable();
            
            // Momentum
            $table->decimal('rsi_14', 8, 4)->nullable();
            $table->decimal('macd', 12, 6)->nullable();
            $table->decimal('macd_signal', 12, 6)->nullable();
            $table->decimal('macd_histogram', 12, 6)->nullable();
            $table->decimal('stochastic_k', 8, 4)->nullable();
            $table->decimal('stochastic_d', 8, 4)->nullable();
            
            // Volatility
            $table->decimal('bollinger_upper', 12, 4)->nullable();
            $table->decimal('bollinger_middle', 12, 4)->nullable();
            $table->decimal('bollinger_lower', 12, 4)->nullable();
            $table->decimal('atr_14', 12, 4)->nullable();
            
            // Volume
            $table->decimal('volume_sma_20', 20, 0)->nullable();
            $table->decimal('obv', 20, 0)->nullable(); // On-Balance Volume
            
            // Price metrics
            $table->decimal('change_percent', 8, 4)->nullable(); // Daily change %
            $table->decimal('week_52_high', 12, 4)->nullable();
            $table->decimal('week_52_low', 12, 4)->nullable();
            
            $table->timestamps();

            $table->unique(['stock_id', 'date']);
            $table->index('date');
            $table->index('rsi_14');
            $table->index('macd');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('technical_indicators');
    }
};
