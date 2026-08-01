<?php

use Illuminate\Support\Facades\Route;

use Modules\Admin\Http\Controllers\Api\AuthController;
use Modules\Admin\Http\Controllers\Api\DashboardController;
use Modules\Admin\Http\Controllers\Api\ClientController;
use Modules\Admin\Http\Controllers\Api\TeamMemberController;
use Modules\Admin\Http\Controllers\Api\DepartmentController;
use Modules\Admin\Http\Controllers\Api\RoleController;
use Modules\Admin\Http\Controllers\Api\PermissionController;
use Modules\Admin\Http\Controllers\Api\LeadController;
use Modules\Admin\Http\Controllers\Api\ContactController;
use Modules\Admin\Http\Controllers\Api\TicketController;
use Modules\Admin\Http\Controllers\Api\CampaignController;
use Modules\Admin\Http\Controllers\Api\NotificationController;
use Modules\Admin\Http\Controllers\Api\SettingController;

use Modules\Admin\Http\Controllers\Api\Accounting\InvoiceController;
use Modules\Admin\Http\Controllers\Api\Accounting\QuoteController;
use Modules\Admin\Http\Controllers\Api\Accounting\ServiceOrderController;
use Modules\Admin\Http\Controllers\Api\Accounting\TransactionController;
use Modules\Admin\Http\Controllers\Api\Accounting\CurrencyController;
use Modules\Admin\Http\Controllers\Api\Accounting\BalanceSheetController;
use Modules\Admin\Http\Controllers\Api\Accounting\SalaryController;

use Modules\Admin\Http\Controllers\Api\Contracts\ContractController;
use Modules\Admin\Http\Controllers\Api\Contracts\ContractTemplateController;

use Modules\Admin\Http\Controllers\Api\PM\ProjectController;
use Modules\Admin\Http\Controllers\Api\PM\MilestoneController;
use Modules\Admin\Http\Controllers\Api\PM\TaskController;
use Modules\Admin\Http\Controllers\Api\PM\WorkLogController;
use Modules\Admin\Http\Controllers\Api\PM\ProjectChatController;

Route::middleware(['auth:sanctum', 'user_type:admin'])->prefix('v1/admin')->group(function () {
    // Auth & Profile
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/profile/picture', [AuthController::class, 'updateProfilePicture']);
    Route::delete('/profile', [AuthController::class, 'deleteAccount']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // General Resource Routes
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('team-members', TeamMemberController::class);
    Route::apiResource('departments', DepartmentController::class)->except(['create', 'edit', 'show']);
    Route::apiResource('roles', RoleController::class)->except(['create', 'edit', 'show']);
    Route::apiResource('permissions', PermissionController::class)->except(['create', 'edit', 'show']);
    Route::apiResource('leads', LeadController::class);
    Route::apiResource('contacts', ContactController::class);
    Route::apiResource('tickets', TicketController::class)->except(['create', 'edit', 'store']);
    Route::apiResource('campaigns', CampaignController::class);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);

    // Settings
    Route::get('/settings', [SettingController::class, 'index']);
    Route::post('/settings', [SettingController::class, 'update']);

    // Accounting
    Route::prefix('accounting')->group(function () {
        Route::apiResource('invoices', InvoiceController::class);
        Route::apiResource('quotes', QuoteController::class);
        Route::apiResource('service-orders', ServiceOrderController::class);
        Route::apiResource('transactions', TransactionController::class)->only(['index', 'show', 'store']);
        Route::apiResource('currencies', CurrencyController::class)->except(['create', 'edit']);
        Route::get('/balance-sheet', [BalanceSheetController::class, 'index']);
        Route::apiResource('salaries', SalaryController::class);
    });

    // Contracts
    Route::prefix('contracts')->group(function () {
        Route::apiResource('templates', ContractTemplateController::class);
        Route::apiResource('manage', ContractController::class)->parameters(['manage' => 'contract']);
    });

    // Project Management
    Route::prefix('pm')->group(function () {
        Route::apiResource('projects', ProjectController::class);
        Route::patch('projects/{id}/status', [ProjectController::class, 'updateStatus']);

        Route::apiResource('milestones', MilestoneController::class);
        Route::patch('milestones/{id}/status', [MilestoneController::class, 'updateStatus']);

        Route::apiResource('tasks', TaskController::class);
        Route::patch('tasks/{id}/status', [TaskController::class, 'updateStatus']);

        Route::apiResource('work-logs', WorkLogController::class)->only(['index', 'show']);
        Route::put('work-logs/{id}', [WorkLogController::class, 'update']);

        // Project Chats
        Route::get('projects/{id}/chat', [ProjectChatController::class, 'index']);
        Route::post('projects/{id}/chat', [ProjectChatController::class, 'store']);
        Route::post('projects/{id}/chat/status', [ProjectChatController::class, 'updateConversationStatus']);
        Route::put('projects/chat/messages/{messageId}', [ProjectChatController::class, 'updateMessage']);
        Route::delete('projects/chat/messages/{messageId}', [ProjectChatController::class, 'deleteMessage']);
        Route::post('projects/chat/messages/{messageId}/read', [ProjectChatController::class, 'markAsRead']);
    });
});
