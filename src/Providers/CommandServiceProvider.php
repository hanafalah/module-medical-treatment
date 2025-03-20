<?php

declare(strict_types=1);

namespace Hanafalah\ModuleMedicalTreatment\Providers;

use Hanafalah\ModuleMedicalTreatment\Commands;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    private $commands = [
        Commands\InstallMakeCommand::class,
    ];


    public function register()
    {
        $this->commands(config('module-medical-treatment.commands', $this->commands));
    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */

    public function provides()
    {
        return $this->commands;
    }
}
