<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Services\PermissionService;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        $permissionService = app(PermissionService::class);

        // User Management Gates
        Gate::define('manage-users', function ($user) use ($permissionService) {
            return $permissionService->canManageUsers($user);
        });

        // Equipment Management Gates
        Gate::define('manage-equipment', function ($user) use ($permissionService) {
            return $permissionService->canManageEquipment($user);
        });

        // Category Management Gates
        Gate::define('manage-categories', function ($user) use ($permissionService) {
            return $permissionService->canManageCategories($user);
        });

        // Borrowing Workflow Gates
        Gate::define('approve-borrowing-requests', function ($user) use ($permissionService) {
            return $permissionService->canApproveRequests($user);
        });

        Gate::define('approve-returns', function ($user) use ($permissionService) {
            return $permissionService->canApproveReturns($user);
        });

        Gate::define('borrow-equipment', function ($user) use ($permissionService) {
            return $permissionService->canBorrowEquipment($user);
        });

        Gate::define('submit-borrowing-request', function ($user) use ($permissionService) {
            return $permissionService->canSubmitBorrowingRequest($user);
        });

        Gate::define('submit-return-request', function ($user) use ($permissionService) {
            return $permissionService->canSubmitReturnRequest($user);
        });

        // Reporting Gates
        Gate::define('view-reports', function ($user) use ($permissionService) {
            return $permissionService->canViewReports($user);
        });

        Gate::define('generate-reports', function ($user) use ($permissionService) {
            return $permissionService->canGenerateReports($user);
        });

        // Equipment Viewing Gates
        Gate::define('view-equipment', function ($user) use ($permissionService) {
            return $permissionService->canViewEquipment($user);
        });

        // History Gates
        Gate::define('view-own-history', function ($user) use ($permissionService) {
            return $permissionService->canViewOwnHistory($user);
        });

        // Activity Logs Gates
        Gate::define('view-activity-logs', function ($user) use ($permissionService) {
            return $permissionService->canViewActivityLogs($user);
        });

        // Analytics Gates
        Gate::define('view-analytics', function ($user) use ($permissionService) {
            return $permissionService->canViewAnalytics($user);
        });
    }
}
