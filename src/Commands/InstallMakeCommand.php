<?php

namespace Hanafalah\ModuleMedicalTreatment\Commands;

class InstallMakeCommand extends EnvironmentCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module-medical-treatment:install';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used for initial installation of module treatment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $provider = 'Hanafalah\ModuleMedicalTreatment\ModuleMedicalTreatmentServiceProvider';

        $this->comment('Installing Module Medical Treatment...');
        $this->callSilent('vendor:publish', [
            '--provider' => $provider,
            '--tag'      => 'config'
        ]);
        $this->info('✔️  Created config/module-medical-treatment.php');

        $this->callSilent('vendor:publish', [
            '--provider' => $provider,
            '--tag'      => 'migrations'
        ]);
        $this->info('✔️  Created migrations');
        $migrations = $this->setMigrationBasePath(database_path('migrations'))->canMigrate();
        $this->callSilent('migrate', [
            '--path' => $migrations
        ]);
        $this->info('✔️  App table migrated');

        $this->comment('hanafalah/module-medical-treatment installed successfully.');
    }
}
