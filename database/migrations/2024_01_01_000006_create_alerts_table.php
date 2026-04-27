<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('stock_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // 'price_above', 'price_below', 'rsi_oversold', 'volume_spike', etc.
            $table->string('name'); // Human-readable name
            $table->json('conditions'); // Alert conditions as JSON
            $table->boolean('is_active')->default(true);
            $table->boolean('notify_telegram')->default(true);
            $table->boolean('notify_email')->default(false);
            $table->timestamp('last_triggered_at')->nullable();
            $table->integer('trigger_count')->default(0);
            $table->timestamps();

            $table->index(['user_id', 'is_active']);
            $table->index('stock_id');
            $table->index('type');
        });

        Schema::create('alert_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alert_id')->constrained()->cascadeOnDelete();
            $table->string('message');
            $table->string('channel')->default('telegram'); // telegram, email
            $table->boolean('sent')->default(false);
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index('alert_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alert_logs');
        Schema::dropIfExists('alerts');
    }
};
