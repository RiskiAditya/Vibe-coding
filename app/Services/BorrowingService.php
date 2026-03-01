<?php

namespace App\Services;

use App\Models\Borrowing;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class BorrowingService
{
    protected EquipmentService $equipmentService;

    public function __construct(EquipmentService $equipmentService)
    {
        $this->equipmentService = $equipmentService;
    }

    /**
     * Create a borrowing request.
     */
    public function createBorrowingRequest(User $borrower, Equipment $equipment, array $data): Borrowing
    {
        if (!$this->canBorrowEquipment($equipment)) {
            throw new \Exception('Alat tidak tersedia untuk dipinjam atau stock habis.');
        }

        if ($this->hasPendingRequest($borrower, $equipment)) {
            throw new \Exception('Anda sudah memiliki permintaan yang tertunda untuk alat ini.');
        }

        if ($this->hasUnreturnedEquipment($borrower)) {
            throw new \Exception('Anda harus mengembalikan alat yang sedang dipinjam sebelum meminjam alat baru.');
        }

        return Borrowing::create([
            'user_id' => $borrower->id,
            'equipment_id' => $equipment->id,
            'borrow_date' => Carbon::now(),
            'requested_return_date' => $data['requested_return_date'],
            'status' => 'pending',
            'notes' => $data['notes'] ?? null,
        ]);
    }

    /**
     * Approve a borrowing request.
     */
    public function approveBorrowing(Borrowing $borrowing, User $approver): Borrowing
    {
        if ($borrowing->status !== 'pending') {
            throw new \Exception('Hanya permintaan yang tertunda yang dapat disetujui.');
        }

        // Check if stock is available
        if ($borrowing->equipment->available_stock <= 0) {
            throw new \Exception('Stock alat tidak tersedia.');
        }

        $borrowing->update([
            'status' => 'approved',
            'approved_by' => $approver->id,
        ]);

        // Decrease available stock
        $borrowing->equipment->decrement('available_stock');

        // Update equipment status to borrowed if no more stock available
        if ($borrowing->equipment->available_stock <= 0) {
            $this->equipmentService->updateStatus($borrowing->equipment, 'borrowed');
        }

        return $borrowing->fresh();
    }

    /**
     * Reject a borrowing request.
     */
    public function rejectBorrowing(Borrowing $borrowing, User $approver): Borrowing
    {
        if ($borrowing->status !== 'pending') {
            throw new \Exception('Hanya permintaan yang tertunda yang dapat ditolak.');
        }

        $borrowing->update([
            'status' => 'rejected',
            'approved_by' => $approver->id,
        ]);

        return $borrowing->fresh();
    }

    /**
     * Check if equipment can be borrowed.
     */
    public function canBorrowEquipment(Equipment $equipment): bool
    {
        return $equipment->status === 'available' && $equipment->available_stock > 0;
    }

    /**
     * Check if user has pending request for equipment.
     */
    public function hasPendingRequest(User $user, Equipment $equipment): bool
    {
        return Borrowing::where('user_id', $user->id)
            ->where('equipment_id', $equipment->id)
            ->where('status', 'pending')
            ->exists();
    }

    /**
     * Check if user has unreturned equipment.
     */
    public function hasUnreturnedEquipment(User $user): bool
    {
        return Borrowing::where('user_id', $user->id)
            ->where('status', 'approved')
            ->exists();
    }

    /**
     * Get pending requests.
     */
    public function getPendingRequests(): Collection
    {
        return Borrowing::with(['user', 'equipment.category'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get user borrowings.
     */
    public function getUserBorrowings(User $user)
    {
        return Borrowing::with(['equipment.category', 'approver'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    /**
     * Get active borrowings.
     */
    public function getActiveBorrowings(): Collection
    {
        return Borrowing::with(['user', 'equipment.category'])
            ->where('status', 'approved')
            ->orderBy('borrow_date', 'desc')
            ->get();
    }
}
