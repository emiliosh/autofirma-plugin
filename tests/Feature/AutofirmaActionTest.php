<?php

declare(strict_types=1);

use Emiliosh\AutofirmaPlugin\Actions\AutofirmaAction;

describe('AutofirmaAction', function (): void {

    it('puede instanciarse', function (): void {
        $action = AutofirmaAction::make('autofirma');

        expect($action)->toBeInstanceOf(AutofirmaAction::class);
    });

    it('acepta datos a firmar como string', function (): void {
        $action = AutofirmaAction::make('autofirma')
            ->dataToSign('contenido del documento');

        expect($action->getEncodedData())->toBe(base64_encode('contenido del documento'));
    });

    it('acepta datos a firmar como closure', function (): void {
        $action = AutofirmaAction::make('autofirma')
            ->dataToSign(fn () => 'contenido dinámico');

        expect($action->getEncodedData())->toBe(base64_encode('contenido dinámico'));
    });

    it('devuelve la configuración del plugin correctamente', function (): void {
        $action = AutofirmaAction::make('autofirma');

        // Este test requiere un panel Filament registrado; se completará
        // en la integración con TestPanel.
    })->todo();
});
