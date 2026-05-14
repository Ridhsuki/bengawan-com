<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->string('product_name_snapshot')->nullable()->after('product_id');
            $table->string('product_sku_snapshot')->nullable()->after('product_name_snapshot');
            $table->unsignedBigInteger('product_shopee_item_id_snapshot')->nullable()->after('product_sku_snapshot');
            $table->unsignedBigInteger('product_shopee_model_id_snapshot')->nullable()->after('product_shopee_item_id_snapshot');
        });

        DB::statement("
            UPDATE sales
            LEFT JOIN products ON products.id = sales.product_id
            SET
                sales.product_name_snapshot = COALESCE(products.name, sales.product_name_snapshot),
                sales.product_sku_snapshot = COALESCE(products.serial_number, products.shopee_sku, sales.product_sku_snapshot),
                sales.product_shopee_item_id_snapshot = COALESCE(products.shopee_item_id, sales.external_item_id),
                sales.product_shopee_model_id_snapshot = COALESCE(products.shopee_model_id, sales.external_model_id)
            WHERE sales.product_name_snapshot IS NULL
        ");
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn([
                'product_name_snapshot',
                'product_sku_snapshot',
                'product_shopee_item_id_snapshot',
                'product_shopee_model_id_snapshot',
            ]);
        });
    }
};
