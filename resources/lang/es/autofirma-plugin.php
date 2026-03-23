<?php

declare(strict_types=1);

return [

    'action' => [
        'label' => 'Firmar con AutoFirma',
    ],

    'modal' => [
        'heading'     => 'Firma electrónica',
        'description' => 'Se utilizará la aplicación AutoFirma instalada en su equipo para realizar la firma electrónica.',
        'submit'      => 'Confirmar firma',
        'ready'       => 'Pulse el botón para iniciar el proceso de firma con AutoFirma.',
        'sign_button' => 'Iniciar firma',
        'waiting'     => 'Esperando respuesta de AutoFirma...',
        'signed'      => 'Documento firmado correctamente.',
        'retry'       => 'Reintentar',
    ],

    'notification' => [
        'signed'          => 'Documento firmado correctamente.',
        'no_signature'    => 'No se recibió ninguna firma de AutoFirma.',
        'invalid_signature' => 'La firma recibida no es válida.',
    ],

    'callback' => [
        'success' => 'Firma recibida y verificada correctamente.',
        'invalid' => 'La firma recibida no es válida.',
    ],

    'js' => [
        'no_data'       => 'No hay datos para firmar.',
        'not_installed' => 'AutoFirma no está instalado o no está disponible en este equipo. Por favor, descárguelo desde el portal del Gobierno de España.',
    ],

];
