<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
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
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['required', 'string'],
            'place'        => ['required', 'string', 'max:255'],
            'capacity'     => ['required', 'integer', 'min:1'],
            'start_date'   => ['required', 'date'],
            'end_date'     => ['required', 'date', 'after_or_equal:start_date'],
            'status'       => ['required', 'string', 'max:50'],
            'category'     => ['required', 'string', 'max:255'],
            'judge_ids'    => ['nullable', 'array'],
            'judge_ids.*'  => ['exists:users,id'],
            'rubric_ids'   => ['nullable', 'array'],
            'rubric_ids.*' => ['exists:rubrics,id'],
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
            'title.required' => 'El título del evento es obligatorio.',
            'description.required' => 'La descripción del evento es obligatoria.',
            'place.required' => 'El lugar del evento es obligatorio.',
            'capacity.required' => 'La capacidad del evento es obligatoria.',
            'start_date.required' => 'La fecha de inicio es obligatoria.',
            'end_date.required' => 'La fecha de fin es obligatoria.',
            'status.required' => 'El estado del evento es obligatorio.',
            'category.required' => 'La categoría del evento es obligatoria.',
            'end_date.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
        ];
    }
}
