<?php

namespace App\Observers;

use App\Models\Impression;
use App\Models\DailyMetric;
use Carbon\Carbon;

class ImpressionObserver
{
    public function created(Impression $impression)
    {
        $date = Carbon::parse($impression->occurred_at)->toDateString();
        $campaign = $impression->campaign;
        $brand = $impression->brand;
        $dailyMetric = DailyMetric::where('campaign_id', $campaign->id)
            ->where('date', $date)
            ->first();

        if ($dailyMetric) {
            $dailyMetric->increment('conversions_count');
        } else {
            DailyMetric::create([
                'campaign_id' => $campaign->id,
                'campaign_name' => $campaign->name,
                'brand_id' => $brand->id,
                'brand_name' => $brand->name,
                'date' => $date,
                'impressions_count' => 1,
            ]);
        }
    }
}
