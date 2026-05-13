<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $duplicates = DB::select("
            SELECT shopee_shop_id, shopee_item_id, shopee_model_id, COUNT(*) AS total
            FROM products
            WHERE shopee_item_id IS NOT NULL
            GROUP BY shopee_shop_id, shopee_item_id, shopee_model_id
            HAVING total > 1
        ");

        if (!empty($duplicates)) {
            throw new RuntimeException('Masih ada duplikasi mapping Shopee. Perbaiki data produk sebelum menjalankan migration unique mapping.');
        }

        Schema::table('products', function (Blueprint $table) {
            $table->unique(
                ['shopee_shop_id', 'shopee_item_id', 'shopee_model_id'],
                'products_unique_shopee_mapping'
            );
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique('products_unique_shopee_mapping');
        });
    }
};
