<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminTeamRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // La autorización se maneja con middleware role:admin
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:255'],
            'leader_id'    => ['required', 'exists:users,id'],
            'backend_id'   => ['nullable', 'exists:users,id', 'different:leader_id'],
            'frontend_id'  => ['nullable', 'exists:users,id', 'different:leader_id', 'different:backend_id'],
            'designer_id'  => ['nullable', 'exists:users,id', 'different:leader_id', 'different:backend_id', 'different:frontend_id'],
        ];
    }

    /**
     * Get the custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del equipo es obligatorio.',
            'name.max' => 'El nombre no puede superar los 255 caracteres.',
            'leader_id.required' => 'Debe seleccionar un líder del equipo.',
            'leader_id.exists' => 'El líder seleccionado no existe.',
            'backend_id.exists' => 'El desarrollador backend seleccionado no existe.',
            'backend_id.different' => 'El desarrollador backend no puede ser el mismo que el líder.',
            'frontend_id.exists' => 'El desarrollador frontend seleccionado no existe.',
            'frontend_id.different' => 'El desarrollador frontend debe ser diferente al líder y al backend.',
            'designer_id.exists' => 'El diseñador seleccionado no existe.',
            'designer_id.different' => 'El diseñador debe ser diferente a los demás miembros.',
        ];
    }
}
