<?php

use Illuminate\Support\Facades\Route;
use Modules\ClientPortal\Http\Controllers\AuthController;
use Modules\ClientPortal\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Client Portal Routes
|--------------------------------------------------------------------------
*/

Route::prefix('dashboard/client')->name('client.')->group(function () {
    
    // Guest Routes (Login & Register)
    Route::middleware('guest:client')->group(function () {
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthController::class, 'login']);
        
        Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
        Route::post('register', [AuthController::class, 'register']);
        
        // Password Reset Routes
        Route::get('forgot-password', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
        Route::post('forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
        Route::get('reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
        Route::post('reset-password', [AuthController::class, 'reset'])->name('password.update');
    });

    // Protected Routes (Authenticated Clients)
    Route::middleware('Modules\ClientPortal\Http\Middleware\ClientAuth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/', function() { return redirect()->route('client.dashboard'); });

        // Projects
        Route::get('projects', [Modules\ClientPortal\Http\Controllers\ProjectController::class, 'index'])->name('projects.index');
        Route::get('projects/{project}', [Modules\ClientPortal\Http\Controllers\ProjectController::class, 'show'])->name('projects.show');
        Route::get('projects/{project}/chat', [Modules\ClientPortal\Http\Controllers\ClientProjectChatController::class, 'index'])->name('projects.chat');
        Route::post('projects/{project}/chat', [Modules\ClientPortal\Http\Controllers\ClientProjectChatController::class, 'store'])->name('projects.chat.store');

        // Task Approvals
        Route::post('tasks/{id}/approve', [Modules\ClientPortal\Http\Controllers\TaskController::class, 'approve'])->name('tasks.approve');
        Route::post('tasks/{id}/reject', [Modules\ClientPortal\Http\Controllers\TaskController::class, 'reject'])->name('tasks.reject');

        // Invoices
        Route::get('invoices', [Modules\ClientPortal\Http\Controllers\InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('invoices/{invoice}', [Modules\ClientPortal\Http\Controllers\InvoiceController::class, 'show'])->name('invoices.show');
        Route::get('invoices/{invoice}/pdf', [Modules\ClientPortal\Http\Controllers\InvoiceController::class, 'downloadPDF'])->name('invoices.pdf');
        Route::get('invoices/{invoice}/pay', [Modules\ClientPortal\Http\Controllers\InvoiceController::class, 'pay'])->name('invoices.pay');
        Route::post('invoices/{invoice}/confirm-payment', [Modules\ClientPortal\Http\Controllers\InvoiceController::class, 'confirmPayment'])->name('invoices.confirm-payment');
        Route::get('invoices/{invoice}/check-status', [Modules\ClientPortal\Http\Controllers\InvoiceController::class, 'checkPaymentStatus'])->name('invoices.check-status');

        // Transactions
        Route::get('transactions', [Modules\ClientPortal\Http\Controllers\TransactionController::class, 'index'])->name('transactions.index');
        Route::get('transactions/{transaction}', [Modules\ClientPortal\Http\Controllers\TransactionController::class, 'show'])->name('transactions.show');

        // Tasks (Support Tickets) - Renamed to 'tickets' in URL but using TicketController
        Route::resource('tickets', Modules\ClientPortal\Http\Controllers\TicketController::class);
        Route::post('tickets/{ticket}/reply', [Modules\ClientPortal\Http\Controllers\TicketController::class, 'reply'])->name('tickets.reply');

        // Profile
        Route::get('profile', [Modules\ClientPortal\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [Modules\ClientPortal\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

        // Service Catalog & Ordering
        Route::get('services', [Modules\ClientPortal\Http\Controllers\ServiceCatalogController::class, 'index'])->name('services.index');
        Route::get('services/{service}', [Modules\ClientPortal\Http\Controllers\ServiceCatalogController::class, 'show'])->name('services.show');
        Route::get('services/{service}/order', [Modules\ClientPortal\Http\Controllers\ServiceCatalogController::class, 'configure'])->name('services.configure');
        
        // Contracts
        Route::get('contracts', [Modules\ClientPortal\Http\Controllers\ContractController::class, 'index'])->name('contracts.index');
        Route::get('contracts/{id}', [Modules\ClientPortal\Http\Controllers\ContractController::class, 'show'])->name('contracts.show');

        // Quotes
        Route::get('quotes', [Modules\ClientPortal\Http\Controllers\QuoteController::class, 'index'])->name('quotes.index');
        Route::get('quotes/{id}', [Modules\ClientPortal\Http\Controllers\QuoteController::class, 'show'])->name('quotes.show');
        Route::get('quotes/{id}/pdf', [Modules\ClientPortal\Http\Controllers\QuoteController::class, 'downloadPDF'])->name('quotes.pdf');
        Route::get('quotes/{id}/pay', [Modules\ClientPortal\Http\Controllers\QuoteController::class, 'pay'])->name('quotes.pay');

        // Orders
        Route::resource('orders', Modules\ClientPortal\Http\Controllers\ServiceOrderController::class)->only(['index', 'store', 'show']);
    });
});

// Backwards-compatible aliases & redirects for legacy /client URLs
Route::redirect('client', '/dashboard/client');
Route::redirect('client/{path}', '/dashboard/client/{path}')->where('path', '.*');
