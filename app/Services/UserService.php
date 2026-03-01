<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserService
{
    protected ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /**
     * Create a new user with validation.
     *
     * @param array $data
     * @return User
     * @throws \Exception
     */
    public function createUser(array $data): User
    {
        DB::beginTransaction();
        
        try {
            // Hash the password
            $data['password'] = Hash::make($data['password']);
            
            // Create the user
            $user = User::create($data);
            
            // Log the action
            $this->activityLogService->log(
                auth()->user(),
                'user_created',
                "Created user: {$user->username} (ID: {$user->id})"
            );
            
            DB::commit();
            
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update an existing user with validation.
     *
     * @param User $user
     * @param array $data
     * @return User
     * @throws \Exception
     */
    public function updateUser(User $user, array $data): User
    {
        DB::beginTransaction();
        
        try {
            // Hash password if provided
            if (isset($data['password']) && !empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                // Remove password from data if not provided
                unset($data['password']);
            }
            
            // Update the user
            $user->update($data);
            
            // Log the action
            $this->activityLogService->log(
                auth()->user(),
                'user_updated',
                "Updated user: {$user->username} (ID: {$user->id})"
            );
            
            DB::commit();
            
            return $user->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete a user.
     *
     * @param User $user
     * @return bool
     * @throws \Exception
     */
    public function deleteUser(User $user): bool
    {
        DB::beginTransaction();
        
        try {
            $username = $user->username;
            $userId = $user->id;
            
            // Log the action BEFORE deleting the user
            $this->activityLogService->log(
                auth()->user(),
                'user_deleted',
                "Deleted user: {$username} (ID: {$userId})"
            );
            
            // Delete the user
            $user->delete();
            
            DB::commit();
            
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Check if a user can be deleted.
     * Prevents self-deletion for admin users.
     *
     * @param User $user
     * @param User $currentUser
     * @return bool
     */
    public function canDeleteUser(User $user, User $currentUser): bool
    {
        // Prevent admin from deleting their own account
        if ($user->id === $currentUser->id && $currentUser->role === 'admin') {
            return false;
        }
        
        return true;
    }
}
