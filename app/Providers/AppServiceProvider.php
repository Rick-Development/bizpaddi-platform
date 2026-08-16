<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        \Modules\ProjectManagement\Models\Task::observe(\App\Observers\TaskObserver::class);
        \Modules\ProjectManagement\Models\Project::observe(\App\Observers\ProjectObserver::class);
        \Modules\ProjectManagement\Models\Comment::observe(\App\Observers\CommentObserver::class);
        \Modules\ProjectManagement\Models\ProjectMessage::observe(\App\Observers\ProjectMessageObserver::class);
        \Modules\ClientPortal\Models\ChatMessage::observe(\App\Observers\ClientChatMessageObserver::class);
    }
}
