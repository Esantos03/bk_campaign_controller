<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ActividadesPendientesRequest;
use App\Services\ConfirmacionService;
use Illuminate\Http\JsonResponse;

class ClienteController extends Controller
{
    public function __construct(
        private ConfirmacionService $confirmacionService
    ) {}

    public function actividadesPendientes(
        ActividadesPendientesRequest $request,
        string $telefono
    ): JsonResponse {
        try {
            $data = $this->confirmacionService->obtenerActividadesPendientes($telefono);
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }
}
