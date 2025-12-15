<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRubricRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // La autorización se maneja con middleware role:judge
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'event_id' => 'nullable|exists:events,id',
            'status' => 'required|in:activa,inactiva',
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
            'name.required' => 'El nombre de la rúbrica es obligatorio.',
            'name.max' => 'El nombre no puede superar los 255 caracteres.',
            'event_id.exists' => 'El evento seleccionado no existe.',
            'status.required' => 'El estado de la rúbrica es obligatorio.',
            'status.in' => 'El estado debe ser "activa" o "inactiva".',
        ];
    }
}
