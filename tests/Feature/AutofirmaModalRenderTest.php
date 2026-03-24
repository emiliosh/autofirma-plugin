<?php

declare(strict_types=1);

use Emiliosh\AutofirmaPlugin\Livewire\AutofirmaModal;
use Livewire\Livewire;

// ---------------------------------------------------------------------------
// Bug reproduction: AutofirmaAction pasa la vista sin variables
// ---------------------------------------------------------------------------

it('throws ErrorException when the autofirma-modal view renders without $encodedData', function (): void {
    // Esto reproduce exactamente lo que hace AutofirmaAction::setUp():
    //   $this->modalContent(view('autofirma-plugin::livewire.autofirma-modal'))
    // La vista usa @js($encodedData) en la línea 2, pero la variable no existe.
    $view = view('autofirma-plugin::livewire.autofirma-modal');

    expect(fn () => $view->render())
        ->toThrow(ErrorException::class);
})->group('bug');

// ---------------------------------------------------------------------------
// Verificación de la corrección: la vista debe recibir las variables
// ---------------------------------------------------------------------------

it('renders the autofirma-modal view successfully when all required variables are provided', function (): void {
    $view = view('autofirma-plugin::livewire.autofirma-modal', [
        'encodedData'  => base64_encode('contenido-pdf-de-prueba'),
        'pluginConfig' => [
            'algorithm'    => 'SHA512withRSA',
            'format'       => 'XAdES',
            'localService' => false,
            'localPort'    => null,
        ],
    ]);

    expect(fn () => $view->render())->not->toThrow(ErrorException::class);
    expect($view->render())->toContain('autofirmaModal');
})->group('fix');

// ---------------------------------------------------------------------------
// Componente Livewire: ya pasa correctamente las variables en render()
// ---------------------------------------------------------------------------

it('AutofirmaModal Livewire component initialises with the provided encodedData', function (): void {
    $encodedData = base64_encode('contenido-pdf-de-prueba');

    Livewire::test(AutofirmaModal::class, ['encodedData' => $encodedData])
        ->assertSet('encodedData', $encodedData)
        ->assertSet('status', 'idle')
        ->assertSet('signature', '');
})->group('livewire');

it('AutofirmaModal updates status to signed when autofirma:signed event is dispatched', function (): void {
    $signature = base64_encode('firma-resultante');

    Livewire::test(AutofirmaModal::class, ['encodedData' => base64_encode('pdf')])
        ->dispatch('autofirma:signed', $signature)
        ->assertSet('status', 'signed')
        ->assertSet('signature', $signature)
        ->assertDispatched('autofirma-plugin:signature-ready');
})->group('livewire');

it('AutofirmaModal updates status to error when autofirma:error event is dispatched', function (): void {
    Livewire::test(AutofirmaModal::class, ['encodedData' => base64_encode('pdf')])
        ->dispatch('autofirma:error', 'AUTOFIRMA_CANCELLED')
        ->assertSet('status', 'error')
        ->assertSet('errorMessage', 'AUTOFIRMA_CANCELLED');
})->group('livewire');
