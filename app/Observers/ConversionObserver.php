<?php

namespace App\Observers;

use App\Models\Conversion;
use App\Models\DailyMetric;
use Carbon\Carbon;

class ConversionObserver
{
    public function created(Conversion $conversion)
    {
        $date = Carbon::parse($conversion->occurred_at)->toDateString();
        $campaign = $conversion->campaign;
        $brand = $conversion->brand;
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
                'conversions_count' => 1,
            ]);
        }
    }
}
