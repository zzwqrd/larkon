<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $dashboardService;

    /**
     * Inject DashboardService for data aggregation.
     * Mirroring the professional pattern from perfume_admin.
     */
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Main Dashboard View
     */
    public function index()
    {
        // 1. Fetch aggregated data from service
        $data = $this->dashboardService->DashboardData();

        // 2. Return the dashboard view with data
        // Note: The view is usually at dashboards.index (theme default)
        return view('dashboards.index', $data);
    }
}
