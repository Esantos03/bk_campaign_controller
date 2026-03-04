<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmarActividadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => [
                'required',
                'uuid',
                'exists:actividad_cliente,token_unico',
            ],
            'accion' => [
                'required',
                'in:confirmar,rechazar',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'token.required' => 'El token es requerido',
            'token.uuid' => 'El token debe ser un UUID válido',
            'token.exists' => 'El token no es válido o ha expirado',
            'accion.required' => 'La acción es requerida',
            'accion.in' => 'La acción debe ser "confirmar" o "rechazar"',
        ];
    }
}
