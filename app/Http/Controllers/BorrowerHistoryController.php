<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BorrowerHistoryController extends Controller
{
    /**
     * Display list of all borrowers with their statistics.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'borrower');

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $borrowers = $query->withCount([
            'borrowings as total_borrowings',
            'borrowings as active_borrowings' => function($q) {
                $q->where('status', 'approved');
            },
            'borrowings as completed_borrowings' => function($q) {
                $q->where('status', 'returned');
            },
            'borrowings as damaged_count' => function($q) {
                $q->where('status', 'returned')
                  ->whereIn('return_condition', ['rusak_ringan', 'rusak_berat']);
            },
            'borrowings as lost_count' => function($q) {
                $q->where('status', 'returned')
                  ->where('return_condition', 'hilang');
            },
            'borrowings as late_count' => function($q) {
                $q->where('status', 'returned')
                  ->whereRaw('actual_return_date > requested_return_date');
            }
        ])
        ->withSum('borrowings as total_penalties', 'total_penalty')
        ->orderBy('total_borrowings', 'desc')
        ->paginate(20);

        return view('admin.borrower-history.index', compact('borrowers'));
    }

    /**
     * Display detailed history for a specific borrower.
     */
    public function show(User $user)
    {
        if ($user->role !== 'borrower') {
            abort(404);
        }

        // Statistics
        $stats = [
            'total_borrowings' => $user->borrowings()->count(),
            'active_borrowings' => $user->borrowings()->where('status', 'approved')->count(),
            'completed_borrowings' => $user->borrowings()->where('status', 'returned')->count(),
            'damaged_count' => $user->borrowings()
                ->where('status', 'returned')
                ->whereIn('return_condition', ['rusak_ringan', 'rusak_berat'])
                ->count(),
            'lost_count' => $user->borrowings()
                ->where('status', 'returned')
                ->where('return_condition', 'hilang')
                ->count(),
            'late_count' => $user->borrowings()
                ->where('status', 'returned')
                ->whereRaw('actual_return_date > requested_return_date')
                ->count(),
            'total_penalties' => $user->borrowings()->sum('total_penalty'),
            'unpaid_penalties' => $user->borrowings()
                ->where('penalty_paid', false)
                ->where('total_penalty', '>', 0)
                ->sum('total_penalty'),
        ];

        // Recent borrowings
        $recentBorrowings = $user->borrowings()
            ->with(['equipment.category'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Monthly borrowing trend (last 6 months)
        $monthlyTrend = $user->borrowings()
            ->where('created_at', '>=', now()->subMonths(6))
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.borrower-history.show', compact('user', 'stats', 'recentBorrowings', 'monthlyTrend'));
    }
}
