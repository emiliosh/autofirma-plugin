<?php

declare(strict_types=1);

namespace Emiliosh\AutofirmaPlugin\Livewire;

use Emiliosh\AutofirmaPlugin\AutofirmaPlugin;
use Livewire\Attributes\On;
use Livewire\Component;

class AutofirmaModal extends Component
{
    /** Datos a firmar codificados en base64, inyectados desde la Action. */
    public string $encodedData = '';

    /** Firma recibida de AutoFirma (base64). */
    public string $signature = '';

    /** Estado del proceso: idle | loading | signed | error */
    public string $status = 'idle';

    /** Mensaje de error de AutoFirma, si lo hay. */
    public string $errorMessage = '';

    // -------------------------------------------------------------------------
    // Livewire lifecycle
    // -------------------------------------------------------------------------

    public function mount(string $encodedData = ''): void
    {
        $this->encodedData = $encodedData;
    }

    // -------------------------------------------------------------------------
    // Eventos desde Alpine / AutoFirma JS
    // -------------------------------------------------------------------------

    /**
     * Llamado por Alpine cuando AutoFirma devuelve la firma correctamente.
     */
    #[On('autofirma:signed')]
    public function onSigned(string $signature): void
    {
        $this->signature = $signature;
        $this->status    = 'signed';

        // Propagamos la firma al componente padre (Action) para que la procese.
        $this->dispatch('autofirma-plugin:signature-ready', signature: $signature);
    }

    /**
     * Llamado por Alpine cuando AutoFirma reporta un error.
     */
    #[On('autofirma:error')]
    public function onError(string $message): void
    {
        $this->errorMessage = $message;
        $this->status       = 'error';
    }

    // -------------------------------------------------------------------------
    // Helpers para la vista
    // -------------------------------------------------------------------------

    public function getPluginConfig(): array
    {
        $plugin = AutofirmaPlugin::get();

        return [
            'algorithm'    => $plugin->getAlgorithm(),
            'format'       => $plugin->getSignatureFormat(),
            'localService' => $plugin->isUsingLocalService(),
            'localPort'    => $plugin->getLocalServicePort(),
        ];
    }

    // -------------------------------------------------------------------------
    // Render
    // -------------------------------------------------------------------------

    public function render(): \Illuminate\View\View
    {
        return view('autofirma-plugin::livewire.autofirma-modal', [
            'pluginConfig' => $this->getPluginConfig(),
        ]);
    }
}
