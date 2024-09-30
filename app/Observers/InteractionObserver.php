<?php

namespace App\Observers;

use App\Models\Interaction;
use App\Models\DailyMetric;
use Carbon\Carbon;

class InteractionObserver
{
    public function created(Interaction $interaction)
    {
        $date = Carbon::parse($interaction->occurred_at)->toDateString();
        $campaign = $interaction->campaign;
        $brand = $interaction->brand;
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
                'interactions_count' => 1,
            ]);
        }
    }
}
