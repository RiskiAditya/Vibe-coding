<?php

namespace App\Services;

use App\Models\Borrowing;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PenaltyService
{
    /**
     * Get penalties with filters.
     */
    public function getPenalties(?string $status = 'unpaid', ?string $search = null): LengthAwarePaginator
    {
        $query = Borrowing::with(['user', 'equipment.category'])
            ->where('status', 'returned')
            ->where('total_penalty', '>', 0);

        // Filter by payment status
        if ($status === 'paid') {
            $query->where('penalty_paid', true);
        } elseif ($status === 'unpaid') {
            $query->where(function($q) {
                $q->where('penalty_paid', false)
                  ->orWhereNull('penalty_paid');
            });
        }

        // Search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('username', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('equipment', function($equipQuery) use ($search) {
                    $equipQuery->where('name', 'like', "%{$search}%");
                });
            });
        }

        return $query->orderBy('actual_return_date', 'desc')->paginate(20);
    }

    /**
     * Get penalty statistics.
     */
    public function getPenaltyStats(): array
    {
        $totalPenalties = Borrowing::where('status', 'returned')
            ->where('total_penalty', '>', 0)
            ->sum('total_penalty');

        $unpaidPenalties = Borrowing::where('status', 'returned')
            ->where('total_penalty', '>', 0)
            ->where(function($q) {
                $q->where('penalty_paid', false)
                  ->orWhereNull('penalty_paid');
            })
            ->sum('total_penalty');

        $paidPenalties = Borrowing::where('status', 'returned')
            ->where('total_penalty', '>', 0)
            ->where('penalty_paid', true)
            ->sum('total_penalty');

        $unpaidCount = Borrowing::where('status', 'returned')
            ->where('total_penalty', '>', 0)
            ->where(function($q) {
                $q->where('penalty_paid', false)
                  ->orWhereNull('penalty_paid');
            })
            ->count();

        $paidCount = Borrowing::where('status', 'returned')
            ->where('total_penalty', '>', 0)
            ->where('penalty_paid', true)
            ->count();

        return [
            'total_penalties' => $totalPenalties,
            'unpaid_penalties' => $unpaidPenalties,
            'paid_penalties' => $paidPenalties,
            'unpaid_count' => $unpaidCount,
            'paid_count' => $paidCount,
            'total_count' => $unpaidCount + $paidCount,
        ];
    }

    /**
     * Mark penalty as paid.
     */
    public function markAsPaid(Borrowing $borrowing): Borrowing
    {
        if ($borrowing->status !== 'returned') {
            throw new \Exception('Hanya peminjaman yang sudah dikembalikan yang dapat ditandai sebagai dibayar.');
        }

        if ($borrowing->total_penalty <= 0) {
            throw new \Exception('Tidak ada denda untuk peminjaman ini.');
        }

        $borrowing->update([
            'penalty_paid' => true,
            'penalty_paid_at' => now(),
        ]);

        return $borrowing->fresh();
    }

    /**
     * Mark penalty as unpaid.
     */
    public function markAsUnpaid(Borrowing $borrowing): Borrowing
    {
        if ($borrowing->status !== 'returned') {
            throw new \Exception('Hanya peminjaman yang sudah dikembalikan yang dapat diubah status pembayarannya.');
        }

        $borrowing->update([
            'penalty_paid' => false,
            'penalty_paid_at' => null,
        ]);

        return $borrowing->fresh();
    }
}
