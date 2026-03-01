<?php

namespace App\Observers;

use App\Models\Borrowing;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Auth;

class BorrowingObserver
{
    protected ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /**
     * Handle the Borrowing "created" event.
     */
    public function created(Borrowing $borrowing): void
    {
        if (Auth::check()) {
            $equipment = $borrowing->equipment;
            $user = $borrowing->user;
            
            $this->activityLogService->log(
                Auth::user(),
                'borrowing_request_created',
                "Borrowing request created by {$user->username} for equipment: {$equipment->name}"
            );
        }
    }

    /**
     * Handle the Borrowing "updated" event.
     */
    public function updated(Borrowing $borrowing): void
    {
        if (Auth::check()) {
            $equipment = $borrowing->equipment;
            $user = $borrowing->user;
            
            // Check if status changed to approved
            if ($borrowing->isDirty('status') && $borrowing->status === 'approved') {
                $this->activityLogService->log(
                    Auth::user(),
                    'borrowing_approved',
                    "Approved borrowing request for {$user->username} - Equipment: {$equipment->name}"
                );
            }
            
            // Check if status changed to rejected
            if ($borrowing->isDirty('status') && $borrowing->status === 'rejected') {
                $this->activityLogService->log(
                    Auth::user(),
                    'borrowing_rejected',
                    "Rejected borrowing request for {$user->username} - Equipment: {$equipment->name}"
                );
            }
            
            // Check if status changed to returned
            if ($borrowing->isDirty('status') && $borrowing->status === 'returned') {
                $this->activityLogService->log(
                    Auth::user(),
                    'equipment_returned',
                    "Equipment returned by {$user->username} - Equipment: {$equipment->name}"
                );
            }
        }
    }

    /**
     * Handle the Borrowing "deleted" event.
     */
    public function deleted(Borrowing $borrowing): void
    {
        if (Auth::check()) {
            $equipment = $borrowing->equipment;
            $user = $borrowing->user;
            
            $this->activityLogService->log(
                Auth::user(),
                'borrowing_deleted',
                "Deleted borrowing record for {$user->username} - Equipment: {$equipment->name}"
            );
        }
    }
}
