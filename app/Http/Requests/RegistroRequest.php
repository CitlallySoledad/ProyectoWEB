<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistroRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Cualquiera puede registrarse
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'control'    => ['required', 'string', 'regex:/^[A-Za-z0-9]+$/'],
            'nombre'     => ['required', 'string', 'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/'],
            'ap_paterno' => ['required', 'string', 'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/'],
            'ap_materno' => ['required', 'string', 'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/'],
            'email'      => ['required', 'email:rfc,dns', 'unique:users,email'],
            'password'   => ['required', 'confirmed', 'min:6', 'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]+$/'],
            'telefono'   => ['required', 'regex:/^[0-9]+$/'],
            'carrera'    => ['required', 'in:Contador Público,Licenciatura en Administración,Ingeniería Química,Ingeniería Mecánica,Ingeniería Industrial,Ingeniería en Sistemas Computacionales,Ingeniería en Gestión Empresarial,Ingeniería Electrónica,Ingeniería Eléctrica,Ingeniería Civil'],
            'role'       => ['required', 'in:student'],
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
            'control.required' => 'El número de control es obligatorio.',
            'control.regex' => 'El número de control solo puede contener letras y números.',
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras.',
            'ap_paterno.required' => 'El apellido paterno es obligatorio.',
            'ap_paterno.regex' => 'El apellido paterno solo puede contener letras.',
            'ap_materno.required' => 'El apellido materno es obligatorio.',
            'ap_materno.regex' => 'El apellido materno solo puede contener letras.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ingresar un correo electrónico válido que contenga @.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.regex' => 'La contraseña debe contener letras y números.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.regex' => 'El teléfono solo puede contener números.',
            'carrera.required' => 'Debe seleccionar una carrera.',
            'carrera.in' => 'La carrera seleccionada no es válida.',
            'role.required' => 'Debe seleccionar un rol.',
        ];
    }
}
