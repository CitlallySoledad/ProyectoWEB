<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeamRequest extends FormRequest
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
            'team_name' => ['required', 'string', 'min:3', 'max:100', 'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s]+$/'],
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
            'team_name.required' => 'El nombre del equipo es obligatorio.',
            'team_name.min' => 'El nombre del equipo debe tener al menos 3 caracteres.',
            'team_name.max' => 'El nombre del equipo no puede superar los 100 caracteres.',
            'team_name.regex' => 'El nombre del equipo solo puede contener letras, números y espacios.',
        ];
    }
}
