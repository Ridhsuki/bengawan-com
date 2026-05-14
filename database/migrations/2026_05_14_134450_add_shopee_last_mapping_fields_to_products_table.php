<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('shopee_last_item_id')->nullable()->after('shopee_item_id');
            $table->unsignedBigInteger('shopee_last_model_id')->nullable()->after('shopee_last_item_id');
            $table->string('shopee_last_sku')->nullable()->after('shopee_sku');
            $table->unsignedBigInteger('shopee_last_shop_id')->nullable()->after('shopee_shop_id');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'shopee_last_item_id',
                'shopee_last_model_id',
                'shopee_last_sku',
                'shopee_last_shop_id',
            ]);
        });
    }
};
