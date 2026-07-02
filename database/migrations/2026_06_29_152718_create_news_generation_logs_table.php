<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_generation_logs', function (Blueprint $table) {
            $table->id();
            $table->string('topic')->nullable();
            $table->string('status'); // success, partial, failed
            $table->integer('articles_count')->default(0);
            $table->text('error_message')->nullable();
            $table->json('response_data')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_generation_logs');
    }
};
