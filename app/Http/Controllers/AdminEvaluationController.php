<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use Illuminate\Http\Request;

class AdminEvaluationController extends Controller
{
    // Lista de proyectos demo (por si no hay datos en BD)
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
                    'name'          => $last->project_name,
                    'creativity'    => $last->creativity,
                    'functionality' => $last->functionality,
                    'innovation'    => $last->innovation,
                ];
            })
            ->values();

        // Si aún no hay nada en BD, usamos los demo
        if ($projects->isEmpty()) {
            $projects = collect($this->projectsDemo);
        }

        return view('admin.evaluations.index', ['projects' => $projects]);
    }

    // VER FORMULARIO DE EVALUACIÓN
    public function show(string $project)
    {
        $projectName = urldecode($project);

        // Rúbricas demo
        $rubrics = ['Rúbrica 1', 'Rúbrica 2', 'Rúbrica 3'];

        return view('admin.evaluations.show', compact('projectName', 'rubrics'));
    }

    // GUARDAR EVALUACIÓN EN BD
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
}


