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

// Business Workspace Routes
Route::prefix('dashboard')->name('admin.')->group(function () {

    // Login & Register routes (guest only)
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login']);
        Route::get('/register', [\Modules\Admin\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register/send-otp', [\Modules\Admin\Http\Controllers\Auth\RegisterController::class, 'sendOtp'])->name('register.send-otp');
        Route::post('/register', [\Modules\Admin\Http\Controllers\Auth\RegisterController::class, 'register']);
    });

    // Protected admin routes
    Route::middleware(['Modules\Admin\Http\Middleware\AdminAuth'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index']);
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

        // Business Subscription & Plan Billing
        Route::get('/billing', [\Modules\Admin\Http\Controllers\BusinessBillingController::class, 'index'])->name('billing.index');
        Route::post('/billing/checkout', [\Modules\Admin\Http\Controllers\BusinessBillingController::class, 'renewOrUpgrade'])->name('billing.checkout');
        Route::post('/billing/topup', [\Modules\Admin\Http\Controllers\BusinessBillingController::class, 'topupWallet'])->name('billing.topup');
        Route::post('/billing/switch-model', [\Modules\Admin\Http\Controllers\BusinessBillingController::class, 'switchModel'])->name('billing.switch-model');
        Route::get('/billing/pay', [\Modules\Admin\Http\Controllers\BusinessBillingController::class, 'pay'])->name('billing.pay');
        Route::post('/billing/verify-now', [\Modules\Admin\Http\Controllers\BusinessBillingController::class, 'verifyNow'])->name('billing.verify-now');
        Route::get('/billing/callback', [\Modules\Admin\Http\Controllers\BusinessBillingController::class, 'callback'])->name('billing.callback');

        // Digital Storefront & Catalog
        Route::prefix('storefront')->name('storefront.')->group(function () {
            Route::get('/products', [\Modules\Admin\Http\Controllers\StorefrontController::class, 'index'])->name('products.index');
            Route::get('/products/create', [\Modules\Admin\Http\Controllers\StorefrontController::class, 'create'])->name('products.create');
            Route::post('/products', [\Modules\Admin\Http\Controllers\StorefrontController::class, 'store'])->name('products.store');
            Route::get('/products/{product}/edit', [\Modules\Admin\Http\Controllers\StorefrontController::class, 'edit'])->name('products.edit');
            Route::put('/products/{product}', [\Modules\Admin\Http\Controllers\StorefrontController::class, 'update'])->name('products.update');
            Route::delete('/products/{product}', [\Modules\Admin\Http\Controllers\StorefrontController::class, 'destroy'])->name('products.destroy');

            Route::get('/settings', [\Modules\Admin\Http\Controllers\StorefrontController::class, 'settings'])->name('settings');
            Route::put('/settings', [\Modules\Admin\Http\Controllers\StorefrontController::class, 'updateSettings'])->name('settings.update');
        });

        // Account Profile & Details
        Route::get('account/profile', [\Modules\Admin\Http\Controllers\AdminProfileController::class, 'edit'])->name('account.profile.edit');
        Route::put('account/profile', [\Modules\Admin\Http\Controllers\AdminProfileController::class, 'update'])->name('account.profile.update');
        Route::get('account/password', [\Modules\Admin\Http\Controllers\AdminPasswordController::class, 'edit'])->name('account.password.edit');
        Route::put('account/password', [\Modules\Admin\Http\Controllers\AdminPasswordController::class, 'update'])->name('account.password.update');

        // Administrators Management (Admin CRUD)
        Route::resource('administrators', \Modules\Admin\Http\Controllers\AdminManagementController::class);
        Route::patch('administrators/{administrator}/status', [\Modules\Admin\Http\Controllers\AdminManagementController::class, 'updateStatus'])->name('administrators.update-status');
        Route::redirect('admins', '/admin/administrators');
        
        // Push Notifications
        Route::get('notifications', [\Modules\Admin\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
        Route::post('notifications/send', [\Modules\Admin\Http\Controllers\NotificationController::class, 'send'])->name('notifications.send');
        Route::post('notifications/team/send', [\Modules\Admin\Http\Controllers\NotificationController::class, 'sendToTeam'])->name('notifications.team.send');

        // Password Management
        Route::get('passwords', [\Modules\Admin\Http\Controllers\PasswordManagementController::class, 'index'])->name('passwords.index');
        Route::post('passwords', [\Modules\Admin\Http\Controllers\PasswordManagementController::class, 'update'])->name('passwords.update');
        
        // Client Management
        Route::resource('clients', \Modules\Admin\Http\Controllers\ClientController::class);
        
        // Team Members Management
        Route::resource('team-members', \Modules\Admin\Http\Controllers\TeamMemberController::class);
        Route::patch('team-members/{user}/status', [\Modules\Admin\Http\Controllers\TeamMemberController::class, 'updateStatus'])->name('team-members.update-status');
        Route::get('team-members/{user}/login-as', [\Modules\Admin\Http\Controllers\TeamMemberController::class, 'impersonate'])->name('team-members.impersonate');
        Route::get('impersonate/stop', [\Modules\Admin\Http\Controllers\TeamMemberController::class, 'stopImpersonate'])->name('impersonate.stop');
        Route::get('team-performance', [\Modules\Admin\Http\Controllers\TeamMemberController::class, 'performance'])->name('team-performance.index');
        Route::get('team-performance/export-pdf', [\Modules\Admin\Http\Controllers\TeamMemberController::class, 'exportPdf'])->name('team-performance.export-pdf');
        Route::get('team-performance/assessment/{user}', [\Modules\Admin\Http\Controllers\TeamMemberController::class, 'assessment'])->name('team-performance.assessment');
        Route::post('team-performance/send-weekly-review', [\Modules\Admin\Http\Controllers\TeamMemberController::class, 'sendWeeklyReviewEmail'])->name('team-performance.send-weekly-review');
        Route::get('team-performance/preview-review-email/{user}', [\Modules\Admin\Http\Controllers\TeamMemberController::class, 'previewWeeklyReviewEmail'])->name('team-performance.preview-review-email');

        // Team Emails
        Route::get('team-emails/create', [\Modules\Admin\Http\Controllers\TeamEmailController::class, 'create'])->name('team-emails.create');
        Route::post('team-emails', [\Modules\Admin\Http\Controllers\TeamEmailController::class, 'send'])->name('team-emails.send');
        
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

            // Staff Payroll & Salaries
            Route::resource('payroll', \Modules\Admin\Http\Controllers\StaffSalaryController::class);
            Route::patch('payroll/{payroll}/toggle-status', [\Modules\Admin\Http\Controllers\StaffSalaryController::class, 'toggleStatus'])->name('payroll.toggle-status');

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
        
        // Platform CMS, Infrastructure & Corporate Marketing Routes (Platform Super Admin Only)
        Route::middleware([\Modules\Admin\Http\Middleware\EnsureSuperAdmin::class])->group(function () {
            // Hero Sliders Management
            Route::resource('hero-sliders', \Modules\Admin\Http\Controllers\HeroSliderController::class);
            
            // Featured Content Management
            Route::resource('featured-content', \Modules\Admin\Http\Controllers\FeaturedContentController::class);
            
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
            Route::get('applications/{application}/download-resume', [\Modules\Admin\Http\Controllers\ApplicationController::class, 'downloadResume'])->name('applications.download-resume');
            Route::resource('applications', \Modules\Admin\Http\Controllers\ApplicationController::class)->only(['index', 'show', 'update']);

            // SEO Manager
            Route::get('seo', [\Modules\Admin\Http\Controllers\SeoController::class, 'index'])->name('seo.index');
            Route::put('seo', [\Modules\Admin\Http\Controllers\SeoController::class, 'update'])->name('seo.update');

            // Navigation Management
            Route::resource('navigation', \Modules\Admin\Http\Controllers\NavigationItemController::class);

            // Home Page Management
            Route::prefix('home')->name('home.')->group(function () {
                Route::resource('stats', \Modules\Admin\Http\Controllers\HomeStatsController::class);
                Route::resource('features', \Modules\Admin\Http\Controllers\HomeFeaturesController::class);
                Route::resource('processes', \Modules\Admin\Http\Controllers\HomeProcessesController::class);
                Route::resource('ctas', \Modules\Admin\Http\Controllers\HomeCtaController::class);
                Route::resource('client-logos', \Modules\Admin\Http\Controllers\ClientLogoController::class);
                Route::resource('trust-badges', \Modules\Admin\Http\Controllers\TrustBadgeController::class);
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
        
        // Tenant Services Catalog
        Route::resource('services', \Modules\Admin\Http\Controllers\ServiceController::class);
        
        // Contact Management
        Route::post('contact-submissions/bulk-delete', [\Modules\Admin\Http\Controllers\ContactSubmissionController::class, 'bulkDestroy'])->name('contact-submissions.bulk-delete');
        Route::resource('contact-submissions', \Modules\Admin\Http\Controllers\ContactSubmissionController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
        
        // Lead Management
        Route::resource('leads', \Modules\Admin\Http\Controllers\LeadController::class);
        Route::post('leads/{lead}/assign', [\Modules\Admin\Http\Controllers\LeadController::class, 'assign'])->name('leads.assign');
        Route::patch('leads/{lead}/status', [\Modules\Admin\Http\Controllers\LeadController::class, 'updateStatus'])->name('leads.update-status');
        Route::post('leads/{lead}/contact', [\Modules\Admin\Http\Controllers\LeadController::class, 'markAsContacted'])->name('leads.mark-contacted');
        Route::post('leads/{lead}/convert', [\Modules\Admin\Http\Controllers\LeadController::class, 'convertToContact'])->name('leads.convert');
        Route::get('leads/export/csv', [\Modules\Admin\Http\Controllers\LeadController::class, 'exportCsv'])->name('leads.export-csv');
        Route::get('leads/export/pdf', [\Modules\Admin\Http\Controllers\LeadController::class, 'exportPdf'])->name('leads.export-pdf');
        Route::post('leads/import/csv', [\Modules\Admin\Http\Controllers\LeadController::class, 'importCsv'])->name('leads.import-csv');
        
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
        
        // Lead Form Settings
        Route::prefix('lead-settings')->name('lead-settings.')->group(function () {
            Route::get('/', [\Modules\Admin\Http\Controllers\LeadSettingsController::class, 'index'])->name('index');
            Route::post('types', [\Modules\Admin\Http\Controllers\LeadSettingsController::class, 'storeType'])->name('types.store');
            Route::put('types/{type}', [\Modules\Admin\Http\Controllers\LeadSettingsController::class, 'updateType'])->name('types.update');
            Route::delete('types/{type}', [\Modules\Admin\Http\Controllers\LeadSettingsController::class, 'destroyType'])->name('types.destroy');
            
            Route::post('budgets', [\Modules\Admin\Http\Controllers\LeadSettingsController::class, 'storeBudget'])->name('budgets.store');
            Route::put('budgets/{budget}', [\Modules\Admin\Http\Controllers\LeadSettingsController::class, 'updateBudget'])->name('budgets.update');
            Route::delete('budgets/{budget}', [\Modules\Admin\Http\Controllers\LeadSettingsController::class, 'destroyBudget'])->name('budgets.destroy');
            
            Route::post('timelines', [\Modules\Admin\Http\Controllers\LeadSettingsController::class, 'storeTimeline'])->name('timelines.store');
            Route::put('timelines/{timeline}', [\Modules\Admin\Http\Controllers\LeadSettingsController::class, 'updateTimeline'])->name('timelines.update');
            Route::delete('timelines/{timeline}', [\Modules\Admin\Http\Controllers\LeadSettingsController::class, 'destroyTimeline'])->name('timelines.destroy');
        });
        
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
        Route::get('tasks/export-pdf', [TaskController::class, 'exportPdf'])->name('tasks.export-pdf');
        Route::get('tasks/export-csv', [TaskController::class, 'exportCsv'])->name('tasks.export-csv');
        Route::resource('tasks', TaskController::class);
        Route::get('kanban', [KanbanController::class, 'index'])->name('kanban.index');
        Route::post('kanban', [KanbanController::class, 'index'])->name('kanban.filter');
        Route::post('kanban/update-order', [KanbanController::class, 'updateOrder'])->name('kanban.update-order');
        Route::get('project-management/work-logs/export', [\Modules\Admin\Http\Controllers\WorkLogController::class, 'exportPage'])->name('work-logs.export-page');
        Route::post('project-management/work-logs/bulk-checkout', [\Modules\Admin\Http\Controllers\WorkLogController::class, 'bulkCheckout'])->name('work-logs.bulk-checkout');
        Route::get('project-management/work-logs/export-pdf', [\Modules\Admin\Http\Controllers\WorkLogController::class, 'exportPdf'])->name('work-logs.export-pdf');
        Route::get('project-management/work-logs/export-csv', [\Modules\Admin\Http\Controllers\WorkLogController::class, 'exportCsv'])->name('work-logs.export-csv');
        Route::get('project-management/work-logs/{workLog}/pdf', [\Modules\Admin\Http\Controllers\WorkLogController::class, 'exportSinglePdf'])->name('work-logs.single-pdf');
        Route::get('project-management/work-logs', [\Modules\Admin\Http\Controllers\WorkLogController::class, 'index'])->name('work-logs.index');
        Route::get('project-management/work-logs/{workLog}', [\Modules\Admin\Http\Controllers\WorkLogController::class, 'show'])->name('work-logs.show');
        Route::resource('kpis', \Modules\Admin\Http\Controllers\KPIController::class);
        Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.update-status');
        Route::patch('tasks/{task}/approval', [TaskController::class, 'updateApproval'])->name('tasks.update-approval');
        Route::get('team-monitoring', [\Modules\Admin\Http\Controllers\TeamMonitoringController::class, 'index'])->name('team-monitoring.index');
        Route::resource('bugs', \Modules\Admin\Http\Controllers\BugReportController::class);
        Route::patch('bugs/{bug}/status', [\Modules\Admin\Http\Controllers\BugReportController::class, 'updateStatus'])->name('bugs.update-status');

        // Admin Unified Chat
        Route::get('chat/{conversation?}', [\Modules\Admin\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
        Route::get('chat-api/conversations', [\Modules\Admin\Http\Controllers\ChatController::class, 'getConversations'])->name('chat.conversations');
        Route::get('chat-api/conversations/{conversation}/messages', [\Modules\Admin\Http\Controllers\ChatController::class, 'getMessages'])->name('chat.messages');
        Route::post('chat-api/conversations/{conversation}/messages', [\Modules\Admin\Http\Controllers\ChatController::class, 'sendMessage'])->name('chat.messages.store');
        Route::post('chat-api/conversations/{conversation}/read', [\Modules\Admin\Http\Controllers\ChatController::class, 'markAsRead'])->name('chat.messages.read');
        Route::post('chat-api/conversations/start', [\Modules\Admin\Http\Controllers\ChatController::class, 'startConversation'])->name('chat.conversations.start');
        Route::get('chat-api/unread-count', [\Modules\Admin\Http\Controllers\ChatController::class, 'getUnreadCount'])->name('chat.unread-count');
        Route::post('chat-api/groups', [\Modules\Admin\Http\Controllers\ChatController::class, 'createGroup'])->name('chat.groups.create');
        Route::post('chat-api/conversations/{conversation}/members/add', [\Modules\Admin\Http\Controllers\ChatController::class, 'addGroupMember'])->name('chat.groups.members.add');
        Route::post('chat-api/conversations/{conversation}/members/remove', [\Modules\Admin\Http\Controllers\ChatController::class, 'removeGroupMember'])->name('chat.groups.members.remove');


        // Admin Centralized Comment Feed
        Route::get('comments-feed', [\Modules\Admin\Http\Controllers\CommentFeedController::class, 'index'])->name('comments-feed.index');
        Route::get('comments-feed/unread-count', [\Modules\Admin\Http\Controllers\CommentFeedController::class, 'getUnreadCount'])->name('comments-feed.unread-count');

        
        // Corporate Management
        Route::resource('subsidiaries', \Modules\Admin\Http\Controllers\SubsidiaryController::class);
        Route::resource('legal-pages', \Modules\Admin\Http\Controllers\LegalPageController::class);
        
        // Settings
        Route::get('settings', [\Modules\Admin\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [\Modules\Admin\Http\Controllers\SettingController::class, 'update'])->name('settings.update');

        // Mail / SMTP Settings
        Route::prefix('settings/mail')->name('settings.mail.')->group(function () {
            Route::get('/', [\Modules\Admin\Http\Controllers\MailSettingsController::class, 'index'])->name('index');
            Route::put('/', [\Modules\Admin\Http\Controllers\MailSettingsController::class, 'update'])->name('update');
            Route::post('/test', [\Modules\Admin\Http\Controllers\MailSettingsController::class, 'sendTest'])->name('test');
        });
    });
});
