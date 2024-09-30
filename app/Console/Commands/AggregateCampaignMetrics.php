<?php
namespace App\Console\Commands;

use App\Models\DailyMetric;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Campaign;
use Carbon\Carbon;

class AggregateCampaignMetrics extends Command
{
    protected $signature = 'metrics:aggregate';

    protected $description = 'Aggregate campaign metrics and store them in the daily_metrics table';

    public function handle()
    {
        $startDate = $this->getEarliestDate();

        $endDate = $this->getLatestDate();

        while ($startDate->lte($endDate)) {
            $this->info("Processing metrics for: " . $startDate->toDateString());

            Campaign::with('brand')->each(function ($campaign) use ($startDate) {
                $startOfDay = $startDate->startOfDay()->toDateTimeString();
                $endOfDay = $startDate->endOfDay()->toDateTimeString();

                $impressions = $campaign->impressions()
                    ->whereBetween('occurred_at', [$startOfDay, $endOfDay])
                    ->get();

                $interactions = $campaign->interactions()
                    ->whereBetween('occurred_at', [$startOfDay, $endOfDay])
                    ->get();

                $conversions = $campaign->conversions()
                    ->whereBetween('occurred_at', [$startOfDay, $endOfDay])
                    ->get();

                DailyMetric::updateOrCreate(
                    [
                        'campaign_id' => $campaign->id,
                        'date' => $startDate->toDateString(),
                    ],
                    [
                        'campaign_name' => $campaign->name,
                        'brand_id' => $campaign->brand->id,
                        'brand_name' => $campaign->brand->name,
                        'impressions_count' => $impressions->count(),
                        'interactions_count' => $interactions->count(),
                        'conversions_count' => $conversions->count(),
                        'updated_at' => now(),
                    ]
                );
            });

            $startDate->addDay();
        }

        $this->info("Metrics aggregation completed.");
    }

    private function getEarliestDate()
    {
        $impressionsDate = DB::table('impressions')->min('occurred_at');
        $interactionsDate = DB::table('interactions')->min('occurred_at');
        $conversionsDate = DB::table('conversions')->min('occurred_at');

        $earliestDate = collect([$impressionsDate, $interactionsDate, $conversionsDate])
            ->filter()
            ->min();

        return Carbon::parse($earliestDate);
    }

    private function getLatestDate()
    {
        $impressionsDate = DB::table('impressions')->max('occurred_at');
        $interactionsDate = DB::table('interactions')->max('occurred_at');
        $conversionsDate = DB::table('conversions')->max('occurred_at');

        $latestDate = collect([$impressionsDate, $interactionsDate, $conversionsDate])
            ->filter()
            ->max();

        return Carbon::parse($latestDate);
    }
}
