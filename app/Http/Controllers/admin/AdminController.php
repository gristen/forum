<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;

class AdminController extends Controller
{


    public function index(DashboardService $dashboardService)
    {
        $dashboardData = $dashboardService->getDashboardData();
        debugbar()->info($dashboardData);
        return view('admin.dashboard', compact('dashboardData'));
    }
}
