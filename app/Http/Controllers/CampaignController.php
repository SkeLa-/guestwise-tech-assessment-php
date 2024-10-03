<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Repositories\DailyMetricRepository;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    protected DailyMetricRepository $dailyMetricRepository;

    public function __construct(DailyMetricRepository $dailyMetricRepository)
    {
        $this->dailyMetricRepository = $dailyMetricRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $filters = $request->only(['brand', 'start_date', 'end_date']);
        $filters['start_date'] = !empty($filters['start_date']) ? $filters['start_date'] : date('Y-m-d', strtotime('-7 days'));
        $filters['end_date'] = !empty($filters['end_date']) ? $filters['end_date'] : date('Y-m-d');
        $sorting = $request->only(['sort_by', 'order_by']);
        $filters['page'] = $request->get('page');
        $campaigns = $this->dailyMetricRepository->getCampaigns(array_merge($filters, $sorting), $perPage);

        return view('campaigns.index', [
            'brands' => Brand::orderBy('name', 'asc')->get(),
            'campaigns' => $campaigns,
            'filters' => $filters,
            'sorting' => $sorting,
            'start_date' => $filters['start_date'],
            'end_date' => $filters['end_date'],
            'sort_by' => $sorting['sort_by'] ?? 'name',
            'order_by' => $sorting['order_by'] ?? 'asc',
        ]);
    }
}
