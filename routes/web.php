<?php

declare(strict_types=1);

use Emiliosh\AutofirmaPlugin\Http\Controllers\AutofirmaCallbackController;
use Illuminate\Support\Facades\Route;

Route::middleware(config('autofirma-plugin.callback_middleware', ['web', 'auth']))
    ->prefix('autofirma')
    ->name('autofirma.')
    ->group(function (): void {

        /**
         * POST /autofirma/callback
         *
         * Endpoint opcional para flujos en los que AutoFirma devuelve
         * la firma firmada directamente al servidor (modo callback HTTP).
         */
        Route::post('callback', AutofirmaCallbackController::class)
            ->name('callback');
    });
