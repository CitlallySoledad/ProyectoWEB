<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminEvaluationController extends Controller
{
    // Lista de proyectos demo (si aún no hay datos en BD)
    private array $projectsDemo = [
        ['name' => 'Proyecto A', 'creativity' => 7, 'functionality' => 10, 'innovation' => 10],
        ['name' => 'Proyecto B', 'creativity' => 8, 'functionality' => 10, 'innovation' => 10],
        ['name' => 'Proyecto C', 'creativity' => 6, 'functionality' => 10, 'innovation' => 10],
    ];

    // PANEL DE EVALUACIONES
    public function index()
    {
        // Tomar la ÚLTIMA evaluación de cada proyecto
        $evaluations = Evaluation::orderBy('created_at', 'desc')->get();

        $projects = $evaluations
            ->groupBy('project_name')
            ->map(function ($group) {
                $last = $group->first();
                return [
                    'id'           => $last->id,
                    'name'         => $last->project_name,
                    'creativity'   => $last->creativity,
                    'functionality'=> $last->functionality,
                    'innovation'   => $last->innovation,
                ];
            })
            ->values();

        if ($projects->isEmpty()) {
            $projects = collect($this->projectsDemo);
        }

        return view('admin.evaluations.index', ['projects' => $projects]);
    }

    // FORMULARIO PARA CREAR EVALUACIÓN DE UN PROYECTO (por nombre)
    public function show(string $project)
    {
        $projectName = urldecode($project);
        $rubrics = ['Rúbrica 1', 'Rúbrica 2', 'Rúbrica 3'];

        return view('admin.evaluations.show', compact('projectName', 'rubrics'));
    }

    // GUARDAR EVALUACIÓN BÁSICA EN BD
    public function store(Request $request, string $project)
    {
        $projectName = urldecode($project);

        $data = $request->validate([
            'creativity'    => ['required', 'integer', 'min:0', 'max:10'],
            'functionality' => ['required', 'integer', 'min:0', 'max:10'],
            'innovation'    => ['required', 'integer', 'min:0', 'max:10'],
            'rubric'        => ['nullable', 'string', 'max:100'],
            'comments'      => ['nullable', 'string'],
        ]);

        $data['project_name'] = $projectName;

        Evaluation::create($data);

        return redirect()
            ->route('admin.evaluations.index')
            ->with('success', 'Evaluación de ' . $projectName . ' guardada correctamente.');
    }

    // PANTALLA DE JUZGAMIENTO PARA UNA EVALUACIÓN (usa el ID)
    public function judgement(Evaluation $evaluation)
    {
        $projectName = $evaluation->project_name;

        $user = Auth::user(); // 👈 aquí usamos Auth::user() (Intelephense ya no se queja)
        $judge = $evaluation->judge ?? ($user ? $user->name : '');

        $date  = $evaluation->evaluated_at ?? now();
        $team  = $evaluation->team ?? '';
        $total = $evaluation->total_score
            ?? ($evaluation->creativity + $evaluation->functionality + $evaluation->innovation);

        return view('admin.evaluations.judgement', compact(
            'evaluation',
            'projectName',
            'judge',
            'date',
            'team',
            'total'
        ));
    }

    // GUARDAR DATOS DE JUZGAMIENTO EN LA MISMA EVALUATION
    public function saveJudgement(Request $request, Evaluation $evaluation)
    {
        $data = $request->validate([
            'judge'        => ['required', 'string', 'max:255'],
            'team'         => ['nullable', 'string', 'max:255'],
            'total_score'  => ['required', 'integer', 'min:0'],
            'evaluated_at' => ['required', 'date'],
            'comments'     => ['nullable', 'string'],
        ]);

        $evaluation->update($data);

        return redirect()
            ->route('admin.evaluations.index')
            ->with('success', 'Juzgamiento de ' . $evaluation->project_name . ' guardado correctamente.');
    }
}


