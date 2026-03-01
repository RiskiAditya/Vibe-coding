<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenaltyController;
use App\Http\Controllers\DamagedEquipmentController;
use App\Http\Controllers\LostEquipmentController;
use App\Http\Controllers\BorrowerHistoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard routes (protected by auth middleware)
Route::middleware(['auth'])->group(function () {
    // Role-based dashboards
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
        ->middleware('role:admin')
        ->name('admin.dashboard');

    Route::get('/staff/dashboard', [DashboardController::class, 'staff'])
        ->middleware('role:staff')
        ->name('staff.dashboard');

    Route::get('/borrower/dashboard', [DashboardController::class, 'borrower'])
        ->middleware('role:borrower')
        ->name('borrower.dashboard');
    
    // User management routes (admin only)
    Route::resource('users', UserController::class)->except(['show']);
    
    // Category management routes (admin only)
    Route::resource('categories', CategoryController::class)->except(['show', 'create', 'edit']);
    
    // Equipment management routes
    Route::resource('equipment', EquipmentController::class);
    
    // Borrowing routes
    Route::get('/borrowings', [BorrowingController::class, 'index'])->name('borrowings.index');
    Route::get('/borrowings/create', [BorrowingController::class, 'create'])->name('borrowings.create');
    Route::post('/borrowings', [BorrowingController::class, 'store'])->name('borrowings.store');
    Route::post('/borrowings/{borrowing}/approve', [BorrowingController::class, 'approve'])->name('borrowings.approve');
    Route::post('/borrowings/{borrowing}/reject', [BorrowingController::class, 'reject'])->name('borrowings.reject');
    
    // Return routes
    Route::get('/returns', [ReturnController::class, 'index'])->name('returns.index');
    Route::get('/returns/{borrowing}/create', [ReturnController::class, 'create'])->name('returns.create');
    Route::post('/returns/{borrowing}', [ReturnController::class, 'store'])->name('returns.store');
    Route::post('/returns/{borrowing}/approve', [ReturnController::class, 'approve'])->name('returns.approve');
    
    // Report routes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/print', [ReportController::class, 'print'])->name('reports.print');
    
    // Penalty management routes (admin only)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/penalties', [PenaltyController::class, 'index'])->name('penalties.index');
        Route::post('/penalties/{borrowing}/mark-paid', [PenaltyController::class, 'markAsPaid'])->name('penalties.mark-paid');
        Route::post('/penalties/{borrowing}/mark-unpaid', [PenaltyController::class, 'markAsUnpaid'])->name('penalties.mark-unpaid');
        
        // Damaged and lost equipment routes (admin only)
        Route::get('/damaged-equipment', [DamagedEquipmentController::class, 'index'])->name('damaged-equipment.index');
        Route::get('/damaged-equipment/export', [DamagedEquipmentController::class, 'exportExcel'])->name('damaged-equipment.export');
        Route::get('/lost-equipment', [LostEquipmentController::class, 'index'])->name('lost-equipment.index');
        Route::get('/lost-equipment/export', [LostEquipmentController::class, 'exportExcel'])->name('lost-equipment.export');
        
        // Borrower history routes (admin only)
        Route::get('/borrower-history', [BorrowerHistoryController::class, 'index'])->name('borrower-history.index');
        Route::get('/borrower-history/{user}', [BorrowerHistoryController::class, 'show'])->name('borrower-history.show');
    });
});
