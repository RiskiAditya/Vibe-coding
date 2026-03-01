<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    /**
     * Log a user action
     *
     * @param User $user The user performing the action
     * @param string $action The action being performed
     * @param string|null $details Additional details about the action
     * @return ActivityLog
     */
    public function log(User $user, string $action, ?string $details = null): ActivityLog
    {
        return ActivityLog::create([
            'user_id' => $user->id,
            'action' => $action,
            'details' => $details,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Log a system action (no user associated)
     *
     * @param string $action The action being performed
     * @param string|null $details Additional details about the action
     * @return ActivityLog
     */
    public function logSystem(string $action, ?string $details = null): ActivityLog
    {
        return ActivityLog::create([
            'user_id' => null,
            'action' => $action,
            'details' => $details,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Get all activity logs for a specific user
     *
     * @param User $user
     * @return Collection
     */
    public function getUserLogs(User $user): Collection
    {
        return ActivityLog::where('user_id', $user->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get recent activity logs
     *
     * @param int $limit Number of logs to retrieve (default 50)
     * @return Collection
     */
    public function getRecentLogs(int $limit = 50): Collection
    {
        return ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Filter activity logs based on criteria
     *
     * @param array $filters Array of filter criteria
     *   - user_id: Filter by user ID
     *   - action: Filter by action type
     *   - date_from: Filter by start date
     *   - date_to: Filter by end date
     * @return Collection
     */
    public function filterLogs(array $filters): Collection
    {
        $query = ActivityLog::with('user');

        // Filter by user
        if (isset($filters['user_id']) && $filters['user_id']) {
            $query->where('user_id', $filters['user_id']);
        }

        // Filter by action type
        if (isset($filters['action']) && $filters['action']) {
            $query->where('action', 'like', '%' . $filters['action'] . '%');
        }

        // Filter by date range
        if (isset($filters['date_from']) && $filters['date_from']) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to']) && $filters['date_to']) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get paginated activity logs
     *
     * @param int $perPage Number of logs per page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginatedLogs(int $perPage = 20)
    {
        return ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get activity logs for a specific action type
     *
     * @param string $action
     * @return Collection
     */
    public function getLogsByAction(string $action): Collection
    {
        return ActivityLog::where('action', $action)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Delete old activity logs (older than specified days)
     *
     * @param int $days Number of days to keep
     * @return int Number of deleted records
     */
    public function deleteOldLogs(int $days = 90): int
    {
        return ActivityLog::where('created_at', '<', now()->subDays($days))->delete();
    }
}
