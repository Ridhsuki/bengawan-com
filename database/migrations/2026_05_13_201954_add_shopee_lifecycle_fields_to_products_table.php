<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('shopee_item_status')->nullable()->after('shopee_publish_status');
            $table->timestamp('shopee_deleted_at')->nullable()->after('shopee_item_status');
            $table->timestamp('shopee_last_checked_at')->nullable()->after('shopee_deleted_at');
            $table->text('shopee_unlinked_reason')->nullable()->after('shopee_last_checked_at');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'shopee_item_status',
                'shopee_deleted_at',
                'shopee_last_checked_at',
                'shopee_unlinked_reason',
            ]);
        });
    }
};
