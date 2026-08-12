<?php

namespace Modules\AiEmployees\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class AiEmployeesServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'AiEmployees';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'aiemployees';

    /**
     * Command classes to register.
     *
     * @var string[]
     */
    protected array $commands = [
        \Modules\AiEmployees\Console\RunAutonomousRoutinesCommand::class,
    ];

    /**
     * Provider classes to register.
     *
     * @var string[]
     */
    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    /**
     * Define module schedules.
     * 
     * @param $schedule
     */
    protected function configureSchedules(Schedule $schedule): void
    {
        $schedule->command('ai:run-autonomous-routines')->everyMinute();
    }
}
