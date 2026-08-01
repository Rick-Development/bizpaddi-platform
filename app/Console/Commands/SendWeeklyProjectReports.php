<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Clients\Models\Client;
use Modules\ProjectManagement\Models\Project;
use App\Mail\WeeklyProjectReport;
use Illuminate\Support\Facades\Mail;

class SendWeeklyProjectReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:send-weekly {--client= : Send report for specific client ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send weekly project progress reports to all active clients';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting weekly project report generation...');

        // Get clients to process
        $clientsQuery = Client::where('is_active', true);
        
        if ($this->option('client')) {
            $clientsQuery->where('id', $this->option('client'));
        }

        $clients = $clientsQuery->get();

        if ($clients->isEmpty()) {
            $this->warn('No active clients found.');
            return Command::SUCCESS;
        }

        $totalReports = 0;
        $bar = $this->output->createProgressBar($clients->count());
        $bar->start();

        foreach ($clients as $client) {
            // Get active projects for this client
            $projects = Project::where('client_id', $client->id)
                ->where('status', 'active')
                ->get();

            foreach ($projects as $project) {
                try {
                    Mail::to($client->email)->send(new WeeklyProjectReport($client, $project));
                    $totalReports++;
                    
                    $this->newLine();
                    $this->info("✓ Sent report for '{$project->name}' to {$client->email}");
                } catch (\Exception $e) {
                    $this->newLine();
                    $this->error("✗ Failed to send report for '{$project->name}' to {$client->email}");
                    $this->error("  Error: {$e->getMessage()}");
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("✓ Weekly report generation complete!");
        $this->info("  Total reports sent: {$totalReports}");

        return Command::SUCCESS;
    }
}
