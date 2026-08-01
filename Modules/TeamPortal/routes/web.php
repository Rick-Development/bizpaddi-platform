<?php

use Illuminate\Support\Facades\Route;
use Modules\TeamPortal\Http\Controllers\AuthController;
use Modules\TeamPortal\Http\Controllers\DashboardController;
use Modules\TeamPortal\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| Team Portal Routes
|--------------------------------------------------------------------------
*/

Route::prefix('team')->name('team.')->group(function () {
    
    // Guest Routes
    Route::middleware('guest:web')->group(function () {
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthController::class, 'login']);
    });

    // Protected Routes
    Route::middleware(['auth:web', \Modules\TeamPortal\Http\Middleware\CheckUserStatus::class])->group(function () {
        Route::get('account-status', [DashboardController::class, 'accountStatus'])->name('account-status');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/', function() { return redirect()->route('team.dashboard'); });

        // Profile
        Route::get('profile', [\Modules\TeamPortal\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [\Modules\TeamPortal\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

        // Tasks
        Route::get('my-tasks', [TaskController::class, 'index'])->name('tasks.index');
        Route::get('kanban', [\Modules\TeamPortal\Http\Controllers\KanbanController::class, 'index'])->name('kanban.index');
        Route::post('kanban/update-order', [\Modules\TeamPortal\Http\Controllers\KanbanController::class, 'updateOrder'])->name('kanban.update-order');
        Route::get('tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
        Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.update-status');

        // Reports (Legacy)
        Route::resource('reports', Modules\TeamPortal\Http\Controllers\ReportController::class);

        // Work Logs (New)
        Route::get('work-logs', [Modules\TeamPortal\Http\Controllers\WorkLogController::class, 'index'])->name('work-logs.index');
        Route::post('work-logs/check-in', [Modules\TeamPortal\Http\Controllers\WorkLogController::class, 'checkIn'])->name('work-logs.check-in');
        Route::put('work-logs/{workLog}/check-out', [Modules\TeamPortal\Http\Controllers\WorkLogController::class, 'checkOut'])->name('work-logs.check-out');
        
        // Bank Accounts
        Route::resource('bank-accounts', Modules\TeamPortal\Http\Controllers\BankAccountController::class)->only(['index', 'create', 'store', 'destroy']);
        Route::post('bank-accounts/{bankAccount}/make-primary', [Modules\TeamPortal\Http\Controllers\BankAccountController::class, 'makePrimary'])->name('bank-accounts.make-primary');

        // Clients
        Route::resource('clients', Modules\TeamPortal\Http\Controllers\ClientController::class)->only(['index', 'show']);

        // Quotes
        Route::resource('quotes', Modules\TeamPortal\Http\Controllers\QuoteController::class)->only(['index', 'show']);

        // Invoices
        Route::resource('invoices', Modules\TeamPortal\Http\Controllers\InvoiceController::class)->only(['index', 'show']);

        // Services
        Route::resource('services', Modules\TeamPortal\Http\Controllers\ServiceController::class)->only(['index', 'show']);
        
        // Performance & KPIs
        Route::get('performance', [\Modules\TeamPortal\Http\Controllers\PerformanceController::class, 'index'])->name('performance.index');

        // Earnings
        Route::get('earnings', [\Modules\TeamPortal\Http\Controllers\PaymentController::class, 'index'])->name('payments.index');
        Route::get('earnings/{id}', [\Modules\TeamPortal\Http\Controllers\PaymentController::class, 'show'])->name('payments.show');

        // Attendance
        Route::post('attendance/clock-in', [\Modules\TeamPortal\Http\Controllers\AttendanceController::class, 'clockIn'])->name('attendance.clock-in');
        Route::post('attendance/clock-out', [\Modules\TeamPortal\Http\Controllers\AttendanceController::class, 'clockOut'])->name('attendance.clock-out');

        // Projects
        Route::get('my-projects', [\Modules\TeamPortal\Http\Controllers\ProjectController::class, 'index'])->name('projects.index');
        Route::get('projects/{project}', [\Modules\TeamPortal\Http\Controllers\ProjectController::class, 'show'])->name('projects.show'); // Need to create this controller if not exists to redirect or show details
        Route::get('projects/{project}/chat', [\Modules\TeamPortal\Http\Controllers\ProjectChatController::class, 'index'])->name('projects.chat');
        Route::post('projects/{project}/chat', [\Modules\TeamPortal\Http\Controllers\ProjectChatController::class, 'store'])->name('projects.chat.store');
    });
});

// Project Manager Routes
Route::prefix('team/pm')->name('team.pm.')->middleware(['auth:web', \Modules\TeamPortal\Http\Middleware\CheckUserStatus::class, \Modules\TeamPortal\Http\Middleware\CheckProjectManager::class])->group(function () {
    // PM Dashboard
    Route::get('dashboard', [\Modules\TeamPortal\Http\Controllers\PM\DashboardController::class, 'index'])->name('dashboard');
    
    // PM Projects
    Route::get('projects', [\Modules\TeamPortal\Http\Controllers\PM\ProjectController::class, 'index'])->name('projects.index');
    Route::get('projects/{project}', [\Modules\TeamPortal\Http\Controllers\PM\ProjectController::class, 'show'])->name('projects.show');
    Route::put('projects/{project}', [\Modules\TeamPortal\Http\Controllers\PM\ProjectController::class, 'update'])->name('projects.update');
    
    // PM Team Management
    Route::get('projects/{project}/team', [\Modules\TeamPortal\Http\Controllers\PM\ProjectController::class, 'team'])->name('projects.team');
    Route::post('projects/{project}/team', [\Modules\TeamPortal\Http\Controllers\PM\ProjectController::class, 'addTeamMember'])->name('projects.team.add');
    Route::delete('projects/{project}/team/{user}', [\Modules\TeamPortal\Http\Controllers\PM\ProjectController::class, 'removeTeamMember'])->name('projects.team.remove');
    
    // PM Task Management
    Route::get('projects/{project}/tasks/create', [\Modules\TeamPortal\Http\Controllers\PM\TaskController::class, 'create'])->name('tasks.create');
    Route::post('projects/{project}/tasks', [\Modules\TeamPortal\Http\Controllers\PM\TaskController::class, 'store'])->name('tasks.store');
    Route::get('projects/{project}/tasks/{task}/edit', [\Modules\TeamPortal\Http\Controllers\PM\TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('projects/{project}/tasks/{task}', [\Modules\TeamPortal\Http\Controllers\PM\TaskController::class, 'update'])->name('tasks.update');
    Route::delete('projects/{project}/tasks/{task}', [\Modules\TeamPortal\Http\Controllers\PM\TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::post('projects/{project}/tasks/bulk-assign', [\Modules\TeamPortal\Http\Controllers\PM\TaskController::class, 'bulkAssign'])->name('tasks.bulk-assign');
});
