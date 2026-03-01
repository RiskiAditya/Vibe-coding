<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
        $this->middleware('auth');
        $this->middleware('role:staff');
    }

    /**
     * Display the report generation form.
     */
    public function index(Request $request): View
    {
        $filters = $request->only(['start_date', 'end_date', 'status', 'user_id']);
        
        // Always show borrowings - either filtered or all
        $borrowings = $this->reportService->generateReport($filters);

        $users = User::orderBy('username')->get();

        return view('reports.index', compact('borrowings', 'users', 'filters'));
    }

    /**
     * Display print-friendly report.
     */
    public function print(Request $request): View
    {
        $filters = $request->only(['start_date', 'end_date', 'status', 'user_id']);
        $borrowings = $this->reportService->generateReport($filters);

        return view('reports.print', compact('borrowings', 'filters'));
    }
}
