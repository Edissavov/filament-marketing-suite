<?php

namespace VasilGerginski\MarketingSuite\Commands;

use Illuminate\Console\Command;

class MarketingSuiteCommand extends Command
{
    public $signature = 'marketing-suite:install';

    public $description = 'Install the Marketing Suite plugin (publishes all migrations and config)';

    public function handle(): int
    {
        $this->info('Publishing Marketing Suite migrations...');

        $this->call('vendor:publish', [
            '--provider' => 'Spatie\LaravelSettings\LaravelSettingsServiceProvider',
            '--tag' => 'migrations',
        ]);

        $this->call('vendor:publish', [
            '--provider' => 'AshAllenDesign\ShortURL\Providers\ShortURLProvider',
            '--tag' => 'short-url-migrations',
        ]);

        $this->call('vendor:publish', [
            '--provider' => 'AshAllenDesign\ShortURL\Providers\ShortURLProvider',
            '--tag' => 'short-url-config',
        ]);

        $this->call('vendor:publish', [
            '--tag' => 'filament-short-url-migrations',
        ]);

        $this->call('vendor:publish', [
            '--tag' => 'marketing-suite-migrations',
        ]);

        $this->call('vendor:publish', [
            '--tag' => 'marketing-suite-config',
        ]);

        $this->info('Running migrations...');
        $this->call('migrate');

        $this->info('Marketing Suite installed successfully.');

        return self::SUCCESS;
    }
}
