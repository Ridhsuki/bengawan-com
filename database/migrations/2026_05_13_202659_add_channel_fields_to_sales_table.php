<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->string('sales_channel')->default('internal')->after('id');
            $table->string('external_order_sn')->nullable()->after('sales_channel');
            $table->unsignedBigInteger('external_item_id')->nullable()->after('external_order_sn');
            $table->unsignedBigInteger('external_model_id')->nullable()->after('external_item_id');
            $table->string('external_status')->nullable()->after('external_model_id');
            $table->json('external_payload')->nullable()->after('external_status');
            $table->timestamp('external_synced_at')->nullable()->after('external_payload');

            $table->index(['sales_channel', 'external_order_sn']);
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropIndex(['sales_channel', 'external_order_sn']);

            $table->dropColumn([
                'sales_channel',
                'external_order_sn',
                'external_item_id',
                'external_model_id',
                'external_status',
                'external_payload',
                'external_synced_at',
            ]);
        });
    }
};
