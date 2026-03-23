<?php

declare(strict_types=1);

namespace Emiliosh\AutofirmaPlugin;

use Emiliosh\AutofirmaPlugin\Livewire\AutofirmaModal;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Livewire\Livewire;

class AutofirmaPlugin implements Plugin
{
    protected string $autofirmaJsUrl = 'https://estaticos.redsara.es/comunes/autofirma/currentversion/AutoScript.js';

    protected bool $useLocalService = false;

    protected int $localServicePort = 51234;

    protected string $algorithm = 'SHA512withRSA';

    protected string $signatureFormat = 'XAdES';

    protected bool $verifySignature = true;

    // -------------------------------------------------------------------------
    // Filament Plugin contract
    // -------------------------------------------------------------------------

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function getId(): string
    {
        return 'autofirma-plugin';
    }

    public function register(Panel $panel): void
    {
        Livewire::component('autofirma-modal', AutofirmaModal::class);
    }

    public function boot(Panel $panel): void
    {
        FilamentAsset::register([
            Js::make('autofirma-plugin', __DIR__ . '/../resources/js/autofirma.js'),
            Js::make('autofirma-autoscript', $this->autofirmaJsUrl)->loadedOnRequest(),
        ], package: 'emiliosh/autofirma-plugin');
    }

    // -------------------------------------------------------------------------
    // Fluent configuration
    // -------------------------------------------------------------------------

    public function autofirmaJsUrl(string $url): static
    {
        $this->autofirmaJsUrl = $url;

        return $this;
    }

    public function getAutofirmaJsUrl(): string
    {
        return $this->autofirmaJsUrl;
    }

    public function useLocalService(bool $condition = true): static
    {
        $this->useLocalService = $condition;

        return $this;
    }

    public function isUsingLocalService(): bool
    {
        return $this->useLocalService;
    }

    public function localServicePort(int $port): static
    {
        $this->localServicePort = $port;

        return $this;
    }

    public function getLocalServicePort(): int
    {
        return $this->localServicePort;
    }

    public function algorithm(string $algorithm): static
    {
        $this->algorithm = $algorithm;

        return $this;
    }

    public function getAlgorithm(): string
    {
        return $this->algorithm;
    }

    /**
     * Set the signature format: XAdES | CAdES | PAdES
     */
    public function signatureFormat(string $format): static
    {
        $this->signatureFormat = $format;

        return $this;
    }

    public function getSignatureFormat(): string
    {
        return $this->signatureFormat;
    }

    public function verifySignature(bool $condition = true): static
    {
        $this->verifySignature = $condition;

        return $this;
    }

    public function shouldVerifySignature(): bool
    {
        return $this->verifySignature;
    }
}
