<?php

namespace App\Observers;

use App\Models\Equipment;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Auth;

class EquipmentObserver
{
    protected ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /**
     * Handle the Equipment "created" event.
     */
    public function created(Equipment $equipment): void
    {
        if (Auth::check()) {
            $this->activityLogService->log(
                Auth::user(),
                'equipment_created',
                "Created equipment: {$equipment->name} (Status: {$equipment->status})"
            );
        }
    }

    /**
     * Handle the Equipment "updated" event.
     */
    public function updated(Equipment $equipment): void
    {
        if (Auth::check()) {
            $changes = [];
            
            if ($equipment->isDirty('name')) {
                $changes[] = "name changed from {$equipment->getOriginal('name')} to {$equipment->name}";
            }
            
            if ($equipment->isDirty('status')) {
                $changes[] = "status changed from {$equipment->getOriginal('status')} to {$equipment->status}";
            }
            
            if ($equipment->isDirty('category_id')) {
                $changes[] = "category changed";
            }

            if (!empty($changes)) {
                $this->activityLogService->log(
                    Auth::user(),
                    'equipment_updated',
                    "Updated equipment {$equipment->name}: " . implode(', ', $changes)
                );
            }
        }
    }

    /**
     * Handle the Equipment "deleted" event.
     */
    public function deleted(Equipment $equipment): void
    {
        if (Auth::check()) {
            $this->activityLogService->log(
                Auth::user(),
                'equipment_deleted',
                "Deleted equipment: {$equipment->name}"
            );
        }
    }
}
