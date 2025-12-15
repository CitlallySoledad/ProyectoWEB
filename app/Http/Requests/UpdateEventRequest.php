<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
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
            'judge_ids'    => ['required', 'array', 'min:1'],
            'judge_ids.*'  => ['exists:users,id'],
            'rubric_ids'   => ['required', 'array', 'min:1'],
            'rubric_ids.*' => ['exists:rubrics,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'El título es obligatorio.',
            'description.required' => 'La descripción es obligatoria.',
            'place.required' => 'El lugar es obligatorio.',
            'capacity.required' => 'La capacidad es obligatoria.',
            'capacity.min' => 'La capacidad debe ser al menos 1.',
            'start_date.required' => 'La fecha de inicio es obligatoria.',
            'end_date.required' => 'La fecha de fin es obligatoria.',
            'end_date.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
            'status.required' => 'El estado es obligatorio.',
            'category.required' => 'La categoría es obligatoria.',
            'judge_ids.required' => 'Debes asignar al menos un juez.',
            'judge_ids.min' => 'Debes asignar al menos un juez.',
            'rubric_ids.required' => 'Debes asignar al menos una rúbrica.',
            'rubric_ids.min' => 'Debes asignar al menos una rúbrica.',
        ];
    }
}
