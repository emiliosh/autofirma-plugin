<?php

declare(strict_types=1);

return [

    'action' => [
        'label' => 'Sign with AutoFirma',
    ],

    'modal' => [
        'heading'     => 'Electronic Signature',
        'description' => 'The AutoFirma application installed on your computer will be used to sign the document.',
        'submit'      => 'Confirm signature',
        'ready'       => 'Click the button below to start the signing process with AutoFirma.',
        'sign_button' => 'Start signing',
        'waiting'     => 'Waiting for AutoFirma response…',
        'signed'      => 'Document signed successfully.',
        'retry'       => 'Retry',
    ],

    'notification' => [
        'signed'            => 'Document signed successfully.',
        'no_signature'      => 'No signature was received from AutoFirma.',
        'invalid_signature' => 'The received signature is not valid.',
    ],

    'callback' => [
        'success' => 'Signature received and verified successfully.',
        'invalid' => 'The received signature is not valid.',
    ],

    'js' => [
        'no_data'       => 'No data to sign.',
        'not_installed' => 'AutoFirma is not installed or not available on this computer. Please download it from the Spanish Government portal.',
    ],

];
