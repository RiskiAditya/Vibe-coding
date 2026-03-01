<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Services\PenaltyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenaltyController extends Controller
{
    protected PenaltyService $penaltyService;

    public function __construct(PenaltyService $penaltyService)
    {
        $this->penaltyService = $penaltyService;
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Display penalty management dashboard.
     */
    public function index(Request $request): View
    {
        $status = $request->get('status', 'all');
        $search = $request->get('search');
        
        $penalties = $this->penaltyService->getPenalties($status, $search);
        $stats = $this->penaltyService->getPenaltyStats();

        return view('admin.penalties.index', compact('penalties', 'stats', 'status', 'search'));
    }

    /**
     * Mark penalty as paid.
     */
    public function markAsPaid(Borrowing $borrowing): RedirectResponse
    {
        try {
            $this->penaltyService->markAsPaid($borrowing);
            
            return back()->with('success', 'Denda berhasil ditandai sebagai sudah dibayar.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Mark penalty as unpaid.
     */
    public function markAsUnpaid(Borrowing $borrowing): RedirectResponse
    {
        try {
            $this->penaltyService->markAsUnpaid($borrowing);
            
            return back()->with('success', 'Status pembayaran denda berhasil diubah.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
