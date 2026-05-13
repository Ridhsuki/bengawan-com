<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('shopee_category_id')->nullable()->after('shopee_sku');
            $table->unsignedBigInteger('shopee_brand_id')->default(0)->after('shopee_category_id');
            $table->string('shopee_brand_name')->default('NoBrand')->after('shopee_brand_id');

            $table->string('shopee_condition')->default('NEW')->after('shopee_brand_name');
            $table->decimal('shopee_weight', 8, 2)->nullable()->after('shopee_condition');

            $table->integer('shopee_package_length')->nullable()->after('shopee_weight');
            $table->integer('shopee_package_width')->nullable()->after('shopee_package_length');
            $table->integer('shopee_package_height')->nullable()->after('shopee_package_width');

            $table->unsignedBigInteger('shopee_logistic_id')->nullable()->after('shopee_package_height');
            $table->string('shopee_publish_status')->nullable()->after('shopee_logistic_id');
            $table->text('shopee_publish_error')->nullable()->after('shopee_publish_status');
            $table->timestamp('shopee_published_at')->nullable()->after('shopee_publish_error');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'shopee_category_id',
                'shopee_brand_id',
                'shopee_brand_name',
                'shopee_condition',
                'shopee_weight',
                'shopee_package_length',
                'shopee_package_width',
                'shopee_package_height',
                'shopee_logistic_id',
                'shopee_publish_status',
                'shopee_publish_error',
                'shopee_published_at',
            ]);
        });
    }
};
