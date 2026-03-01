<?php

namespace App\Services;

use App\Models\Borrowing;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class ReportService
{
    /**
     * Generate borrowing report with filters.
     */
    public function generateReport(array $filters): Collection
    {
        $query = Borrowing::with(['user', 'equipment.category', 'approver']);

        // Apply date range filter if both dates are provided
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query = $this->filterByDateRange(
                $query,
                Carbon::parse($filters['start_date']),
                Carbon::parse($filters['end_date'])
            );
        }

        // Apply status filter if provided
        if (!empty($filters['status'])) {
            $query = $this->filterByStatus($query, $filters['status']);
        }

        // Apply user filter if provided
        if (!empty($filters['user_id'])) {
            $user = User::find($filters['user_id']);
            if ($user) {
                $query = $this->filterByUser($query, $user);
            }
        }

        return $query->orderBy('borrow_date', 'desc')->get();
    }

    /**
     * Filter by date range.
     */
    public function filterByDateRange($query, Carbon $startDate, Carbon $endDate)
    {
        return $query->whereBetween('borrow_date', [$startDate, $endDate]);
    }

    /**
     * Filter by status.
     */
    public function filterByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Filter by user.
     */
    public function filterByUser($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }
}
