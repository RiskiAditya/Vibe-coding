<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsService;
use App\Services\ActivityLogService;
use App\Services\BorrowingService;
use App\Services\EquipmentService;
use App\Services\PenaltyService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    protected AnalyticsService $analyticsService;
    protected ActivityLogService $activityLogService;
    protected BorrowingService $borrowingService;
    protected EquipmentService $equipmentService;
    protected PenaltyService $penaltyService;

    public function __construct(
        AnalyticsService $analyticsService,
        ActivityLogService $activityLogService,
        BorrowingService $borrowingService,
        EquipmentService $equipmentService,
        PenaltyService $penaltyService
    ) {
        $this->analyticsService = $analyticsService;
        $this->activityLogService = $activityLogService;
        $this->borrowingService = $borrowingService;
        $this->equipmentService = $equipmentService;
        $this->penaltyService = $penaltyService;
        $this->middleware('auth');
    }

    /**
     * Display admin dashboard.
     */
    public function admin(): View
    {
        $stats = [
            'total_users' => $this->analyticsService->getTotalUsers(),
            'total_equipment' => $this->analyticsService->getTotalEquipment(),
            'active_borrowings' => $this->analyticsService->getActiveBorrowings(),
            'pending_requests' => $this->analyticsService->getPendingRequests(),
        ];

        $categoryStats = $this->analyticsService->getCategoryStatistics();
        $recentActivity = $this->activityLogService->getRecentLogs(10);

        return view('admin.dashboard', compact(
            'stats',
            'categoryStats',
            'recentActivity'
        ));
    }

    /**
     * Display staff dashboard.
     */
    public function staff(): View
    {
        $stats = [
            'total_users' => $this->analyticsService->getTotalUsers(),
            'total_equipment' => $this->analyticsService->getTotalEquipment(),
            'active_borrowings' => $this->analyticsService->getActiveBorrowings(),
            'pending_requests' => $this->analyticsService->getPendingRequests(),
        ];

        $borrowingTrends = $this->analyticsService->getBorrowingTrends(30);
        $equipmentUtilization = $this->analyticsService->getEquipmentUtilization();
        $categoryStats = $this->analyticsService->getCategoryStatistics();
        $statusDistribution = $this->analyticsService->getEquipmentStatusDistribution();
        $penaltyStats = $this->penaltyService->getPenaltyStats();
        
        // Chart data
        $monthlyTrends = $this->analyticsService->getMonthlyBorrowingTrends();
        $mostBorrowed = $this->analyticsService->getMostBorrowedEquipment(10);
        $borrowingStatus = $this->analyticsService->getBorrowingStatusDistribution();
        $equipmentByCategory = $this->analyticsService->getEquipmentByCategory();

        return view('staff.dashboard', compact(
            'stats',
            'borrowingTrends',
            'equipmentUtilization',
            'categoryStats',
            'statusDistribution',
            'penaltyStats',
            'monthlyTrends',
            'mostBorrowed',
            'borrowingStatus',
            'equipmentByCategory'
        ));
    }

    /**
     * Display borrower dashboard.
     */
    public function borrower(): View
    {
        $availableEquipment = $this->equipmentService->getAvailableEquipment();
        $myBorrowings = $this->borrowingService->getUserBorrowings(Auth::user());

        return view('borrower.dashboard', compact('availableEquipment', 'myBorrowings'));
    }
}
