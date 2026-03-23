<?php

declare(strict_types=1);

namespace Emiliosh\AutofirmaPlugin;

use Emiliosh\AutofirmaPlugin\Facades\Autofirma;
use Illuminate\Support\ServiceProvider;

class AutofirmaPluginServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/autofirma-plugin.php', 'autofirma-plugin');

        $this->app->singleton(AutofirmaService::class, function () {
            return new AutofirmaService(
                config('autofirma-plugin', []),
            );
        });

        $this->app->alias(AutofirmaService::class, 'autofirma');
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'autofirma-plugin');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'autofirma-plugin');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/autofirma-plugin.php' => config_path('autofirma-plugin.php'),
            ], 'autofirma-plugin-config');

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/autofirma-plugin'),
            ], 'autofirma-plugin-views');

            $this->publishes([
                __DIR__ . '/../resources/lang' => lang_path('vendor/autofirma-plugin'),
            ], 'autofirma-plugin-lang');
        }
    }
}
