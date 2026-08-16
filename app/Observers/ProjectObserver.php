<?php

namespace App\Observers;

use Modules\ProjectManagement\Models\Project;
use App\Notifications\ProjectCreatedNotification;
use App\Models\User;

class ProjectObserver
{
    /**
     * Handle the Project "created" event.
     *
     * @param  \Modules\ProjectManagement\Models\Project  $project
     * @return void
     */
    public function created(Project $project)
    {
        // Notify the client if they exist
        if ($project->client) {
            $project->client->notify(new ProjectCreatedNotification($project));
        }

        // Notify Project Managers
        $pms = User::whereHas('roles', function($q){
            $q->where('name', 'project_manager');
        })->get();
        
        foreach($pms as $pm) {
            if ($pm->id !== auth()->id()) {
                $pm->notify(new ProjectCreatedNotification($project));
            }
        }
    }
}
