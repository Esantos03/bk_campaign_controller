<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ActividadesPendientesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'telefono' => [
                'required',
                'string',
                'regex:/^[0-9]{10,15}$/',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'telefono.required' => 'El teléfono es requerido',
            'telefono.regex' => 'El formato del teléfono es inválido. Debe contener entre 10 y 15 dígitos',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'telefono' => $this->route('telefono'),
        ]);
    }
}
