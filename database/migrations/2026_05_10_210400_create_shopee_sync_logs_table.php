<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shopee_sync_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('shopee_shop_id')->nullable()->constrained('shopee_shops')->nullOnDelete();

            $table->string('type')->index();
            $table->string('status')->index();
            $table->text('message')->nullable();

            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shopee_sync_logs');
    }
};
