<?php

namespace App\Http\Controllers\Judge;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\EvaluationEvidence;
use App\Models\Project;
use App\Models\RubricCriterion;
use App\Models\Rubric;
use App\Models\EvaluationScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EvaluationController extends Controller
{
    public function index()
    {
        $judge = Auth::user();
        $evaluations = Evaluation::where('judge_id', $judge->id)
            ->with(['project.team', 'rubric'])
            ->latest('evaluated_at')
            ->get();

        return view('judge.evaluations.index', compact('evaluations'));
    }

    public function show(Project $project)
    {
        $judgeId = Auth::id();

        // Cargar documentos del proyecto
        $project->load('documents');

        // Buscar si ya existe evaluación para este juez y proyecto
        $evaluation = Evaluation::where('project_id', $project->id)
            ->where('judge_id', $judgeId)
            ->first();

        // Si ya está completada, redirigir a lista
        if ($evaluation && $evaluation->status === 'completada') {
            return redirect()->route('judge.evaluations.index')
                ->with('info', 'Esta evaluación ya fue completada.');
        }

        // Determinar la rúbrica a usar (puede venir del proyecto o de la evaluación existente)
        $rubricId = $evaluation->rubric_id ?? $project->rubric_id;

        if (!$rubricId) {
            return redirect()->route('judge.projects.index')
                ->with('error', 'Este proyecto no tiene una rúbrica asignada.');
        }

        $criteria = RubricCriterion::where('rubric_id', $rubricId)->get();

        // Cargar la rúbrica (para mostrar pesos/min/max en la vista)
        $rubric = Rubric::find($rubricId);

        // Si ya existe evaluación, traer puntajes existentes para prefilar
        $existingScores = collect();
        if ($evaluation) {
            $evaluation->load('scores');
            $existingScores = $evaluation->scores->keyBy('rubric_criterion_id');
        }

        // No creamos la evaluación aquí: la crearemos cuando el juez guarde (reduce filas "pendientes" innecesarias)
        return view('judge.evaluations.show', compact('evaluation', 'criteria', 'project', 'rubric', 'existingScores'));
    }

    public function store(Request $request, Project $project)
    {
        // Crear la evaluación al momento de guardar si no existe.
        $evaluation = Evaluation::firstOrCreate(
            [
                'project_id' => $project->id,
                'judge_id' => Auth::id(),
            ],
            [
                'project_name' => $project->name,
                'rubric_id' => $project->rubric_id ?? null,
                'status' => 'pendiente',
                'creativity' => 0,
                'functionality' => 0,
                'innovation' => 0,
            ]
        );

        return $this->storeScores($request, $evaluation);
    }

    public function storeScores(Request $request, Evaluation $evaluation)
    {
        $request->validate([
            'scores.*.criterion_id' => 'required|exists:rubric_criteria,id',
            'scores.*.score' => 'required|integer|min:0|max:10',
            'scores.*.comment' => 'nullable|string',
            'evidence_files.*' => 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx',
            'evidence_descriptions.*' => 'nullable|string|max:500',
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

        if ($request->hasFile('evidence_files')) {
            foreach ($request->file('evidence_files') as $index => $file) {
                if ($file) {
                    $fileName = $file->getClientOriginalName();
                    $mimeType = $file->getMimeType();
                    $fileSize = $file->getSize();
                    
                    $path = $file->store("evaluations/{$evaluation->id}", 'public');
                    
                    $description = null;
                    if ($request->evidence_descriptions && isset($request->evidence_descriptions[$index])) {
                        $description = $request->evidence_descriptions[$index];
                    }
                    
                    EvaluationEvidence::create([
                        'evaluation_id' => $evaluation->id,
                        'file_name' => $fileName,
                        'file_path' => $path,
                        'mime_type' => $mimeType,
                        'file_size' => $fileSize,
                        'description' => $description,
                    ]);
                }
            }
        }

        $final = EvaluationScore::where('evaluation_id', $evaluation->id)->avg('score');

        $evaluation->update([
            'final_score' => $final,
            'status' => 'completada',
            'evaluated_at' => now(),
        ]);

        return redirect()->route('judge.projects.index')
            ->with('success', 'Evaluación guardada correctamente. Evidencias subidas.');
    }

    public function exportPdf(Evaluation $evaluation)
    {
        if ($evaluation->judge_id !== Auth::id()) {
            return redirect()->route('judge.evaluations.index')
                ->with('error', 'No tienes permiso para descargar esta evaluación.');
        }

        $evaluation->load(['project.team', 'rubric.criteria', 'scores', 'evidences']);

        $pdf = \PDF::loadView('judge.evaluations.pdf', compact('evaluation'));
        
        return $pdf->download("evaluacion_" . $evaluation->project->id . "_" . now()->format('Y-m-d_H-i') . ".pdf");
    }
}
