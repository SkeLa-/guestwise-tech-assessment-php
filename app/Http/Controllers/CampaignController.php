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

        $filters = $request->only(['brand', 'start_date', 'end_date', 'sort_by', 'order_by']);

        $campaigns = $this->dailyMetricRepository->getCampaigns($filters, $perPage);

        return view('campaigns.index', [
            'brands' => Brand::orderBy('name', 'asc')->get(),
            'campaigns' => $campaigns,
            'start_date' => request('start_date', date('Y-m-d', strtotime('-7 days'))),
            'end_date' => request('end_date', date('Y-m-d')),
            'sort_by' => request('sort_by') ?? 'name',
            'order_by' => request('order_by') ?? 'asc',
        ]);
    }
}
