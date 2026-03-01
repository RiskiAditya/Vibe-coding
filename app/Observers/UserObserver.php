<?php

namespace App\Observers;

use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Auth;

class UserObserver
{
    protected ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // Only log if there's an authenticated user (admin creating the user)
        if (Auth::check()) {
            $this->activityLogService->log(
                Auth::user(),
                'user_created',
                "Created user: {$user->username} (Role: {$user->role})"
            );
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        if (Auth::check()) {
            $changes = [];
            
            if ($user->isDirty('username')) {
                $changes[] = "username changed from {$user->getOriginal('username')} to {$user->username}";
            }
            
            if ($user->isDirty('email')) {
                $changes[] = "email changed from {$user->getOriginal('email')} to {$user->email}";
            }
            
            if ($user->isDirty('role')) {
                $changes[] = "role changed from {$user->getOriginal('role')} to {$user->role}";
            }

            if (!empty($changes)) {
                $this->activityLogService->log(
                    Auth::user(),
                    'user_updated',
                    "Updated user {$user->username}: " . implode(', ', $changes)
                );
            }
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        if (Auth::check()) {
            $this->activityLogService->log(
                Auth::user(),
                'user_deleted',
                "Deleted user: {$user->username} (Role: {$user->role})"
            );
        }
    }
}
