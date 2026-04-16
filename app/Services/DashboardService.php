<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\Role;
use App\Models\Product;
use App\Models\User;
use App\Models\PerfumeOrder;
use App\Models\PerfumeCategory;
use App\Models\ActivityLog;
use Carbon\Carbon;

class DashboardService
{
    /**
     * Aggregate all data required for the admin dashboard.
     * Integrates professional business modules: Orders, Products, and Activity Logs.
     */
    public function DashboardData()
    {
        // 1. Core Business Stats
        $totalOrders = PerfumeOrder::count();
        $totalProducts = Product::count();
        $totalCategories = PerfumeCategory::count();
        $totalUsers = User::count();

        // 2. Growth/Performance (Example: Orders this month vs Previous)
        $thisMonthStart = Carbon::now()->startOfMonth();
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        $newOrdersThisMonth = PerfumeOrder::where('created_at', '>=', $thisMonthStart)->count();
        $newOrdersLastMonth = PerfumeOrder::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();

        $orderGrowth = $newOrdersLastMonth > 0
            ? round((($newOrdersThisMonth - $newOrdersLastMonth) / $newOrdersLastMonth) * 100, 1)
            : ($newOrdersThisMonth > 0 ? 100 : 0);

        // 3. Chart Data (Last 6 Months Order Trend)
        $chartLabels = [];
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $chartLabels[] = $month->format('M');
            $chartData[] = PerfumeOrder::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        // 4. Recent Professional Audit Trail (Activity Logs)
        $recentActivities = ActivityLog::with('userable')->latest()->limit(10)->get();

        return [
            'stats' => [
                'orders' => $totalOrders,
                'products' => $totalProducts,
                'categories' => $totalCategories,
                'users' => $totalUsers,
            ],
            'performance' => [
                'order_growth' => $orderGrowth,
                'new_orders_this_month' => $newOrdersThisMonth,
            ],
            'charts' => [
                'labels' => $chartLabels,
                'data' => $chartData,
            ],
            'recentActivities' => $recentActivities,
            'colors' => ['primary', 'success', 'info', 'warning', 'danger'],
        ];
    }
}
