<?php

declare(strict_types=1);

namespace Emiliosh\AutofirmaPlugin\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Emiliosh\AutofirmaPlugin\AutofirmaPlugin;
use Emiliosh\AutofirmaPlugin\AutofirmaPluginServiceProvider;
use Filament\Actions\ActionsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Panel;
use Filament\PanelRegistry;
use Filament\Schemas\SchemasServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    #[\Override]
    protected function getPackageProviders($app): array
    {
        return [
            // Filament sub-packages must be registered BEFORE LivewireServiceProvider
            // because SupportServiceProvider binds DataStore::class, which would
            // drop Livewire's DataStore singleton if registered after Livewire.
            BladeIconsServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            SupportServiceProvider::class,
            ActionsServiceProvider::class,
            SchemasServiceProvider::class,
            InfolistsServiceProvider::class,
            NotificationsServiceProvider::class,
            WidgetsServiceProvider::class,
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

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $panel = Panel::make()
            ->default()
            ->id('test')
            ->plugin(AutofirmaPlugin::make());

        app(PanelRegistry::class)->register($panel);
    }

    /**
     * Registra el plugin en un panel de prueba.
     */
    protected function makePlugin(): AutofirmaPlugin
    {
        return AutofirmaPlugin::make();
    }
}
