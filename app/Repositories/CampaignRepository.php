<?php

namespace App\Repositories;

use App\Models\Campaign;

class CampaignRepository
{
    public function getCampaigns($filters, $perPage = 10)
    {
        $startDate = $filters['start_date'] ?? now()->subWeek()->format('Y-m-d');
        $endDate = $filters['end_date'] ?? now()->format('Y-m-d');
        $brandId = $filters['brand'] ?? null;
        $sortBy = $filters['sort_by'] ?? 'name';
        $orderBy = $filters['order_by'] ?? 'asc';

        return Campaign::with(['brand:id,name'])
            ->withCount([
                'impressions' => function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('occurred_at', [$startDate, $endDate]);
                },
                'interactions' => function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('occurred_at', [$startDate, $endDate]);
                },
                'conversions' => function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('occurred_at', [$startDate, $endDate]);
                }
            ])
            ->leftJoin('brands', 'campaigns.brand_id', '=', 'brands.id')
            ->when($brandId, function ($query) use ($brandId) {
                return $query->where('brand_id', $brandId);
            })
            ->when($sortBy === 'brand_name', function ($query) use ($orderBy) {
                return $query->orderBy('brands.name', $orderBy);
            }, function ($query) use ($sortBy, $orderBy) {
                return $query->orderBy($sortBy, $orderBy);
            })
            ->paginate($perPage)
            ->appends(request()->all());
    }
}
