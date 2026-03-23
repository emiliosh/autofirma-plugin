/**
 * autofirma.js
 *
 * Componente Alpine.js que gestiona la integración con AutoFirma.
 *
 * Flujo:
 *   1. init()  → comprueba si AutoScript está disponible en la página.
 *   2. sign()  → llama a AutoScript.firma() con los datos y la config del plugin.
 *   3. Los callbacks de AutoFirma actualizan el estado y disparan eventos Livewire.
 */

document.addEventListener('alpine:init', () => {
    Alpine.data('autofirmaModal', (encodedData, config) => ({

        status: 'idle',       // idle | loading | signed | error
        errorMessage: '',
        signature: '',

        encodedData: encodedData,
        config: config,

        // ------------------------------------------------------------------
        // Lifecycle
        // ------------------------------------------------------------------

        init() {
            // Escucha el evento emitido por Livewire cuando la firma ya fue procesada
            this.$wire.on('autofirma-plugin:signature-ready', () => {
                // La firma está lista; el modal puede cerrarse automáticamente
                // si así lo configura la Action.
            });
        },

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

                AutoScript.firma(
                    this.config.algorithm,          // Algoritmo
                    this.config.format,             // Formato (XAdES, CAdES, PAdES)
                    'base64',                       // Tipo de datos de entrada
                    this.encodedData,               // Datos a firmar (base64)
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

            // Envía la firma al componente Livewire
            this.$dispatch('autofirma:signed', signatureB64);
            this.$wire.dispatch('autofirma:signed', { signature: signatureB64 });
        },

        onError(errorCode, errorMessage) {
            const message = errorMessage
                ? `[${errorCode}] ${errorMessage}`
                : `Error de AutoFirma (código ${errorCode})`;

            this.setError(message);
            this.$wire.dispatch('autofirma:error', { message });
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
