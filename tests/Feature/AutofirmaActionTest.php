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

    it('ejecuta afterSigned con la firma cuando se completa la firma', function (): void {
        $called    = false;
        $received  = null;

        $action = AutofirmaAction::make('autofirma')
            ->dataToSign('contenido del documento')
            ->afterSigned(function (string $sig, mixed $record) use (&$called, &$received): void {
                $called   = true;
                $received = $sig;
            });

        $signature = base64_encode('firma-resultante');
        $action->invokeAfterSigned($signature);

        expect($called)->toBeTrue();
        expect($received)->toBe($signature);
    });

    it('no falla cuando afterSigned no está configurado', function (): void {
        $action = AutofirmaAction::make('autofirma')
            ->dataToSign('contenido');

        expect(fn () => $action->invokeAfterSigned(base64_encode('firma')))->not->toThrow(\Throwable::class);
    });
});
