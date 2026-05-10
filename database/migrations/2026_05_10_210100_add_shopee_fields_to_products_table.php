<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('shopee_shop_id')
                ->nullable()
                ->after('link_tokopedia')
                ->constrained('shopee_shops')
                ->nullOnDelete();

            $table->unsignedBigInteger('shopee_item_id')->nullable()->after('shopee_shop_id')->index();

            // Isi 0 jika produk Shopee tidak punya variasi/model.
            $table->unsignedBigInteger('shopee_model_id')->default(0)->after('shopee_item_id')->index();

            $table->string('shopee_sku')->nullable()->after('shopee_model_id')->index();
            $table->boolean('sync_shopee_stock')->default(false)->after('shopee_sku')->index();

            $table->integer('shopee_stock')->nullable()->after('sync_shopee_stock');
            $table->timestamp('shopee_last_synced_at')->nullable()->after('shopee_stock');
            $table->string('shopee_sync_status')->nullable()->after('shopee_last_synced_at');
            $table->text('shopee_sync_error')->nullable()->after('shopee_sync_status');

            $table->index(['shopee_shop_id', 'shopee_item_id', 'shopee_model_id'], 'products_shopee_lookup_idx');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['shopee_shop_id']);

            $table->dropColumn([
                'shopee_shop_id',
                'shopee_item_id',
                'shopee_model_id',
                'shopee_sku',
                'sync_shopee_stock',
                'shopee_stock',
                'shopee_last_synced_at',
                'shopee_sync_status',
                'shopee_sync_error',
            ]);
        });
    }
};
