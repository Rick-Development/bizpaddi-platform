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
        Route::get('api/overdue-tasks-count', [DashboardController::class, 'getOverdueTasksCount'])->name('api.overdue-tasks-count');
        Route::get('/', function() { return redirect()->route('team.dashboard'); });

        // Profile
        Route::get('profile', [\Modules\TeamPortal\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
        Route::get('profile/edit', [\Modules\TeamPortal\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [\Modules\TeamPortal\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
        Route::get('profile/password', [\Modules\TeamPortal\Http\Controllers\ProfileController::class, 'edit'])->name('profile.password');

        // Tasks
        Route::get('my-tasks', [TaskController::class, 'index'])->name('tasks.index');
        Route::get('kanban', [\Modules\TeamPortal\Http\Controllers\KanbanController::class, 'index'])->name('kanban.index');
        Route::post('kanban/filter', [\Modules\TeamPortal\Http\Controllers\KanbanController::class, 'index'])->name('kanban.filter');
        Route::post('kanban/update-order', [\Modules\TeamPortal\Http\Controllers\KanbanController::class, 'updateOrder'])->name('kanban.update-order');
        Route::get('tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
        Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.update-status');
        Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

        // Reports (Legacy)
        Route::resource('reports', Modules\TeamPortal\Http\Controllers\ReportController::class);

        // Work Logs (New)
        Route::get('work-logs', [Modules\TeamPortal\Http\Controllers\WorkLogController::class, 'index'])->name('work-logs.index');
        Route::get('work-logs/check-partial-status', [Modules\TeamPortal\Http\Controllers\WorkLogController::class, 'checkPartialStatus'])->name('work-logs.check-partial-status');
        Route::post('work-logs/check-in', [Modules\TeamPortal\Http\Controllers\WorkLogController::class, 'checkIn'])->name('work-logs.check-in');
        Route::get('work-logs/{workLog}', [Modules\TeamPortal\Http\Controllers\WorkLogController::class, 'show'])->name('work-logs.show');
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

        // Team Portal Unified Chat
        Route::get('chat/{conversation?}', [\Modules\TeamPortal\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
        Route::get('chat-api/conversations', [\Modules\TeamPortal\Http\Controllers\ChatController::class, 'getConversations'])->name('chat.conversations');
        Route::get('chat-api/conversations/{conversation}/messages', [\Modules\TeamPortal\Http\Controllers\ChatController::class, 'getMessages'])->name('chat.messages');
        Route::post('chat-api/conversations/{conversation}/messages', [\Modules\TeamPortal\Http\Controllers\ChatController::class, 'sendMessage'])->name('chat.messages.store');
        Route::post('chat-api/conversations/{conversation}/read', [\Modules\TeamPortal\Http\Controllers\ChatController::class, 'markAsRead'])->name('chat.messages.read');
        Route::post('chat-api/conversations/start', [\Modules\TeamPortal\Http\Controllers\ChatController::class, 'startConversation'])->name('chat.conversations.start');
        Route::get('chat-api/unread-count', [\Modules\TeamPortal\Http\Controllers\ChatController::class, 'getUnreadCount'])->name('chat.unread-count');
        Route::post('chat-api/groups', [\Modules\TeamPortal\Http\Controllers\ChatController::class, 'createGroup'])->name('chat.groups.store');
        Route::post('chat-api/conversations/{conversation}/members/add', [\Modules\TeamPortal\Http\Controllers\ChatController::class, 'addGroupMember'])->name('chat.groups.members.add');
        Route::post('chat-api/conversations/{conversation}/members/remove', [\Modules\TeamPortal\Http\Controllers\ChatController::class, 'removeGroupMember'])->name('chat.groups.members.remove');

        // Centralized Comment Feed
        Route::get('comments-feed', [\Modules\TeamPortal\Http\Controllers\CommentFeedController::class, 'index'])->name('comments-feed.index');
        Route::get('comments-feed/unread-count', [\Modules\TeamPortal\Http\Controllers\CommentFeedController::class, 'getUnreadCount'])->name('comments-feed.unread-count');
    });
});

// BDM Routes
Route::prefix('team/bdm')->name('team.bdm.')->middleware(['auth:web', \Modules\TeamPortal\Http\Middleware\CheckUserStatus::class])->group(function () {
    // We can add a middleware to ensure they are actually a BDM if we want:
    // Route::middleware([\Modules\TeamPortal\Http\Middleware\CheckBdm::class])
    
    // BDM Dashboard
    Route::get('dashboard', [\Modules\TeamPortal\Http\Controllers\BDM\DashboardController::class, 'index'])->name('dashboard');
    
    // BDM CRM & Sales Features
    Route::resource('clients', \Modules\TeamPortal\Http\Controllers\BDM\ClientController::class);
    Route::post('quotes/{quote}/send', [\Modules\TeamPortal\Http\Controllers\BDM\Accounting\QuoteController::class, 'send'])->name('quotes.send');
    Route::post('quotes/{quote}/approve', [\Modules\TeamPortal\Http\Controllers\BDM\Accounting\QuoteController::class, 'approve'])->name('quotes.approve');
    Route::post('quotes/{quote}/convert-invoice', [\Modules\TeamPortal\Http\Controllers\BDM\Accounting\QuoteController::class, 'convertToInvoice'])->name('quotes.convert-invoice');
    Route::get('quotes/{quote}/pdf', [\Modules\TeamPortal\Http\Controllers\BDM\Accounting\QuoteController::class, 'downloadPDF'])->name('quotes.pdf');
    Route::resource('quotes', \Modules\TeamPortal\Http\Controllers\BDM\Accounting\QuoteController::class);
    
    Route::post('invoices/{invoice}/send', [\Modules\TeamPortal\Http\Controllers\BDM\Accounting\InvoiceController::class, 'send'])->name('invoices.send');
    Route::post('invoices/{invoice}/mark-paid', [\Modules\TeamPortal\Http\Controllers\BDM\Accounting\InvoiceController::class, 'markPaid'])->name('invoices.mark-paid');
    Route::get('invoices/{invoice}/pdf', [\Modules\TeamPortal\Http\Controllers\BDM\Accounting\InvoiceController::class, 'downloadPDF'])->name('invoices.pdf');
    Route::resource('invoices', \Modules\TeamPortal\Http\Controllers\BDM\Accounting\InvoiceController::class);
    
    Route::post('service-orders/{service_order}/convert-quote', [\Modules\TeamPortal\Http\Controllers\BDM\Accounting\ServiceOrderController::class, 'convertToQuote'])->name('service-orders.convert-quote');
    Route::post('service-orders/{service_order}/convert-invoice', [\Modules\TeamPortal\Http\Controllers\BDM\Accounting\ServiceOrderController::class, 'convertToInvoice'])->name('service-orders.convert-invoice');
    Route::post('service-orders/{service_order}/convert-contract', [\Modules\TeamPortal\Http\Controllers\BDM\Accounting\ServiceOrderController::class, 'convertToContract'])->name('service-orders.convert-contract');
    Route::resource('service-orders', \Modules\TeamPortal\Http\Controllers\BDM\Accounting\ServiceOrderController::class);
    Route::get('contracts/expiring-soon', [\Modules\TeamPortal\Http\Controllers\BDM\Contracts\ContractController::class, 'expiringSoon'])->name('contracts.expiring-soon');
    Route::get('contracts/load-template', [\Modules\TeamPortal\Http\Controllers\BDM\Contracts\ContractController::class, 'loadTemplate'])->name('contracts.load-template');
    Route::get('contracts/{contract}/pdf', [\Modules\TeamPortal\Http\Controllers\BDM\Contracts\ContractController::class, 'downloadPDF'])->name('contracts.pdf');
    Route::get('contracts/{contract}/docx', [\Modules\TeamPortal\Http\Controllers\BDM\Contracts\ContractController::class, 'downloadDocx'])->name('contracts.docx');
    Route::post('contracts/{contract}/signature', [\Modules\TeamPortal\Http\Controllers\BDM\Contracts\ContractController::class, 'uploadSignature'])->name('contracts.signature');
    Route::patch('contracts/{contract}/status', [\Modules\TeamPortal\Http\Controllers\BDM\Contracts\ContractController::class, 'updateStatus'])->name('contracts.status');
    Route::resource('contracts', \Modules\TeamPortal\Http\Controllers\BDM\Contracts\ContractController::class);
    Route::resource('projects', \Modules\TeamPortal\Http\Controllers\BDM\Portfolio\ProjectController::class);
    Route::resource('services', \Modules\TeamPortal\Http\Controllers\BDM\Portfolio\ServiceController::class);
    Route::post('tickets/{ticket}/reply', [\Modules\TeamPortal\Http\Controllers\BDM\Support\TicketController::class, 'reply'])->name('tickets.reply');
    Route::resource('tickets', \Modules\TeamPortal\Http\Controllers\BDM\Support\TicketController::class)->except(['create', 'store', 'edit']);
    
    // BDM Leads & Kanban
    Route::get('leads', [\Modules\TeamPortal\Http\Controllers\BDM\LeadController::class, 'index'])->name('leads.index');
    Route::get('leads/create', [\Modules\TeamPortal\Http\Controllers\BDM\LeadController::class, 'create'])->name('leads.create');
    Route::post('leads', [\Modules\TeamPortal\Http\Controllers\BDM\LeadController::class, 'store'])->name('leads.store');
    Route::get('leads/{lead}', [\Modules\TeamPortal\Http\Controllers\BDM\LeadController::class, 'show'])->name('leads.show');
    Route::get('leads/{lead}/edit', [\Modules\TeamPortal\Http\Controllers\BDM\LeadController::class, 'edit'])->name('leads.edit');
    Route::put('leads/{lead}', [\Modules\TeamPortal\Http\Controllers\BDM\LeadController::class, 'update'])->name('leads.update');
    Route::delete('leads/{lead}', [\Modules\TeamPortal\Http\Controllers\BDM\LeadController::class, 'destroy'])->name('leads.destroy');
    Route::get('deals', [\Modules\TeamPortal\Http\Controllers\BDM\LeadController::class, 'kanban'])->name('deals.kanban');
    Route::post('leads/{lead}/status', [\Modules\TeamPortal\Http\Controllers\BDM\LeadController::class, 'updateStatus'])->name('leads.update-status');
    Route::post('leads/{lead}/log-activity', [\Modules\TeamPortal\Http\Controllers\BDM\LeadController::class, 'logActivity'])->name('leads.log-activity');

    // BDM Lead Sources
    Route::resource('lead-sources', \Modules\TeamPortal\Http\Controllers\BDM\LeadSourceController::class)->except(['show']);

    // BDM Communications (Ported from Admin)
    Route::prefix('communications')->group(function () {
        Route::get('contacts/export', [\Modules\TeamPortal\Http\Controllers\BDM\Communications\ContactController::class, 'export'])->name('contacts.export');
        Route::get('contacts/export-pdf', [\Modules\TeamPortal\Http\Controllers\BDM\Communications\ContactController::class, 'exportPdf'])->name('contacts.export-pdf');
        Route::post('contacts/import', [\Modules\TeamPortal\Http\Controllers\BDM\Communications\ContactController::class, 'import'])->name('contacts.import');
        Route::get('contacts/{contact}/send-email', [\Modules\TeamPortal\Http\Controllers\BDM\Communications\ContactController::class, 'sendEmail'])->name('contacts.send-email');
        Route::get('contacts/{contact}/send-sms', [\Modules\TeamPortal\Http\Controllers\BDM\Communications\ContactController::class, 'sendSms'])->name('contacts.send-sms');
        Route::post('contacts/{contact}/process-email', [\Modules\TeamPortal\Http\Controllers\BDM\Communications\ContactController::class, 'processEmail'])->name('contacts.process-email');
        Route::post('contacts/{contact}/process-sms', [\Modules\TeamPortal\Http\Controllers\BDM\Communications\ContactController::class, 'processSms'])->name('contacts.process-sms');
        Route::get('contacts/bulk-send-email', [\Modules\TeamPortal\Http\Controllers\BDM\Communications\ContactController::class, 'bulkSendEmail'])->name('contacts.bulk-send-email');
        Route::get('contacts/bulk-send-sms', [\Modules\TeamPortal\Http\Controllers\BDM\Communications\ContactController::class, 'bulkSendSms'])->name('contacts.bulk-send-sms');
        Route::post('contacts/bulk-delete', [\Modules\TeamPortal\Http\Controllers\BDM\Communications\ContactController::class, 'bulkDelete'])->name('contacts.bulk-delete');
        
        Route::resource('campaigns', \Modules\TeamPortal\Http\Controllers\BDM\Communications\CampaignController::class);
        Route::resource('contact-lists', \Modules\TeamPortal\Http\Controllers\BDM\Communications\ContactListController::class);
        Route::resource('contacts', \Modules\TeamPortal\Http\Controllers\BDM\Communications\ContactController::class);
        Route::resource('communication-scripts', \Modules\TeamPortal\Http\Controllers\BDM\Communications\CommunicationScriptController::class);
        Route::resource('email-templates', \Modules\TeamPortal\Http\Controllers\BDM\Communications\EmailTemplateController::class);
        Route::resource('sms-templates', \Modules\TeamPortal\Http\Controllers\BDM\Communications\SmsTemplateController::class);
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

// ─── QA Tester Portal ──────────────────────────────────────────────────────
Route::prefix('team/qa')
    ->name('team.qa.')
    ->middleware(['auth:web', \Modules\TeamPortal\Http\Middleware\CheckUserStatus::class])
    ->group(function () {

        // QA Dashboard
        Route::get('dashboard', [\Modules\TeamPortal\Http\Controllers\QA\QaDashboardController::class, 'index'])->name('dashboard');
        Route::get('/', fn() => redirect()->route('team.qa.dashboard'));

        // Bug Reports (full CRUD)
        Route::get('bugs', [\Modules\TeamPortal\Http\Controllers\QA\BugReportController::class, 'index'])->name('bugs.index');
        Route::get('bugs/create', [\Modules\TeamPortal\Http\Controllers\QA\BugReportController::class, 'create'])->name('bugs.create');
        Route::post('bugs', [\Modules\TeamPortal\Http\Controllers\QA\BugReportController::class, 'store'])->name('bugs.store');
        Route::get('bugs/{bug}', [\Modules\TeamPortal\Http\Controllers\QA\BugReportController::class, 'show'])->name('bugs.show');
        Route::get('bugs/{bug}/edit', [\Modules\TeamPortal\Http\Controllers\QA\BugReportController::class, 'edit'])->name('bugs.edit');
        Route::put('bugs/{bug}', [\Modules\TeamPortal\Http\Controllers\QA\BugReportController::class, 'update'])->name('bugs.update');
        Route::delete('bugs/{bug}', [\Modules\TeamPortal\Http\Controllers\QA\BugReportController::class, 'destroy'])->name('bugs.destroy');

        // Test Cases (assigned tasks for QA review)
        Route::get('test-cases', [\Modules\TeamPortal\Http\Controllers\QA\QaTestCaseController::class, 'index'])->name('test-cases.index');
        Route::get('test-cases/{task}', [\Modules\TeamPortal\Http\Controllers\QA\QaTestCaseController::class, 'show'])->name('test-cases.show');
        Route::patch('test-cases/{task}/status', [\Modules\TeamPortal\Http\Controllers\QA\QaTestCaseController::class, 'updateStatus'])->name('test-cases.update-status');
    });

