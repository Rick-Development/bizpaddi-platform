<?php

use Illuminate\Support\Facades\Route;
use Modules\TeamPortal\Http\Controllers\Api\AuthController;
use Modules\TeamPortal\Http\Controllers\Api\DashboardController;
use Modules\TeamPortal\Http\Controllers\Api\TaskController;
use Modules\TeamPortal\Http\Controllers\Api\KanbanController;
use Modules\TeamPortal\Http\Controllers\Api\WorkLogController;
use Modules\TeamPortal\Http\Controllers\Api\AttendanceController;
use Modules\TeamPortal\Http\Controllers\Api\ReportController;
use Modules\TeamPortal\Http\Controllers\Api\ProjectController;
use Modules\TeamPortal\Http\Controllers\Api\ProjectChatController;
use Modules\TeamPortal\Http\Controllers\Api\BankAccountController;
use Modules\TeamPortal\Http\Controllers\Api\PerformanceController;
use Modules\TeamPortal\Http\Controllers\Api\EarningsController;
use Modules\TeamPortal\Http\Controllers\Api\ClientController;
use Modules\TeamPortal\Http\Controllers\Api\QuoteController;
use Modules\TeamPortal\Http\Controllers\Api\InvoiceController;
use Modules\TeamPortal\Http\Controllers\Api\ServiceController;
use Modules\TeamPortal\Http\Controllers\Api\WalletController;

use Modules\TeamPortal\Http\Controllers\Api\PM\DashboardController as PMDashboardController;
use Modules\TeamPortal\Http\Controllers\Api\PM\ProjectController as PMProjectController;
use Modules\TeamPortal\Http\Controllers\Api\PM\TaskController as PMTaskController;

Route::middleware(['auth:sanctum', 'user_type:team'])->prefix('v1/team')->name('team.')->group(function () {
    // Auth & Profile
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/profile/picture', [AuthController::class, 'updateProfilePicture']);
    Route::delete('/profile', [AuthController::class, 'deleteAccount']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Tasks
    Route::apiResource('tasks', TaskController::class)->only(['index', 'show']);
    Route::patch('/tasks/{id}/status', [TaskController::class, 'updateStatus']);

    // Kanban
    Route::get('/kanban', [KanbanController::class, 'index']);
    Route::post('/kanban/update-order', [KanbanController::class, 'updateOrder']);

    // Work Logs
    Route::get('/work-logs', [WorkLogController::class, 'index']);
    Route::get('/work-logs/check-partial-status', [WorkLogController::class, 'checkPartialStatus']);
    Route::post('/work-logs/check-in', [WorkLogController::class, 'checkIn']);
    Route::post('/work-logs/start', [WorkLogController::class, 'checkIn']);       // alias for check-in
    Route::put('/work-logs/{id}/check-out', [WorkLogController::class, 'checkOut']);
    Route::put('/work-logs/{id}/stop', [WorkLogController::class, 'checkOut']);   // alias for check-out
    Route::patch('/work-logs/{id}/stop', [WorkLogController::class, 'checkOut']); // also support PATCH

    // Attendance
    Route::get('/attendance', [AttendanceController::class, 'index']);
    Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn']);
    Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut']);

    // Reports
    Route::apiResource('reports', ReportController::class)->only(['index', 'store', 'show']);

    // Projects
    Route::apiResource('projects', ProjectController::class)->only(['index', 'show']);
    
    // Project Chat
    Route::get('/projects/{id}/chat', [ProjectChatController::class, 'index']);
    Route::post('/projects/{id}/chat', [ProjectChatController::class, 'store']);
    Route::post('/projects/{id}/chat/status', [ProjectChatController::class, 'updateConversationStatus']);
    
    Route::put('/projects/chat/messages/{messageId}', [ProjectChatController::class, 'updateMessage']);
    Route::delete('/projects/chat/messages/{messageId}', [ProjectChatController::class, 'deleteMessage']);
    Route::post('/projects/chat/messages/{messageId}/read', [ProjectChatController::class, 'markAsRead']);

    // General Chats (Team <-> Admin)
    Route::get('/chats', [\Modules\TeamPortal\Http\Controllers\Api\ChatController::class, 'index']);
    Route::post('/chats', [\Modules\TeamPortal\Http\Controllers\Api\ChatController::class, 'store']);
    Route::get('/chats/{id}', [\Modules\TeamPortal\Http\Controllers\Api\ChatController::class, 'show']);
    Route::post('/chats/{id}/messages', [\Modules\TeamPortal\Http\Controllers\Api\ChatController::class, 'sendMessage']);
    Route::post('/chats/{id}/close', [\Modules\TeamPortal\Http\Controllers\Api\ChatController::class, 'close']);
    Route::delete('/chats/{id}', [\Modules\TeamPortal\Http\Controllers\Api\ChatController::class, 'destroy']);

    // Bank Accounts
    Route::get('/bank-accounts/banks', [BankAccountController::class, 'banks']);
    Route::apiResource('bank-accounts', BankAccountController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('/bank-accounts/{id}/make-primary', [BankAccountController::class, 'makePrimary']);

    // Performance
    Route::get('/performance', [PerformanceController::class, 'index']);

    // Earnings
    Route::apiResource('earnings', EarningsController::class)->only(['index', 'show']);

    // Clients
    Route::apiResource('clients', ClientController::class)->only(['index', 'show']);

    // Quotes & Invoices
    Route::apiResource('quotes', QuoteController::class)->only(['index', 'show']);
    Route::apiResource('invoices', InvoiceController::class)->only(['index', 'show']);

    // Services
    Route::get('/services', [ServiceController::class, 'index']);
    Route::get('/services/categories', [ServiceController::class, 'categories']);

    // Wallet
    Route::get('/wallet', [WalletController::class, 'index']);
    Route::get('/wallet/transactions', [WalletController::class, 'transactions']);

    // Project Manager (PM) Routes
    Route::prefix('pm')->name('pm.')->group(function () {
        Route::get('/dashboard', [PMDashboardController::class, 'index']);
        Route::apiResource('projects', PMProjectController::class)->only(['index', 'show']);
        Route::get('/tasks/team-members', [PMTaskController::class, 'teamMembers']);
        Route::get('/tasks/options', [PMTaskController::class, 'options']);
        Route::apiResource('tasks', PMTaskController::class);
        Route::apiResource('milestones', \Modules\TeamPortal\Http\Controllers\Api\PM\MilestoneController::class);
        Route::get('/work-logs', [\Modules\TeamPortal\Http\Controllers\Api\PM\WorkLogController::class, 'index']);
    });
});
