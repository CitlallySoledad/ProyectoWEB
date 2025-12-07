<?php

namespace App\Http\Controllers\Judge;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\RubricCriterion;
use App\Models\EvaluationScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
{
    /**
     * Mostrar todas las evaluaciones del juez autenticado.
     */
    public function index()
    {
        $judge = Auth::user();

        $evaluations = Evaluation::where('judge_id', $judge->id)
            ->with(['project.team', 'rubric'])
            ->latest('evaluated_at')
            ->get();

        return view('judge.evaluations.index', compact('evaluations'));
    }

    /**
     * Mostrar una evaluación específica con sus criterios.
     */
    public function show(Evaluation $evaluation)
    {
        $criteria = RubricCriterion::where('rubric_id', $evaluation->rubric_id)->get();

        return view('judge.evaluations.show', compact('evaluation', 'criteria'));
    }

    /**
     * Guardar los puntajes de los criterios evaluados.
     */
    public function storeScores(Request $request, Evaluation $evaluation)
    {
        $request->validate([
            'scores.*.criterion_id' => 'required|exists:rubric_criteria,id',
            'scores.*.score' => 'required|integer|min:0|max:10',
            'scores.*.comment' => 'nullable|string',
        ]);

        foreach ($request->scores as $scoreData) {
            EvaluationScore::updateOrCreate(
                [
                    'evaluation_id' => $evaluation->id,
                    'rubric_criterion_id' => $scoreData['criterion_id'],
                ],
                [
                    'score' => $scoreData['score'],
                    'comment' => $scoreData['comment'] ?? null,
                ]
            );
        }

        // Calcular puntaje final promedio
        $final = EvaluationScore::where('evaluation_id', $evaluation->id)->avg('score');

        $evaluation->update([
            'final_score' => $final,
            'status' => 'completada',
            'evaluated_at' => now(),
        ]);

        return redirect()->route('judge.projects.index')
            ->with('success', 'Evaluación guardada correctamente.');
    }
}
