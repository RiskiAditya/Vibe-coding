<?php

namespace App\Services;

use App\Models\Borrowing;
use App\Models\Equipment;
use App\Models\User;
use Carbon\Carbon;

class ReturnService
{
    protected EquipmentService $equipmentService;

    public function __construct(EquipmentService $equipmentService)
    {
        $this->equipmentService = $equipmentService;
    }

    /**
     * Process equipment return.
     */
    public function processReturn(Borrowing $borrowing, User $returner, array $data = []): Borrowing
    {
        if ($borrowing->status !== 'approved') {
            throw new \Exception('Hanya peminjaman yang disetujui yang dapat dikembalikan.');
        }

        // Calculate late days and fee
        $lateDays = $borrowing->calculateLateDays();
        $lateFee = $borrowing->calculateLateFee();
        
        // Get condition data
        $returnCondition = $data['return_condition'] ?? 'baik';
        $damageNotes = $data['damage_notes'] ?? null;
        $repairCost = $data['repair_cost'] ?? 0;
        
        // Calculate total penalty
        $totalPenalty = $lateFee + $repairCost;

        $borrowing->update([
            'status' => 'returned',
            'actual_return_date' => Carbon::now(),
            'return_condition' => $returnCondition,
            'damage_notes' => $damageNotes,
            'repair_cost' => $repairCost,
            'late_days' => $lateDays,
            'late_fee' => $lateFee,
            'total_penalty' => $totalPenalty,
        ]);

        // Handle equipment stock based on condition
        if ($returnCondition === 'hilang') {
            // If lost, decrease total stock and available stock
            $borrowing->equipment->decrement('stock');
            $borrowing->equipment->decrement('available_stock');
        } elseif ($returnCondition === 'rusak_berat' || $returnCondition === 'rusak_ringan') {
            // If damaged (light or heavy), decrease total stock
            // This removes the damaged item from circulation
            // But we still increase available_stock first (from the return)
            $borrowing->equipment->increment('available_stock');
            // Then decrease both stock and available_stock to remove the damaged unit
            $borrowing->equipment->decrement('stock');
            $borrowing->equipment->decrement('available_stock');
        } else {
            // If condition is good, just increase available stock
            $borrowing->equipment->increment('available_stock');
        }

        // ALWAYS set equipment status to 'available' regardless of condition
        // Damaged/lost items are tracked in separate "List Barang Rusak" and "List Barang Hilang" pages
        // The stock number already shows availability
        $this->equipmentService->updateStatus($borrowing->equipment, 'available');

        return $borrowing->fresh();
    }

    /**
     * Get active borrowings (approved status).
     */
    public function getActiveBorrowings()
    {
        return Borrowing::with(['user', 'equipment.category', 'approver'])
            ->where('status', 'approved')
            ->orderBy('borrow_date', 'desc')
            ->get();
    }
}
