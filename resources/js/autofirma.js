/**
 * autofirma.js
 *
 * Componente Alpine.js que gestiona la integración con AutoFirma.
 *
 * Flujo:
 *   1. sign()      → llama a AutoScript.firma() con los datos y la config del plugin.
 *   2. onSuccess() → actualiza el estado y llama a $wire.callMountedAction({signature})
 *                    para que Filament ejecute el closure action() de AutofirmaAction.
 *   3. onError()   → actualiza el estado de error sin necesidad de notificar a Livewire.
 */

document.addEventListener('alpine:init', () => {
    Alpine.data('autofirmaModal', (encodedData, config) => ({

        status: 'idle',       // idle | loading | signed | error
        errorMessage: '',
        signature: '',

        encodedData: encodedData,
        config: config,

        // ------------------------------------------------------------------
        // Firma
        // ------------------------------------------------------------------

        sign() {
            if (!this.encodedData) {
                this.setError('{{ __("autofirma-plugin::autofirma-plugin.js.no_data") }}');
                return;
            }

            if (typeof AutoScript === 'undefined') {
                this.setError('{{ __("autofirma-plugin::autofirma-plugin.js.not_installed") }}');
                return;
            }

            this.status = 'loading';

            try {
                AutoScript.cargarAppAfirma();

                AutoScript.sign(
                    this.encodedData,               // Datos a firmar (base64)
                    this.config.algorithm,          // Algoritmo
                    this.config.format,             // Formato (XAdES, CAdES, PAdES)
                    this.buildParams(),             // Parámetros adicionales
                    (signatureB64) => this.onSuccess(signatureB64),
                    (errorCode, errorMessage) => this.onError(errorCode, errorMessage),
                );
            } catch (e) {
                this.setError(e.message ?? 'Error desconocido al llamar a AutoFirma.');
            }
        },

        // ------------------------------------------------------------------
        // Callbacks de AutoFirma
        // ------------------------------------------------------------------

        onSuccess(signatureB64) {
            this.signature = signatureB64;
            this.status    = 'signed';

            // Llama al action closure de AutofirmaAction con la firma como argumento.
            // $wire aquí apunta al componente Filament (page), no a un Livewire anidado.
            $wire.callMountedAction({ signature: signatureB64 });
        },

        onError(errorCode, errorMessage) {
            const message = errorMessage
                ? `[${errorCode}] ${errorMessage}`
                : `Error de AutoFirma (código ${errorCode})`;

            this.setError(message);
        },

        setError(message) {
            this.errorMessage = message;
            this.status       = 'error';
        },

        // ------------------------------------------------------------------
        // Helpers
        // ------------------------------------------------------------------

        buildParams() {
            const parts = ['headless=false'];

            if (this.config.localService) {
                parts.push(`localPort=${this.config.localPort}`);
            }

            return parts.join('\n');
        },
    }));
});
