<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class SystemController extends Controller
{
    public function index()
    {
        return view('admin::system.index');
    }

    public function runCommand(Request $request)
    {
        $command = $request->input('command');
        
        $allowedArtisanCommands = [
            'optimize' => 'optimize',
            'cache-clear' => 'cache:clear',
            'config-clear' => 'config:clear',
            'route-clear' => 'route:clear',
            'view-clear' => 'view:clear',
            'optimize-clear' => 'optimize:clear',
            'storage-link' => 'storage:link',
            'migrate' => 'migrate --force',
            'db-seed' => 'db:seed --force',
            'queue-restart' => 'queue:restart',
            'queue-clear' => 'queue:clear',
            'queue-flush' => 'queue:flush',
            'queue-pause' => 'queue:pause',
            'queue-resume' => 'queue:resume',
            'queue-retry-all' => 'queue:retry all',
            'queue-prune-batches' => 'queue:prune-batches',
            'queue-prune-failed' => 'queue:prune-failed',
            'queue-table' => 'queue:table',
            'queue-batches-table' => 'queue:batches-table',
            'queue-failed-table' => 'queue:failed-table',
            'permission-cache-reset' => 'permission:cache-reset',
            'event-clear' => 'event:clear',
            'event-cache' => 'event:cache',
            'module-migrate' => 'module:migrate',
            'module-seed' => 'module:seed',
            'medialibrary-clean' => 'medialibrary:clean --force',
        ];

        $allowedShellCommands = [
            'composer-dump' => 'composer dump-autoload',
        ];

        try {
            if (isset($allowedArtisanCommands[$command])) {
                Artisan::call($allowedArtisanCommands[$command]);
                $output = Artisan::output();
                return back()->with('success', "Artisan command '{$allowedArtisanCommands[$command]}' executed. Output: " . $output);
            }

            if (isset($allowedShellCommands[$command])) {
                // Change directory to project root for shell commands
                $projectRoot = base_path();
                $cmd = $allowedShellCommands[$command];
                $output = shell_exec("cd $projectRoot && $cmd 2>&1");
                return back()->with('success', "Shell command '$cmd' executed. Output: " . $output);
            }

            return back()->with('error', 'Invalid command.');
        } catch (\Exception $e) {
            Log::error("Failed to run command: " . $e->getMessage());
            return back()->with('error', 'Failed to run command: ' . $e->getMessage());
        }
    }
}
