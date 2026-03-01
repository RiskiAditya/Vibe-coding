<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Services\ReturnService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReturnController extends Controller
{
    protected ReturnService $returnService;

    public function __construct(ReturnService $returnService)
    {
        $this->returnService = $returnService;
        $this->middleware('auth');
    }

    /**
     * Show the form for initiating a return.
     */
    public function create(Borrowing $borrowing): View
    {
        // Allow staff/admin to process any return, borrower only their own
        if (auth()->user()->role === 'borrower' && $borrowing->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('returns.create', compact('borrowing'));
    }

    /**
     * Process equipment return with condition checking.
     */
    public function store(Request $request, Borrowing $borrowing): RedirectResponse
    {
        // Allow staff/admin to process any return, borrower only their own
        if (auth()->user()->role === 'borrower' && $borrowing->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'return_condition' => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
            'damage_notes' => 'nullable|string|max:1000',
            'repair_cost' => 'nullable|numeric|min:0|max:99999999',
        ]);

        try {
            $this->returnService->processReturn($borrowing, Auth::user(), $request->all());

            return redirect()
                ->route('borrowings.index')
                ->with('success', 'Alat berhasil dikembalikan. Denda keterlambatan: Rp ' . number_format($borrowing->fresh()->late_fee, 0, ',', '.') . ', Total Penalti: Rp ' . number_format($borrowing->fresh()->total_penalty, 0, ',', '.'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show active borrowings for staff to process returns.
     */
    public function index(): View
    {
        // Allow admin and staff to view returns
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $borrowings = $this->returnService->getActiveBorrowings();

        return view('returns.index', compact('borrowings'));
    }
}
