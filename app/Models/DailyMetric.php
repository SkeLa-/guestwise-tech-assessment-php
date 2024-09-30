<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'campaign_name',
        'brand_id',
        'brand_name',
        'date',
        'impressions_count',
        'interactions_count',
        'conversions_count'
    ];

    /**
     * Relationship to the Campaign model.
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
