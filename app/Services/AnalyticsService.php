<?php

namespace App\Services;

use App\Models\User;
use App\Models\Equipment;
use App\Models\Borrowing;
use App\Models\Category;
use Carbon\Carbon;

class AnalyticsService
{
    /**
     * Get total users count.
     */
    public function getTotalUsers(): int
    {
        return User::count();
    }

    /**
     * Get total equipment count.
     */
    public function getTotalEquipment(): int
    {
        return Equipment::count();
    }

    /**
     * Get active borrowings count.
     */
    public function getActiveBorrowings(): int
    {
        return Borrowing::where('status', 'approved')->count();
    }

    /**
     * Get pending requests count.
     */
    public function getPendingRequests(): int
    {
        return Borrowing::where('status', 'pending')->count();
    }

    /**
     * Get borrowing trends over time.
     */
    public function getBorrowingTrends(int $days = 30): array
    {
        $startDate = Carbon::now()->subDays($days);
        
        $borrowings = Borrowing::where('borrow_date', '>=', $startDate)
            ->selectRaw('DATE(borrow_date) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $trends = [];
        for ($i = $days; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $trends[$date] = 0;
        }

        foreach ($borrowings as $borrowing) {
            $trends[$borrowing->date] = $borrowing->count;
        }

        return $trends;
    }

    /**
     * Get equipment utilization by category.
     */
    public function getEquipmentUtilization(): array
    {
        $categories = Category::withCount('equipment')->get();
        $utilization = [];

        foreach ($categories as $category) {
            $totalEquipment = $category->equipment_count;
            $borrowedCount = Borrowing::whereHas('equipment', function ($query) use ($category) {
                $query->where('category_id', $category->id);
            })->where('status', 'approved')->count();

            $utilization[] = [
                'category' => $category->name,
                'total' => $totalEquipment,
                'borrowed' => $borrowedCount,
                'rate' => $totalEquipment > 0 ? round(($borrowedCount / $totalEquipment) * 100, 2) : 0,
            ];
        }

        return $utilization;
    }

    /**
     * Get category statistics.
     */
    public function getCategoryStatistics(): array
    {
        return Category::withCount('equipment')
            ->get()
            ->map(function ($category) {
                return [
                    'name' => $category->name,
                    'count' => $category->equipment_count,
                ];
            })
            ->toArray();
    }

    /**
     * Get equipment status distribution.
     */
    public function getEquipmentStatusDistribution(): array
    {
        return Equipment::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();
    }

    /**
     * Get monthly borrowing trends (last 6 months).
     */
    public function getMonthlyBorrowingTrends(): array
    {
        $data = Borrowing::where('created_at', '>=', now()->subMonths(6))
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'labels' => $data->pluck('month')->toArray(),
            'values' => $data->pluck('count')->toArray(),
        ];
    }

    /**
     * Get most borrowed equipment (top 10).
     */
    public function getMostBorrowedEquipment(int $limit = 10): array
    {
        $data = Borrowing::select('equipment_id')
            ->selectRaw('COUNT(*) as borrow_count')
            ->with('equipment')
            ->groupBy('equipment_id')
            ->orderBy('borrow_count', 'desc')
            ->limit($limit)
            ->get();

        return [
            'labels' => $data->pluck('equipment.name')->toArray(),
            'values' => $data->pluck('borrow_count')->toArray(),
        ];
    }

    /**
     * Get borrowing status distribution.
     */
    public function getBorrowingStatusDistribution(): array
    {
        return Borrowing::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();
    }

    /**
     * Get equipment by category for pie chart.
     */
    public function getEquipmentByCategory(): array
    {
        $data = Category::withCount('equipment')
            ->having('equipment_count', '>', 0)
            ->get();

        return [
            'labels' => $data->pluck('name')->toArray(),
            'values' => $data->pluck('equipment_count')->toArray(),
        ];
    }
}
