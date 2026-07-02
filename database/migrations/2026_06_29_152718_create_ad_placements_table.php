<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad_placements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location')->unique(); // sidebar-top, sidebar-bottom, header, footer, in-article, between-articles
            $table->text('code')->nullable(); // HTML/JS ad code
            $table->string('image_url')->nullable();
            $table->text('link_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->index('location');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_placements');
    }
};
