<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->default('Bengawan Computer');
            $table->text('address')->nullable();
            $table->text('about_title')->nullable();
            $table->longText('about_desc')->nullable();
            $table->string('phone')->nullable();
            $table->text('google_maps_link')->nullable();
            $table->json('social_media')->nullable();
            $table->json('banners')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
