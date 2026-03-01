<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Models\Equipment;
use App\Models\Borrowing;
use App\Observers\UserObserver;
use App\Observers\EquipmentObserver;
use App\Observers\BorrowingObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register model observers for activity logging
        User::observe(UserObserver::class);
        Equipment::observe(EquipmentObserver::class);
        Borrowing::observe(BorrowingObserver::class);
    }
}
