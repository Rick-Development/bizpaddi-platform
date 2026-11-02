<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load all module migrations regardless of directory casing on Linux (database/migrations vs Database/Migrations)
        $moduleMigrationDirs = array_merge(
            glob(base_path('Modules/*/database/migrations'), GLOB_ONLYDIR) ?: [],
            glob(base_path('Modules/*/Database/Migrations'), GLOB_ONLYDIR) ?: []
        );
        if (!empty($moduleMigrationDirs)) {
            $this->loadMigrationsFrom(array_unique($moduleMigrationDirs));
        }

        \Modules\ProjectManagement\Models\Task::observe(\App\Observers\TaskObserver::class);
        \Modules\ProjectManagement\Models\Project::observe(\App\Observers\ProjectObserver::class);
        \Modules\ProjectManagement\Models\Comment::observe(\App\Observers\CommentObserver::class);
        \Modules\ProjectManagement\Models\ProjectMessage::observe(\App\Observers\ProjectMessageObserver::class);
        \Modules\ClientPortal\Models\ChatMessage::observe(\App\Observers\ClientChatMessageObserver::class);
        \Modules\TeamPortal\Models\WorkLog::observe(\App\Observers\WorkLogObserver::class);

        // Load SMTP configuration from the settings table (overrides .env values).
        // Wrapped in try/catch so that the app still boots on a fresh install
        // before migrations have been run.
        $this->applyMailSettingsFromDatabase();

        // Auto-provision default email templates if any are missing
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('email_templates')) {
                \App\Services\DynamicTemplateService::provisionTemplates();
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::debug('AppServiceProvider: Could not provision email templates — ' . $e->getMessage());
        }
    }

    /**
     * Pull mail settings stored in the `settings` table and apply them to
     * the running Laravel config. This allows an admin to update SMTP
     * credentials via the panel without needing to edit .env manually.
     */
    protected function applyMailSettingsFromDatabase(): void
    {
        try {
            // Only attempt DB reads when the connection is available
            if (!\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                return;
            }

            $map = [
                'mail_mailer'       => ['mail.default',                  null],
                'mail_host'         => ['mail.mailers.smtp.host',        null],
                'mail_port'         => ['mail.mailers.smtp.port',        null],
                'mail_username'     => ['mail.mailers.smtp.username',    null],
                'mail_password'     => ['mail.mailers.smtp.password',    null],
                'mail_encryption'   => ['mail.mailers.smtp.scheme',      null],
                'mail_from_address' => ['mail.from.address',             null],
                'mail_from_name'    => ['mail.from.name',                null],
            ];

            foreach ($map as $settingKey => [$configKey]) {
                $value = \Modules\Settings\Models\Setting::get($settingKey);
                if ($value !== null && $value !== '') {
                    // Cast port to integer
                    if ($settingKey === 'mail_port') {
                        $value = (int) $value;
                    }
                    // Map encryption schemes to valid Symfony Mailer schemes
                    if ($settingKey === 'mail_encryption') {
                        if ($value === 'tls' || $value === 'starttls') {
                            $value = 'smtp';
                        } elseif ($value === 'ssl') {
                            $value = 'smtps';
                        } else {
                            $value = null;
                        }
                    }
                    Config::set($configKey, $value);
                }
            }
        } catch (\Exception $e) {
            // Silently skip — this is non-fatal (e.g. during migrations)
            \Illuminate\Support\Facades\Log::debug('AppServiceProvider: Could not load mail settings from DB — ' . $e->getMessage());
        }
    }
}

