<?php

declare(strict_types=1);

namespace Emiliosh\AutofirmaPlugin\Actions;

use Closure;
use Emiliosh\AutofirmaPlugin\AutofirmaPlugin;
use Emiliosh\AutofirmaPlugin\AutofirmaService;
use Filament\Actions\Action;
use Illuminate\Support\Js;

class AutofirmaAction extends Action
{
    /**
     * Datos a firmar (base64). Puede ser un valor estático o un Closure
     * que recibe el record actual y devuelve el string a firmar.
     */
    protected string|Closure|null $dataToSign = null;

    /**
     * Callback invocado tras una firma exitosa.
     * Recibe (string $signatureB64, mixed $record).
     */
    protected ?Closure $afterSigned = null;

    /**
     * Callback invocado cuando AutoFirma reporta un error.
     * Recibe (string $errorMessage, mixed $record).
     */
    protected ?Closure $onSignError = null;

    // -------------------------------------------------------------------------
    // Bootstrap
    // -------------------------------------------------------------------------

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('autofirma-plugin::autofirma-plugin.action.label'));

        $this->icon('heroicon-o-pencil-square');

        $this->modalHeading(__('autofirma-plugin::autofirma-plugin.modal.heading'));

        $this->modalDescription(__('autofirma-plugin::autofirma-plugin.modal.description'));

        // Alpine llama a $wire.callMountedAction({signature:...}), no hace falta botón de submit.
        $this->modalSubmitAction(false);

        $this->modalContent(function (mixed $record = null): \Illuminate\Contracts\View\View {
            return view('autofirma-plugin::livewire.autofirma-modal', [
                'encodedData'  => $this->getEncodedData($record),
                'pluginConfig' => $this->getAutofirmaConfig(),
            ]);
        });

        // Filament ejecuta este closure cuando Alpine llama a $wire.callMountedAction({signature:...}).
        $this->action(function (array $arguments, mixed $record = null): void {
            $signatureB64 = $arguments['signature'] ?? null;

            if (blank($signatureB64)) {
                $this->failureNotificationTitle(__('autofirma-plugin::autofirma-plugin.notification.no_signature'));
                $this->failure();

                return;
            }

            /** @var AutofirmaService $service */
            $service = app(AutofirmaService::class);

            if (
                AutofirmaPlugin::get()->shouldVerifySignature() &&
                ! $service->verifySignature($signatureB64, $this->resolveDataToSign($record))
            ) {
                $this->failureNotificationTitle(__('autofirma-plugin::autofirma-plugin.notification.invalid_signature'));
                $this->failure();

                return;
            }

            $this->invokeAfterSigned($signatureB64, $record);

            $this->successNotificationTitle(__('autofirma-plugin::autofirma-plugin.notification.signed'));
            $this->success();
        });
    }

    // -------------------------------------------------------------------------
    // Fluent API
    // -------------------------------------------------------------------------

    public function dataToSign(string|Closure $data): static
    {
        $this->dataToSign = $data;

        return $this;
    }

    public function afterSigned(Closure $callback): static
    {
        $this->afterSigned = $callback;

        return $this;
    }

    public function onSignError(Closure $callback): static
    {
        $this->onSignError = $callback;

        return $this;
    }

    // -------------------------------------------------------------------------
    // Invocación de callbacks
    // -------------------------------------------------------------------------

    public function invokeAfterSigned(string $signatureB64, mixed $record = null): void
    {
        if ($this->afterSigned instanceof Closure) {
            ($this->afterSigned)($signatureB64, $record);
        }
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    protected function resolveDataToSign(mixed $record = null): string
    {
        if ($this->dataToSign instanceof Closure) {
            return ($this->dataToSign)($record);
        }

        return $this->dataToSign ?? '';
    }

    /**
     * Devuelve los datos codificados en base64 listos para AutoFirma.
     * Se puede llamar desde la vista/componente Alpine mediante un endpoint o
     * pasándolo como variable Blade.
     */
    public function getEncodedData(mixed $record = null): string
    {
        return base64_encode($this->resolveDataToSign($record));
    }

    /**
     * Configuración del plugin activo para exponer al frontend.
     *
     * @return array<string, mixed>
     */
    public function getAutofirmaConfig(): array
    {
        $plugin = AutofirmaPlugin::get();

        return [
            'algorithm'     => $plugin->getAlgorithm(),
            'format'        => $plugin->getSignatureFormat(),
            'localService'  => $plugin->isUsingLocalService(),
            'localPort'     => $plugin->getLocalServicePort(),
        ];
    }
}
