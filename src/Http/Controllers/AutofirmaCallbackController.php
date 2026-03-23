<?php

declare(strict_types=1);

namespace Emiliosh\AutofirmaPlugin\Http\Controllers;

use Emiliosh\AutofirmaPlugin\AutofirmaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Controlador opcional para flujos en los que AutoFirma realiza
 * un callback HTTP al servidor (en lugar de devolver la firma al frontend).
 *
 * Regístrase automáticamente en routes/web.php y puede deshabilitarse
 * desde la configuración del plugin.
 */
class AutofirmaCallbackController extends Controller
{
    public function __construct(
        private readonly AutofirmaService $service,
    ) {}

    /**
     * Recibe la firma generada por AutoFirma.
     *
     * POST /autofirma/callback
     *
     * Body esperado (JSON):
     *   {
     *     "signature": "<base64>",
     *     "transaction_id": "<uuid>"
     *   }
     */
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'signature'      => ['required', 'string'],
            'transaction_id' => ['required', 'string', 'uuid'],
        ]);

        // TODO: recuperar el documento original asociado al transaction_id,
        //       verificar la firma y persistir el resultado.

        $valid = $this->service->verifySignature(
            $validated['signature'],
            originalData: '', // reemplazar por el documento original
        );

        if (! $valid) {
            return response()->json(
                ['message' => __('autofirma-plugin::autofirma-plugin.callback.invalid')],
                422,
            );
        }

        return response()->json([
            'message' => __('autofirma-plugin::autofirma-plugin.callback.success'),
        ]);
    }
}
