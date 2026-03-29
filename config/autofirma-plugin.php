<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | URL de AutoScript.js
    |--------------------------------------------------------------------------
    | URL pública desde la que se carga el script de AutoFirma.
    | Por defecto apunta al CDN de REDSARA del Gobierno de España.
    */
    'autofirma_js_url' => env(
        'AUTOFIRMA_JS_URL',
        'https://estaticos.redsara.es/comunes/autofirma/currentversion/AutoScript.js',
    ),

    /*
    |--------------------------------------------------------------------------
    | Algoritmo de firma
    |--------------------------------------------------------------------------
    | Algoritmo criptográfico empleado. Valores habituales:
    |   SHA1withRSA | SHA256withRSA | SHA384withRSA | SHA512withRSA
    */
    'algorithm' => env('AUTOFIRMA_ALGORITHM', 'SHA512withRSA'),

    /*
    |--------------------------------------------------------------------------
    | Formato de firma
    |--------------------------------------------------------------------------
    | Formato del sobre de firma generado:
    |   XAdES  → documentos XML / uso general
    |   CAdES  → binarios / CMS
    |   PAdES  → documentos PDF
    */
    'signature_format' => env('AUTOFIRMA_FORMAT', 'PAdES'),

    /*
    |--------------------------------------------------------------------------
    | Servicio local de AutoFirma
    |--------------------------------------------------------------------------
    | En entornos donde AutoFirma expone un servicio HTTP local (puerto 51234)
    | en lugar de usar el protocolo afirma://, activa esta opción.
    */
    'use_local_service' => env('AUTOFIRMA_LOCAL_SERVICE', false),

    'local_service_port' => env('AUTOFIRMA_LOCAL_PORT', 51234),

    /*
    |--------------------------------------------------------------------------
    | Verificación de firmas en servidor
    |--------------------------------------------------------------------------
    | Si está habilitado, el servidor verificará la firma recibida antes de
    | aceptarla como válida. Requiere implementar AutofirmaService::verifySignature().
    */
    'verify_signature' => env('AUTOFIRMA_VERIFY', true),

    /*
    |--------------------------------------------------------------------------
    | Middleware para la ruta de callback
    |--------------------------------------------------------------------------
    */
    'callback_middleware' => ['web', 'auth'],

];
