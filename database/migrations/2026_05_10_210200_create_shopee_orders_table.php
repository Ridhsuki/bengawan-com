<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shopee_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shopee_shop_id')->constrained('shopee_shops')->cascadeOnDelete();

            $table->string('order_sn')->unique();
            $table->string('order_status')->nullable()->index();
            $table->json('raw_payload')->nullable();

            $table->timestamp('stock_applied_at')->nullable();
            $table->timestamp('stock_restored_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shopee_orders');
    }
};
