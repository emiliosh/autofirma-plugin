<?php

declare(strict_types=1);

namespace Emiliosh\AutofirmaPlugin\Tests;

use Emiliosh\AutofirmaPlugin\AutofirmaPlugin;
use Emiliosh\AutofirmaPlugin\AutofirmaPluginServiceProvider;
use Filament\FilamentServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    #[\Override]
    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            FilamentServiceProvider::class,
            AutofirmaPluginServiceProvider::class,
        ];
    }

    #[\Override]
    protected function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
        config()->set('app.key', 'base64:' . base64_encode(random_bytes(32)));
    }

    /**
     * Registra el plugin en un panel de prueba.
     */
    protected function makePlugin(): AutofirmaPlugin
    {
        return AutofirmaPlugin::make();
    }
}
