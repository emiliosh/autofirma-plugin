<?php

declare(strict_types=1);

use Emiliosh\AutofirmaPlugin\AutofirmaPlugin;

describe('AutofirmaPlugin', function (): void {

    it('tiene el id correcto', function (): void {
        $plugin = AutofirmaPlugin::make();

        expect($plugin->getId())->toBe('autofirma-plugin');
    });

    it('usa SHA512withRSA como algoritmo por defecto', function (): void {
        $plugin = AutofirmaPlugin::make();

        expect($plugin->getAlgorithm())->toBe('SHA512withRSA');
    });

    it('permite configurar el algoritmo de firma', function (): void {
        $plugin = AutofirmaPlugin::make()->algorithm('SHA256withRSA');

        expect($plugin->getAlgorithm())->toBe('SHA256withRSA');
    });

    it('usa XAdES como formato de firma por defecto', function (): void {
        $plugin = AutofirmaPlugin::make();

        expect($plugin->getSignatureFormat())->toBe('XAdES');
    });

    it('no usa el servicio local por defecto', function (): void {
        $plugin = AutofirmaPlugin::make();

        expect($plugin->isUsingLocalService())->toBeFalse();
    });

    it('puede activar el servicio local', function (): void {
        $plugin = AutofirmaPlugin::make()->useLocalService();

        expect($plugin->isUsingLocalService())->toBeTrue();
    });

    it('verifica firmas por defecto', function (): void {
        $plugin = AutofirmaPlugin::make();

        expect($plugin->shouldVerifySignature())->toBeTrue();
    });
});
