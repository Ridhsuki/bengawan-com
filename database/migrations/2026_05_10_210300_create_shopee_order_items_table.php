<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shopee_order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('shopee_order_id')->constrained('shopee_orders')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();

            $table->unsignedBigInteger('shopee_item_id')->index();
            $table->unsignedBigInteger('shopee_model_id')->default(0)->index();
            $table->string('shopee_sku')->nullable();

            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 15, 2)->nullable();

            $table->json('raw_payload')->nullable();

            $table->timestamps();

            $table->unique(
                ['shopee_order_id', 'shopee_item_id', 'shopee_model_id'],
                'shopee_order_item_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shopee_order_items');
    }
};
