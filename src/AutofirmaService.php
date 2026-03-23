<?php

declare(strict_types=1);

namespace Emiliosh\AutofirmaPlugin;

class AutofirmaService
{
    public function __construct(
        private readonly array $config = [],
    ) {}

    /**
     * Codifica los datos en base64 para enviarlos a AutoFirma.
     */
    public function prepareData(string $data): string
    {
        return base64_encode($data);
    }

    /**
     * Decodifica los datos firmados recibidos desde AutoFirma.
     */
    public function decodeSignature(string $signatureB64): string
    {
        return base64_decode($signatureB64, strict: true) ?: '';
    }

    /**
     * Verifica la firma recibida de AutoFirma.
     *
     * TODO: Implementar la verificación real mediante OpenSSL
     *       o llamada al servicio @firma del MPTFP.
     */
    public function verifySignature(string $signatureB64, string $originalData): bool
    {
        // Placeholder: implementar según el formato de firma (XAdES/CAdES/PAdES)
        return true;
    }

    /**
     * Construye la cadena de parámetros para la llamada a AutoScript.firma().
     *
     * @param  array<string, string>  $options
     */
    public function buildParams(array $options = []): string
    {
        $defaults = [
            'headless' => 'false',
        ];

        return http_build_query(array_merge($defaults, $options));
    }

    /**
     * Devuelve la configuración activa del plugin.
     */
    public function getConfig(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $this->config;
        }

        return data_get($this->config, $key, $default);
    }
}
