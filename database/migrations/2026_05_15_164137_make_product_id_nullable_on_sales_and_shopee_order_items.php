<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $this->dropForeignIfExists('sales', 'product_id');

        DB::statement('ALTER TABLE sales MODIFY product_id BIGINT UNSIGNED NULL');

        Schema::table('sales', function (Blueprint $table) {
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->nullOnDelete();
        });

        if (Schema::hasTable('shopee_order_items') && Schema::hasColumn('shopee_order_items', 'product_id')) {
            $this->dropForeignIfExists('shopee_order_items', 'product_id');

            DB::statement('ALTER TABLE shopee_order_items MODIFY product_id BIGINT UNSIGNED NULL');

            Schema::table('shopee_order_items', function (Blueprint $table) {
                $table->foreign('product_id')
                    ->references('id')
                    ->on('products')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        $this->dropForeignIfExists('sales', 'product_id');

        DB::statement('ALTER TABLE sales MODIFY product_id BIGINT UNSIGNED NOT NULL');

        Schema::table('sales', function (Blueprint $table) {
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->cascadeOnDelete();
        });

        if (Schema::hasTable('shopee_order_items') && Schema::hasColumn('shopee_order_items', 'product_id')) {
            $this->dropForeignIfExists('shopee_order_items', 'product_id');

            DB::statement('ALTER TABLE shopee_order_items MODIFY product_id BIGINT UNSIGNED NOT NULL');

            Schema::table('shopee_order_items', function (Blueprint $table) {
                $table->foreign('product_id')
                    ->references('id')
                    ->on('products')
                    ->cascadeOnDelete();
            });
        }
    }

    private function dropForeignIfExists(string $table, string $column): void
    {
        $database = DB::getDatabaseName();

        $foreign = DB::selectOne(
            "
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = ?
              AND TABLE_NAME = ?
              AND COLUMN_NAME = ?
              AND REFERENCED_TABLE_NAME IS NOT NULL
            LIMIT 1
            ",
            [$database, $table, $column]
        );

        if (!$foreign) {
            return;
        }

        Schema::table($table, function (Blueprint $tableBlueprint) use ($foreign) {
            $tableBlueprint->dropForeign($foreign->CONSTRAINT_NAME);
        });
    }
};
