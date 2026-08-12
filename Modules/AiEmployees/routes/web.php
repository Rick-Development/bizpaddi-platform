<?php

use Illuminate\Support\Facades\Route;
use Modules\AiEmployees\Http\Controllers\AiEmployeesController;
use Modules\AiEmployees\Http\Controllers\AiDashboardController;
use Modules\AiEmployees\Http\Controllers\AiEmployeeController;
use Modules\AiEmployees\Http\Controllers\AiTaskController;
use Modules\AiEmployees\Http\Controllers\AiKnowledgeController;

Route::prefix('admin/aiemployees')->name('admin.aiemployees.')->middleware(['web', \Modules\Admin\Http\Middleware\AdminAuth::class])->group(function () {
    Route::get('/dashboard', [AiDashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/task', [AiDashboardController::class, 'processTask'])->name('dashboard.task');
    Route::get('/dashboard/task/{id}/poll', [AiDashboardController::class, 'pollTask'])->name('dashboard.task.poll');
    Route::post('/dashboard/actions/{id}/approve', [AiDashboardController::class, 'approveAction'])->name('dashboard.actions.approve');
    Route::post('/dashboard/actions/{id}/reject', [AiDashboardController::class, 'rejectAction'])->name('dashboard.actions.reject');
    Route::post('employees/{employee}/toggle-status', [AiEmployeeController::class, 'toggleStatus'])->name('employees.toggle-status');
    Route::resource('employees', AiEmployeeController::class);
    Route::post('/run-routines', [AiDashboardController::class, 'runRoutines'])->name('run-routines');
    Route::post('tasks/{task}/retry', [AiTaskController::class, 'retry'])->name('tasks.retry');
    Route::resource('tasks', AiTaskController::class);
    Route::resource('knowledge', AiKnowledgeController::class);
});
