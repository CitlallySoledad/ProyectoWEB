<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // El usuario autenticado puede actualizar su propio perfil
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'             => ['required', 'string', 'min:3', 'max:255', 'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/'],
            'email'            => ['required', 'email:rfc,dns', 'max:255'],
            'curp'             => ['nullable', 'string', 'size:18', 'regex:/^[A-Z]{4}[0-9]{6}[HM][A-Z]{5}[0-9A-Z][0-9]$/'],
            'fecha_nacimiento' => ['nullable', 'date', 'before:-15 years'],
            'genero'           => ['nullable', 'string', 'in:Masculino,Femenino,Otro'],
            'estado_civil'     => ['nullable', 'string', 'in:Soltero/a,Casado/a,Divorciado/a,Viudo/a,Unión libre'],
            'telefono'         => ['nullable', 'string', 'size:10', 'regex:/^[0-9]{10}$/'],
            'profesion'        => ['nullable', 'string', 'max:255'],
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
            'name.required' => 'El nombre es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'name.max' => 'El nombre no puede superar los 255 caracteres.',
            'name.regex' => 'El nombre solo puede contener letras y espacios.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ingresar un correo electrónico válido.',
            'email.max' => 'El correo electrónico no puede superar los 255 caracteres.',
            'curp.size' => 'El CURP debe tener exactamente 18 caracteres.',
            'curp.regex' => 'El formato del CURP no es válido. Ejemplo: AAAA000000HDFRRR00',
            'fecha_nacimiento.date' => 'La fecha de nacimiento debe ser una fecha válida.',
            'fecha_nacimiento.before' => 'Debes tener al menos 15 años para registrarte.',
            'genero.in' => 'El género seleccionado no es válido.',
            'estado_civil.in' => 'El estado civil seleccionado no es válido.',
            'telefono.size' => 'El teléfono debe tener exactamente 10 dígitos.',
            'telefono.regex' => 'El teléfono solo puede contener números.',
            'profesion.max' => 'La profesión no puede superar los 255 caracteres.',
        ];
    }
}
