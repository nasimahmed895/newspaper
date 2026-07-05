<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // pending = submitted via API, awaiting review
            // approved = admin approved, will be published
            // published = live on site
            // rejected = admin rejected
            $table->string('status', 20)->default('published')->after('is_published');
            $table->unsignedBigInteger('api_partner_id')->nullable()->after('status');
            $table->foreign('api_partner_id')->references('id')->on('api_partners')->nullOnDelete();
            $table->string('submitted_by_name')->nullable()->after('api_partner_id');
            $table->string('submitted_by_email')->nullable()->after('submitted_by_name');
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign(['api_partner_id']);
            $table->dropColumn(['status', 'api_partner_id', 'submitted_by_name', 'submitted_by_email']);
        });
    }
};
