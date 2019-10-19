<?php

namespace Tonysm\DockerPreset;

use Illuminate\Foundation\Console\PresetCommand;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Process\Process;

class DockerPresetServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            PresetCommand::macro('docker', function ($command) {
                try {
                    DockerPreset::install();
                } catch (AlreadyInstalledException $e) {
                    $command->warn('It seems the Docker preset was already installed in this project.');
                    $command->warn('Skipped.');
                    return;
                }
                $command->info('Docker preset scaffolding installed successfully.');
                $command->info(<<<EOF
Make sure you update your .env file with the required envs:

DOCKER_APP_PORT=80
DOCKER_DB_PORT=3306
DOCKER_TEST_DB_PORT=3307

Then you should be able to:

    - Install composer dependencies with:
        docker-compose run --rm app composer install
    - Boot the application: 
        docker-compose up -d

EOF
                );
            });

            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('docker-preset.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'docker-preset');

        // Register the main class to use with the facade
        $this->app->singleton('docker-preset', function () {
            return new DockerPreset;
        });
    }
}
