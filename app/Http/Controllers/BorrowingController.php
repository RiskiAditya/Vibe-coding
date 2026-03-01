<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Equipment;
use App\Services\BorrowingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BorrowingController extends Controller
{
    protected BorrowingService $borrowingService;

    public function __construct(BorrowingService $borrowingService)
    {
        $this->borrowingService = $borrowingService;
        $this->middleware('auth');
    }

    /**
     * Display a listing of borrowing requests.
     */
    public function index(): View
    {
        $user = Auth::user();

        if ($user->role === 'borrower') {
            $borrowings = $this->borrowingService->getUserBorrowings($user);
        } else {
            $borrowings = Borrowing::with(['user', 'equipment.category', 'approver'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        }

        return view('borrowings.index', compact('borrowings'));
    }

    /**
     * Show the form for creating a new borrowing request.
     */
    public function create(Request $request): View
    {
        $equipmentId = $request->get('equipment_id');
        $equipment = Equipment::with('category')->findOrFail($equipmentId);

        return view('borrowings.create', compact('equipment'));
    }

    /**
     * Store a newly created borrowing request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'requested_return_date' => 'required|date|after:today',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $equipment = Equipment::findOrFail($request->equipment_id);
            $this->borrowingService->createBorrowingRequest(
                Auth::user(),
                $equipment,
                $request->all()
            );

            return redirect()
                ->route('borrowings.index')
                ->with('success', 'Permintaan peminjaman berhasil diajukan.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Approve a borrowing request.
     */
    public function approve(Borrowing $borrowing): RedirectResponse
    {
        // Check if user is admin or staff
        if (!in_array(Auth::user()->role, ['admin', 'staff'])) {
            abort(403, 'This action is unauthorized.');
        }

        try {
            $this->borrowingService->approveBorrowing($borrowing, Auth::user());

            return redirect()
                ->route('borrowings.index')
                ->with('success', 'Permintaan peminjaman berhasil disetujui.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Reject a borrowing request.
     */
    public function reject(Borrowing $borrowing): RedirectResponse
    {
        // Check if user is admin or staff
        if (!in_array(Auth::user()->role, ['admin', 'staff'])) {
            abort(403, 'This action is unauthorized.');
        }

        try {
            $this->borrowingService->rejectBorrowing($borrowing, Auth::user());

            return redirect()
                ->route('borrowings.index')
                ->with('success', 'Permintaan peminjaman ditolak.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
