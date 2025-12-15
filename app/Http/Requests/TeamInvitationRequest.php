<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeamInvitationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // La autorización se maneja con middleware role:student
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email:rfc,dns', 'max:255'],
            'role' => ['required', 'in:Back,Front,Diseñador'],
            'team_id' => ['required', 'exists:teams,id'],
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
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ingresar un correo electrónico válido.',
            'email.max' => 'El correo electrónico no puede superar los 255 caracteres.',
            'role.required' => 'Debes seleccionar un rol.',
            'role.in' => 'El rol seleccionado no es válido. Debe ser Back, Front o Diseñador.',
            'team_id.required' => 'El equipo es obligatorio.',
            'team_id.exists' => 'El equipo seleccionado no existe.',
        ];
    }
}
