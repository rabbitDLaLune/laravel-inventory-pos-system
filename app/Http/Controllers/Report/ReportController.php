<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Services\ReportService;

class ReportController extends Controller
{
    /**
     * Display reports page.
     */
    public function index(ReportService $reportService)
    {
        return view('reports.index', $reportService->summary());
    }
}
