<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->nullable()->constrained()->nullOnDelete();
            $table->string('session_id', 64)->index();
            $table->string('ip_hash', 64)->index();
            $table->string('page_type', 20)->default('other'); // article|home|category|search|other
            $table->string('device_type', 10)->default('desktop'); // desktop|mobile|tablet
            $table->string('browser', 40)->default('other');
            $table->string('os', 30)->default('other');
            $table->string('referrer_source', 20)->default('direct'); // direct|organic|social|referral
            $table->timestamp('created_at')->useCurrent()->index();

            $table->index(['article_id', 'created_at']);
            $table->index(['ip_hash', 'created_at']);
            $table->index(['device_type', 'created_at']);
            $table->index(['referrer_source', 'created_at']);
            $table->index(['page_type', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};
