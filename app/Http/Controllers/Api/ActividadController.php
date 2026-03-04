<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ConfirmarActividadRequest;
use App\Services\ConfirmacionService;
use Illuminate\Http\JsonResponse;

class ActividadController extends Controller
{
    public function __construct(
        private ConfirmacionService $confirmacionService
    ) {}

    public function confirmar(ConfirmarActividadRequest $request): JsonResponse
    {
        try {
            $confirmar = $request->input('accion') === 'confirmar';
            $data = $this->confirmacionService->confirmar(
                $request->input('token'),
                $confirmar
            );
            
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
