<?php

declare(strict_types=1);

namespace Emiliosh\AutofirmaPlugin\Tests;

use Emiliosh\AutofirmaPlugin\AutofirmaPlugin;
use Filament\Panel;

/**
 * Extiende TestCase registrando un panel Filament mínimo con el plugin,
 * necesario para que AutofirmaPlugin::get() y los componentes Livewire
 * que dependen de él funcionen correctamente en tests.
 */
abstract class FilamentTestCase extends TestCase
{
    #[\Override]
    protected function getEnvironmentSetUp($app): void
    {
        parent::getEnvironmentSetUp($app);

        $this->registerTestPanel($app);
    }

    private function registerTestPanel($app): void
    {
        $panel = Panel::make()
            ->id('test')
            ->default()
            ->plugin(
                AutofirmaPlugin::make()
                    ->algorithm('SHA512withRSA')
                    ->signatureFormat('XAdES')
                    ->verifySignature(false),
            );

        $app->afterResolving('filament', function ($filament) use ($panel): void {
            $filament->registerPanel($panel);
        });
    }
}