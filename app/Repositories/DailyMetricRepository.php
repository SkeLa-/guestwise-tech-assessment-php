<?php

namespace App\Repositories;

use App\Models\Campaign;
use App\Models\DailyMetric;
use Illuminate\Support\Facades\Cache;


class DailyMetricRepository
{
    public function getCampaigns($filters, $perPage = 10)
    {
        $startDate = $filters['start_date'] ?? now()->subWeek()->format('Y-m-d');
        $endDate = $filters['end_date'] ?? now()->format('Y-m-d');
        $brandId = $filters['brand'] ?? null;
        $sortBy = $filters['sort_by'] ?? 'campaign_name';
        $orderBy = $filters['order_by'] ?? 'asc';

        $validSortColumns = ['campaign_name', 'brand_name', 'impressions_count', 'interactions_count', 'conversions_count', 'conversion_rate'];

        if (!in_array($sortBy, $validSortColumns)) {
            $sortBy = 'campaign_name';
        }



        $cacheKey = $this->buildCacheKey($filters, $perPage);

        return Cache::remember($cacheKey, 300, function () use ($startDate, $endDate, $brandId, $sortBy, $orderBy, $perPage) {
            return DailyMetric::with('campaign')
                ->whereBetween('date', [$startDate, $endDate])
                ->when($brandId, function ($query) use ($brandId) {
                    return $query->where('brand_id', $brandId);
                })
                ->groupBy('campaign_id', 'brand_id', 'campaign_name', 'brand_name')
                ->selectRaw('campaign_id, brand_id, campaign_name, brand_name, SUM(impressions_count) as impressions_count, SUM(interactions_count) as interactions_count, SUM(conversions_count) as conversions_count, (SUM(conversions_count) / NULLIF(SUM(impressions_count), 0)) as conversion_rate')
                ->orderBy($sortBy, $orderBy)
                ->paginate($perPage)
                ->appends(request()->all());
        });
    }

    private function buildCacheKey($filters, $perPage)
    {
        $startDate = $filters['start_date'] ?? now()->subWeek()->format('Y-m-d');
        $endDate = $filters['end_date'] ?? now()->format('Y-m-d');
        $brandId = $filters['brand'] ?? 'all';
        $page = $filters['page'] ?? '1';
        $sortBy = $filters['sort_by'] ?? 'name';
        $orderBy = $filters['order_by'] ?? 'asc';

        return "daily_metric_{$startDate}_{$endDate}_brand_{$brandId}_sort_{$sortBy}_order_{$orderBy}_perPage_{$perPage}_page_{$page}";
    }
}
