<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEvaluationScoresRequest extends FormRequest
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
            'scores.*.criterion_id' => 'required|exists:rubric_criteria,id',
            'scores.*.score' => 'required|integer|min:0|max:10',
            'scores.*.comment' => 'nullable|string',
            'evidence_files.*' => 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx',
            'evidence_descriptions.*' => 'nullable|string|max:500',
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
            'scores.*.criterion_id.required' => 'Todos los criterios deben tener un ID válido.',
            'scores.*.criterion_id.exists' => 'Uno o más criterios no existen.',
            'scores.*.score.required' => 'Todos los criterios deben tener una calificación.',
            'scores.*.score.integer' => 'Las calificaciones deben ser números enteros.',
            'scores.*.score.min' => 'La calificación mínima es 0.',
            'scores.*.score.max' => 'La calificación máxima es 10.',
            'evidence_files.*.max' => 'Los archivos de evidencia no pueden superar los 10MB.',
            'evidence_files.*.mimes' => 'Los archivos deben ser PDF, JPG, JPEG, PNG, DOC o DOCX.',
            'evidence_descriptions.*.max' => 'Las descripciones no pueden superar los 500 caracteres.',
        ];
    }
}
