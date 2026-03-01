<?php

namespace App\Services;

use App\Models\User;

class PermissionService
{
    /**
     * Check if user can manage users (create, update, delete users)
     */
    public function canManageUsers(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Check if user can manage equipment (create, update, delete equipment)
     */
    public function canManageEquipment(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Check if user can manage categories
     */
    public function canManageCategories(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Check if user can approve borrowing requests
     */
    public function canApproveRequests(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * Check if user can approve returns
     */
    public function canApproveReturns(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * Check if user can borrow equipment
     */
    public function canBorrowEquipment(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff', 'borrower']);
    }

    /**
     * Check if user can view reports
     */
    public function canViewReports(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * Check if user can generate reports
     */
    public function canGenerateReports(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * Check if user can view equipment
     */
    public function canViewEquipment(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff', 'borrower']);
    }

    /**
     * Check if user can view their own borrowing history
     */
    public function canViewOwnHistory(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff', 'borrower']);
    }

    /**
     * Check if user can view activity logs
     */
    public function canViewActivityLogs(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Check if user can view analytics dashboard
     */
    public function canViewAnalytics(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Check if user can submit borrowing requests
     */
    public function canSubmitBorrowingRequest(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff', 'borrower']);
    }

    /**
     * Check if user can submit return requests
     */
    public function canSubmitReturnRequest(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff', 'borrower']);
    }
}
