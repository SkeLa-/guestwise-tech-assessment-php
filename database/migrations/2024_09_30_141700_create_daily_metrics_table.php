<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('daily_metrics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campaign_id');
            $table->string('campaign_name');
            $table->unsignedBigInteger('brand_id');
            $table->string('brand_name');
            $table->date('date');
            $table->integer('impressions_count')->default(0);
            $table->integer('interactions_count')->default(0);
            $table->integer('conversions_count')->default(0);

            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');

            $table->unique(['campaign_id', 'date']);
            $table->index('campaign_name');
            $table->index('brand_name');
            $table->index('date');
            $table->index('impressions_count');
            $table->index('interactions_count');
            $table->index('conversions_count');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('daily_metrics');
    }
};
