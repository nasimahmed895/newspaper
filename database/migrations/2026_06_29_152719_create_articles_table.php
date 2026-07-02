<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content');
            $table->text('excerpt')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('image_credit')->nullable();
            $table->string('image_source')->nullable(); // unsplash, dalle, or null
            $table->string('author')->nullable();
            $table->string('source')->nullable();
            $table->string('source_url')->nullable();
            $table->string('trending_topic')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->text('seo_keywords')->nullable();
            $table->integer('reading_time_minutes')->default(0);
            $table->unsignedBigInteger('view_count')->default(0);
            $table->boolean('is_trending')->default(false);
            $table->timestamps();

            $table->index('slug');
            $table->index('is_published');
            $table->index('published_at');
            $table->index('category_id');
            $table->index('is_trending');
            $table->index(['is_published', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
