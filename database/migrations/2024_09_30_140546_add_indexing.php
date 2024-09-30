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
        Schema::table('impressions', function (Blueprint $table) {
            $table->index('campaign_id');
            $table->index('brand_id');
            $table->index('occurred_at');
            $table->index(['campaign_id', 'brand_id', 'occurred_at']);
        });

        Schema::table('interactions', function (Blueprint $table) {
            $table->index('campaign_id');
            $table->index('brand_id');
            $table->index('occurred_at');
            $table->index(['campaign_id', 'brand_id', 'occurred_at']);
        });

        Schema::table('conversions', function (Blueprint $table) {
            $table->index('campaign_id');
            $table->index('brand_id');
            $table->index('occurred_at');
            $table->index(['campaign_id', 'brand_id', 'occurred_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('impressions', function (Blueprint $table) {
            $table->dropIndex(['campaign_id']);
            $table->dropIndex(['brand_id']);
            $table->dropIndex(['occurred_at']);
            $table->dropIndex(['campaign_id', 'brand_id', 'occurred_at']);
        });

        Schema::table('interactions', function (Blueprint $table) {
            $table->dropIndex(['campaign_id']);
            $table->dropIndex(['brand_id']);
            $table->dropIndex(['occurred_at']);
            $table->dropIndex(['campaign_id', 'brand_id', 'occurred_at']);
        });

        Schema::table('conversions', function (Blueprint $table) {
            $table->dropIndex(['campaign_id']);
            $table->dropIndex(['brand_id']);
            $table->dropIndex(['occurred_at']);
            $table->dropIndex(['campaign_id', 'brand_id', 'occurred_at']);
        });
    }
};
