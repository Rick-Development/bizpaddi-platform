<?php

use Illuminate\Support\Facades\Route;
use Modules\ClientPortal\Http\Controllers\Api\AuthController;
use Modules\ClientPortal\Http\Controllers\Api\ProjectController;
use Modules\ClientPortal\Http\Controllers\Api\InvoiceController;
use Modules\ClientPortal\Http\Controllers\Api\ServiceCategoryController;
use Modules\ClientPortal\Http\Controllers\Api\TicketController;
use Modules\ClientPortal\Http\Controllers\Api\QuoteController;
use Modules\ClientPortal\Http\Controllers\Api\ChatController;
use Modules\ClientPortal\Http\Controllers\Api\ProjectChatController;
use Modules\ClientPortal\Http\Controllers\Api\PublicController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1/client')->group(function () {
    
    // Public Routes
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
    
    Route::get('legal-pages', [PublicController::class, 'getLegalPages']);
    Route::get('legal-pages/{slug}', [PublicController::class, 'getLegalPage']);
    Route::get('support-settings', [PublicController::class, 'getSupportSettings']);

    // Service Categories
    Route::get('service-categories', [ServiceCategoryController::class, 'index']);
    Route::get('service-categories/{id}', [ServiceCategoryController::class, 'show']);

    // Protected Routes
    Route::middleware(['auth:sanctum', 'user_type:client'])->group(function () {
        // Auth & Profile
        Route::get('profile', [AuthController::class, 'profile']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('update-device-token', [AuthController::class, 'updateFcmToken']);
        Route::post('update-profile', [AuthController::class, 'updateProfile']);
        Route::post('change-password', [AuthController::class, 'changePassword']);
        Route::post('profile/picture', [AuthController::class, 'updateProfilePicture']);
        Route::delete('profile', [AuthController::class, 'deleteAccount']);


        // Projects
        Route::get('projects', [ProjectController::class, 'index']);
        Route::get('projects/{id}', [ProjectController::class, 'show']);

        // Project Chats
        Route::get('projects/{id}/chat', [ProjectChatController::class, 'index']);
        Route::post('projects/{id}/chat', [ProjectChatController::class, 'store']);
        Route::put('projects/chat/messages/{messageId}', [ProjectChatController::class, 'updateMessage']);
        Route::delete('projects/chat/messages/{messageId}', [ProjectChatController::class, 'deleteMessage']);
        Route::post('projects/chat/messages/{messageId}/read', [ProjectChatController::class, 'markAsRead']);

        // Invoices
        Route::get('invoices', [InvoiceController::class, 'index']);
        Route::get('invoices/{id}', [InvoiceController::class, 'show']);

        // Support Tickets
        Route::get('tickets', [TicketController::class, 'index']);
        Route::post('tickets', [TicketController::class, 'store']);
        Route::get('tickets/{id}', [TicketController::class, 'show']);

        // Quotes
        Route::get('quotes', [QuoteController::class, 'index']);
        Route::get('quotes/{id}', [QuoteController::class, 'show']);

        // Chats (separate from Support Tickets)
        Route::get('chats', [ChatController::class, 'index']);
        Route::post('chats', [ChatController::class, 'store']);
        Route::get('chats/{id}', [ChatController::class, 'show']);
        Route::post('chats/{id}/messages', [ChatController::class, 'sendMessage']);
        Route::post('chats/{id}/close', [ChatController::class, 'close']);
        Route::delete('chats/{id}', [ChatController::class, 'destroy']);
    });
});
