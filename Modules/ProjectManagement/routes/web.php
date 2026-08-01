<?php

use Illuminate\Support\Facades\Route;
use Modules\ProjectManagement\Http\Controllers\ProjectManagementController;

Route::group([], function () {
    Route::get('project-management', function() {
        return view('projectmanagement::dashboard');
    })->name('project.dashboard');
    
    Route::resource('projectmanagements', ProjectManagementController::class)->names('projectmanagement');

    Route::post('comments', [Modules\ProjectManagement\Http\Controllers\CommentController::class, 'store'])->name('pm.comments.store');
    Route::delete('comments/{comment}', [Modules\ProjectManagement\Http\Controllers\CommentController::class, 'destroy'])->name('pm.comments.destroy');

    Route::post('attachments', [Modules\ProjectManagement\Http\Controllers\AttachmentController::class, 'store'])->name('pm.attachments.store');
    Route::delete('attachments/{attachment}', [Modules\ProjectManagement\Http\Controllers\AttachmentController::class, 'destroy'])
    ->name('pm.attachments.destroy');
    Route::post('time-logs/start', [Modules\ProjectManagement\Http\Controllers\TimeLogController::class, 'start'])->name('pm.time-logs.start');
    Route::post('time-logs/{timeLog}/stop', [Modules\ProjectManagement\Http\Controllers\TimeLogController::class, 'stop'])->name('pm.time-logs.stop');
    Route::resource('time-logs', Modules\ProjectManagement\Http\Controllers\TimeLogController::class)->only(['store', 'destroy'])->names('pm.time-logs');
    
});
