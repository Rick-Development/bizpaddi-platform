<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\Auth\LoginController;
use Modules\Admin\Http\Controllers\DashboardController;
use Modules\Admin\Http\Controllers\TaskController;
use Modules\Admin\Http\Controllers\EmailTemplateController;
use Modules\Admin\Http\Controllers\KanbanController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/dashboard');

    // Login routes (guest only)
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login']);
    });

    // Protected admin routes
    Route::middleware(['Modules\Admin\Http\Middleware\AdminAuth'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

        // Account
        Route::get('account/password', [\Modules\Admin\Http\Controllers\AdminPasswordController::class, 'edit'])->name('account.password.edit');
        Route::put('account/password', [\Modules\Admin\Http\Controllers\AdminPasswordController::class, 'update'])->name('account.password.update');
        
        // Push Notifications
        Route::get('notifications', [\Modules\Admin\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
        Route::post('notifications/send', [\Modules\Admin\Http\Controllers\NotificationController::class, 'send'])->name('notifications.send');

        // Password Management
        Route::get('passwords', [\Modules\Admin\Http\Controllers\PasswordManagementController::class, 'index'])->name('passwords.index');
        Route::post('passwords', [\Modules\Admin\Http\Controllers\PasswordManagementController::class, 'update'])->name('passwords.update');
        
        // Client Management
        Route::resource('clients', \Modules\Admin\Http\Controllers\ClientController::class);
        
        // Team Members Management
        Route::resource('team-members', \Modules\Admin\Http\Controllers\TeamMemberController::class);
        Route::patch('team-members/{user}/status', [\Modules\Admin\Http\Controllers\TeamMemberController::class, 'updateStatus'])->name('team-members.update-status');
        Route::get('team-performance', [\Modules\Admin\Http\Controllers\TeamMemberController::class, 'performance'])->name('team-performance.index');
        
        // Departments & Roles
        Route::resource('departments', \Modules\Admin\Http\Controllers\DepartmentController::class);
        Route::resource('roles', \Modules\Admin\Http\Controllers\RoleController::class);
        
        // Team Utilities
        Route::get('team-utilities', [\Modules\Admin\Http\Controllers\TeamUtilityController::class, 'index'])->name('team-utilities.index');
        Route::post('team-utilities/purchase', [\Modules\Admin\Http\Controllers\TeamUtilityController::class, 'purchase'])->name('team-utilities.purchase');
        Route::get('vas/service/{serviceId}/categories', [\Modules\Admin\Http\Controllers\TeamUtilityController::class, 'fetchServiceCategories'])->name('vas.categories');
        Route::get('vas/service-category/{categoryId}/products', [\Modules\Admin\Http\Controllers\TeamUtilityController::class, 'fetchCategoryProducts'])->name('vas.products');
        Route::get('team-utilities/success/{transaction}', [\Modules\Admin\Http\Controllers\TeamUtilityController::class, 'success'])->name('team-utilities.success');
        
        // Accounting Management
        Route::prefix('accounting')->name('accounting.')->group(function () {
            // Invoices
            Route::resource('invoices', \Modules\Admin\Http\Controllers\InvoiceController::class);
            Route::post('invoices/{invoice}/send', [\Modules\Admin\Http\Controllers\InvoiceController::class, 'send'])->name('invoices.send');
            Route::post('invoices/{invoice}/mark-paid', [\Modules\Admin\Http\Controllers\InvoiceController::class, 'markPaid'])->name('invoices.mark-paid');
            Route::get('invoices/{invoice}/pdf', [\Modules\Admin\Http\Controllers\InvoiceController::class, 'downloadPDF'])->name('invoices.pdf');
            
            // Service Orders
            Route::resource('service-orders', \Modules\Admin\Http\Controllers\ServiceOrderController::class);
            Route::post('service-orders/{id}/convert-quote', [\Modules\Admin\Http\Controllers\ServiceOrderController::class, 'convertToQuote'])->name('service-orders.convert-quote');
            Route::post('service-orders/{id}/convert-invoice', [\Modules\Admin\Http\Controllers\ServiceOrderController::class, 'convertToInvoice'])->name('service-orders.convert-invoice');
            Route::post('service-orders/{id}/convert-contract', [\Modules\Admin\Http\Controllers\ServiceOrderController::class, 'convertToContract'])->name('service-orders.convert-contract');

            // Quotes
            Route::resource('quotes', \Modules\Admin\Http\Controllers\QuoteController::class);
            Route::post('quotes/{quote}/send', [\Modules\Admin\Http\Controllers\QuoteController::class, 'send'])->name('quotes.send');
            Route::post('quotes/{quote}/approve', [\Modules\Admin\Http\Controllers\QuoteController::class, 'approve'])->name('quotes.approve');
            Route::post('quotes/{quote}/convert', [\Modules\Admin\Http\Controllers\QuoteController::class, 'convertToInvoice'])->name('quotes.convert');
            Route::get('quotes/{quote}/pdf', [\Modules\Admin\Http\Controllers\QuoteController::class, 'downloadPDF'])->name('quotes.pdf');

            Route::resource('currencies', \Modules\Admin\Http\Controllers\CurrencyController::class);
            
            // Services & Categories
            Route::resource('services', \Modules\Admin\Http\Controllers\ServiceController::class);
            Route::resource('service-categories', \Modules\Admin\Http\Controllers\ServiceCategoryController::class);
            
            // Transactions
            Route::resource('transactions', \Modules\Admin\Http\Controllers\TransactionController::class);
            Route::post('transactions/{transaction}/complete', [\Modules\Admin\Http\Controllers\TransactionController::class, 'markComplete'])->name('transactions.complete');
            
            // Accounts
            Route::resource('accounts', \Modules\Admin\Http\Controllers\AccountController::class);
            Route::post('accounts/{account}/update-balance', [\Modules\Admin\Http\Controllers\AccountController::class, 'updateBalance'])->name('accounts.update-balance');
            
            // Balance Sheet
            Route::get('balance-sheet', [\Modules\Admin\Http\Controllers\BalanceSheetController::class, 'index'])->name('balance-sheet');
            
            // Feature Pricing
            Route::resource('feature-pricing', \Modules\Admin\Http\Controllers\FeaturePricingController::class);

            // Salary Disbursements
            Route::resource('salaries', \Modules\Admin\Http\Controllers\SalaryController::class);

            // SafeHaven Transactions
            Route::get('bank-transactions', [\Modules\Admin\Http\Controllers\SafeHavenController::class, 'index'])->name('bank.transactions');
        });
        
        // Contracts Management (Standalone)
        Route::prefix('contracts')->name('contracts.')->group(function () {
            // Templates
            Route::prefix('templates')->name('templates.')->group(function () {
                Route::get('/', [\Modules\Admin\Http\Controllers\ContractTemplateController::class, 'index'])->name('index');
                Route::get('/create', [\Modules\Admin\Http\Controllers\ContractTemplateController::class, 'create'])->name('create');
                Route::post('/', [\Modules\Admin\Http\Controllers\ContractTemplateController::class, 'store'])->name('store');
                Route::get('/{template}/edit', [\Modules\Admin\Http\Controllers\ContractTemplateController::class, 'edit'])->name('edit');
                Route::put('/{template}', [\Modules\Admin\Http\Controllers\ContractTemplateController::class, 'update'])->name('update');
                Route::delete('/{template}', [\Modules\Admin\Http\Controllers\ContractTemplateController::class, 'destroy'])->name('destroy');
            });

            Route::get('/', [\Modules\Admin\Http\Controllers\ContractController::class, 'index'])->name('index');
            Route::get('/create', [\Modules\Admin\Http\Controllers\ContractController::class, 'create'])->name('create');
            Route::post('/', [\Modules\Admin\Http\Controllers\ContractController::class, 'store'])->name('store');
            Route::get('/expiring-soon', [\Modules\Admin\Http\Controllers\ContractController::class, 'expiringSoon'])->name('expiring');
            Route::post('/load-template', [\Modules\Admin\Http\Controllers\ContractController::class, 'loadTemplate'])->name('load-template');
            
            Route::get('/{contract}', [\Modules\Admin\Http\Controllers\ContractController::class, 'show'])->name('show');
            Route::get('/{contract}/edit', [\Modules\Admin\Http\Controllers\ContractController::class, 'edit'])->name('edit');
            Route::put('/{contract}', [\Modules\Admin\Http\Controllers\ContractController::class, 'update'])->name('update');
            Route::delete('/{contract}', [\Modules\Admin\Http\Controllers\ContractController::class, 'destroy'])->name('destroy');
            Route::get('/{contract}/pdf', [\Modules\Admin\Http\Controllers\ContractController::class, 'downloadPDF'])->name('pdf');
            Route::get('/{contract}/docx', [\Modules\Admin\Http\Controllers\ContractController::class, 'downloadDocx'])->name('docx');
            Route::post('/{contract}/signature', [\Modules\Admin\Http\Controllers\ContractController::class, 'uploadSignature'])->name('signature');
            Route::post('/{contract}/status', [\Modules\Admin\Http\Controllers\ContractController::class, 'updateStatus'])->name('status');
        });
        
        // Hero Sliders Management
        Route::resource('hero-sliders', \Modules\Admin\Http\Controllers\HeroSliderController::class);
        
        // Featured Content Management
        Route::resource('featured-content', \Modules\Admin\Http\Controllers\FeaturedContentController::class);
        
        // Services Management
        Route::resource('services', \Modules\Admin\Http\Controllers\ServiceController::class);
        
        // Testimonials Management
        Route::resource('testimonials', \Modules\Admin\Http\Controllers\TestimonialController::class);
        
        // About Section Management
        Route::resource('leaders', \Modules\Admin\Http\Controllers\LeaderController::class);
        Route::resource('timeline', \Modules\Admin\Http\Controllers\TimelineController::class);
        Route::resource('awards', \Modules\Admin\Http\Controllers\AwardController::class);
        
        // Portfolio Management
        Route::resource('projects', \Modules\Admin\Http\Controllers\ProjectController::class);
        Route::resource('industries', \Modules\Admin\Http\Controllers\IndustryController::class);
        
        // Blog Management
        Route::resource('posts', \Modules\Admin\Http\Controllers\PostController::class);
        Route::resource('categories', \Modules\Admin\Http\Controllers\CategoryController::class);
        
        // Careers Management
        Route::resource('jobs', \Modules\Admin\Http\Controllers\JobController::class);
        Route::resource('applications', \Modules\Admin\Http\Controllers\ApplicationController::class)->only(['index', 'show', 'update']);
        
        // Contact Management
        Route::post('contact-submissions/bulk-delete', [\Modules\Admin\Http\Controllers\ContactSubmissionController::class, 'bulkDestroy'])->name('contact-submissions.bulk-delete');
        Route::resource('contact-submissions', \Modules\Admin\Http\Controllers\ContactSubmissionController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
        
        // Lead Management
        Route::resource('leads', \Modules\Admin\Http\Controllers\LeadController::class);
        Route::post('leads/{lead}/assign', [\Modules\Admin\Http\Controllers\LeadController::class, 'assign'])->name('leads.assign');
        Route::patch('leads/{lead}/status', [\Modules\Admin\Http\Controllers\LeadController::class, 'updateStatus'])->name('leads.update-status');
        Route::post('leads/{lead}/contact', [\Modules\Admin\Http\Controllers\LeadController::class, 'markAsContacted'])->name('leads.mark-contacted');
        Route::post('leads/{lead}/convert', [\Modules\Admin\Http\Controllers\LeadController::class, 'convertToContact'])->name('leads.convert');
        
        // Communications Management
        // Support Tickets
        Route::resource('tickets', \Modules\Admin\Http\Controllers\TicketController::class);
        Route::post('tickets/{ticket}/reply', [\Modules\Admin\Http\Controllers\TicketController::class, 'reply'])->name('tickets.reply');

        // Communications Management
        Route::resource('campaigns', \Modules\Admin\Http\Controllers\CampaignController::class);
        Route::post('campaigns/{campaign}/send', [\Modules\Admin\Http\Controllers\CampaignController::class, 'send'])->name('campaigns.send');
        
        Route::resource('contact-lists', \Modules\Admin\Http\Controllers\ContactListController::class);
        Route::post('contact-lists/{contactList}/add-contacts', [\Modules\Admin\Http\Controllers\ContactListController::class, 'addContacts'])->name('contact-lists.add-contacts');
        Route::delete('contact-lists/{contactList}/contacts/{contact}', [\Modules\Admin\Http\Controllers\ContactListController::class, 'removeContact'])->name('contact-lists.remove-contact');
        
        // Contacts Management
        Route::post('contacts/bulk-delete', [\Modules\Admin\Http\Controllers\ContactController::class, 'bulkDelete'])->name('contacts.bulk-delete');
        Route::get('contacts/bulk-send-email', [\Modules\Admin\Http\Controllers\ContactController::class, 'bulkSendEmail'])->name('contacts.bulk-send-email');
        Route::post('contacts/bulk-send-email', [\Modules\Admin\Http\Controllers\ContactController::class, 'processBulkEmail'])->name('contacts.process-bulk-email');
        Route::get('contacts/bulk-send-sms', [\Modules\Admin\Http\Controllers\ContactController::class, 'bulkSendSms'])->name('contacts.bulk-send-sms');
        Route::post('contacts/bulk-send-sms', [\Modules\Admin\Http\Controllers\ContactController::class, 'processBulkSms'])->name('contacts.process-bulk-sms');
        Route::post('contacts/import', [\Modules\Admin\Http\Controllers\ContactController::class, 'import'])->name('contacts.import');
        Route::get('contacts/export', [\Modules\Admin\Http\Controllers\ContactController::class, 'export'])->name('contacts.export');
        Route::get('contacts/export-pdf', [\Modules\Admin\Http\Controllers\ContactController::class, 'exportPdf'])->name('contacts.export-pdf');
        
        Route::get('contacts/{contact}/send-email', [\Modules\Admin\Http\Controllers\ContactController::class, 'sendEmail'])->name('contacts.send-email');
        Route::post('contacts/{contact}/send-email', [\Modules\Admin\Http\Controllers\ContactController::class, 'processEmail'])->name('contacts.process-email');
        Route::get('contacts/{contact}/send-sms', [\Modules\Admin\Http\Controllers\ContactController::class, 'sendSms'])->name('contacts.send-sms');
        Route::post('contacts/{contact}/send-sms', [\Modules\Admin\Http\Controllers\ContactController::class, 'processSms'])->name('contacts.process-sms');

        Route::resource('contacts', \Modules\Admin\Http\Controllers\ContactController::class);
        
        // Cold Calling Management
        // Communication Scripts & Channels
        Route::resource('communication-channels', \Modules\Admin\Http\Controllers\CommunicationChannelController::class);
        
        Route::resource('communication-scripts', \Modules\Admin\Http\Controllers\CommunicationScriptController::class);
        
        // Script Steps API (for builder)
        Route::post('communication-script-steps', [\Modules\Admin\Http\Controllers\CommunicationScriptStepController::class, 'store'])->name('communication-script-steps.store');
        Route::put('communication-script-steps/{step}', [\Modules\Admin\Http\Controllers\CommunicationScriptStepController::class, 'update'])->name('communication-script-steps.update');
        Route::delete('communication-script-steps/{step}', [\Modules\Admin\Http\Controllers\CommunicationScriptStepController::class, 'destroy'])->name('communication-script-steps.destroy');
        Route::post('communication-script-steps/reorder', [\Modules\Admin\Http\Controllers\CommunicationScriptStepController::class, 'reorder'])->name('communication-script-steps.reorder');
        
        // Exceptions API
        Route::post('communication-script-steps/{step}/exceptions', [\Modules\Admin\Http\Controllers\CommunicationScriptStepController::class, 'storeException'])->name('communication-script-steps.exceptions.store');
        Route::delete('communication-script-exceptions/{exception}', [\Modules\Admin\Http\Controllers\CommunicationScriptStepController::class, 'destroyException'])->name('communication-script-exceptions.destroy');

        Route::resource('email-templates', EmailTemplateController::class);
        Route::resource('sms-templates', \Modules\Admin\Http\Controllers\SmsTemplateController::class);

        // Project Management
        Route::resource('pm-projects', \Modules\Admin\Http\Controllers\PMProjectController::class);
        Route::get('pm-projects/{pm_project}/milestones', [\Modules\Admin\Http\Controllers\PMProjectController::class, 'milestones'])->name('pm-projects.milestones');
        Route::resource('pm-milestones', \Modules\Admin\Http\Controllers\PMMilestoneController::class);
        Route::resource('tasks', TaskController::class);
        Route::get('kanban', [KanbanController::class, 'index'])->name('kanban.index');
        Route::post('kanban/update-order', [KanbanController::class, 'updateOrder'])->name('kanban.update-order');
        Route::get('project-management/work-logs', [\Modules\Admin\Http\Controllers\WorkLogController::class, 'index'])->name('work-logs.index');
        Route::get('project-management/work-logs/{workLog}', [\Modules\Admin\Http\Controllers\WorkLogController::class, 'show'])->name('work-logs.show');
        Route::resource('kpis', \Modules\Admin\Http\Controllers\KPIController::class);
        Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.update-status');
        Route::patch('tasks/{task}/approval', [TaskController::class, 'updateApproval'])->name('tasks.update-approval');
        Route::get('team-monitoring', [\Modules\Admin\Http\Controllers\TeamMonitoringController::class, 'index'])->name('team-monitoring.index');

        
        // Corporate Management
        Route::resource('subsidiaries', \Modules\Admin\Http\Controllers\SubsidiaryController::class);
        Route::resource('legal-pages', \Modules\Admin\Http\Controllers\LegalPageController::class);
        
        // Settings
        Route::get('settings', [\Modules\Admin\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [\Modules\Admin\Http\Controllers\SettingController::class, 'update'])->name('settings.update');

        // SEO Manager
        Route::get('seo', [\Modules\Admin\Http\Controllers\SeoController::class, 'index'])->name('seo.index');
        Route::put('seo', [\Modules\Admin\Http\Controllers\SeoController::class, 'update'])->name('seo.update');
        
        // Store
        Route::get('store', [\Modules\Admin\Http\Controllers\StoreController::class, 'index'])->name('store.index');

        // Navigation Management
        Route::resource('navigation', \Modules\Admin\Http\Controllers\NavigationItemController::class);
        
        // Home Page Management
        Route::prefix('home')->name('home.')->group(function () {
            Route::resource('stats', \Modules\Admin\Http\Controllers\HomeStatsController::class);
            Route::resource('features', \Modules\Admin\Http\Controllers\HomeFeaturesController::class);
            Route::resource('processes', \Modules\Admin\Http\Controllers\HomeProcessesController::class);
            Route::resource('ctas', \Modules\Admin\Http\Controllers\HomeCtaController::class);
        });
        
        // Investor Relations
        Route::prefix('investor')->name('investor.')->group(function () {
            Route::resource('financial-reports', \Modules\Admin\Http\Controllers\FinancialReportController::class);
            Route::resource('press-releases', \Modules\Admin\Http\Controllers\InvestorPressReleaseController::class);
            Route::resource('stock-data', \Modules\Admin\Http\Controllers\StockDataController::class);
            Route::resource('shareholder-meetings', \Modules\Admin\Http\Controllers\ShareholderMeetingController::class);
        });
        
        // Visitor Sessions Management
        Route::resource('visitors', \Modules\Admin\Http\Controllers\VisitorController::class)->only(['index', 'show', 'destroy']);
        Route::post('visitors/bulk-delete', [\Modules\Admin\Http\Controllers\VisitorController::class, 'bulkDelete'])->name('visitors.bulk-delete');
        
        // Rate Limiting
        Route::get('rate-limits/sync', [\Modules\Admin\Http\Controllers\RateLimitController::class, 'sync'])->name('rate-limits.sync');
        Route::resource('rate-limits', \Modules\Admin\Http\Controllers\RateLimitController::class)->only(['index', 'edit', 'update', 'destroy']);

        // System Maintenance
        Route::get('system', [\Modules\Admin\Http\Controllers\SystemController::class, 'index'])->name('system.index');
        Route::post('system/run', [\Modules\Admin\Http\Controllers\SystemController::class, 'runCommand'])->name('system.run');
    });
});
