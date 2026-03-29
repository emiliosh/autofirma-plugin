<?php

declare(strict_types=1);

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
