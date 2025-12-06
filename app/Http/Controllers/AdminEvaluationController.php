<?php

namespace App\Http\Controllers;
use App\Models\Project;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

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

    // LISTA DE PROYECTOS A EVALUAR (vista tipo tabla)
    public function projectsToEvaluate(Request $request)
{
    $search = $request->query('search');

    $projects = collect();

    if (Schema::hasTable('projects')) {
        try {
            $projects = Project::query()
                ->when($search, function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                          ->orWhere('team', 'like', "%{$search}%");
                })
                ->get()
                ->map(function ($project) {
                    return [
                        'team_name'     => $project->team ?? 'Sin equipo',
                        'project_name'  => $project->name ?? 'Proyecto sin nombre',
                        'status'        => $project->status ?? 'Pendiente',
                        'extra_members' => null,
                    ];
                });
        } catch (\Throwable $e) {
            $projects = collect();
        }
    }

    if ($projects->isEmpty()) {
        $projects = collect([
            ['team_name' => 'Dinamita',    'project_name' => 'EduTrack',            'status' => 'Pendiente', 'extra_members' => 1],
            ['team_name' => 'Escuadrón',   'project_name' => 'GreenTech Solutions', 'status' => 'Evaluado',  'extra_members' => 2],
            ['team_name' => 'Buena onda',  'project_name' => 'SafeRoute',           'status' => 'Evaluado',  'extra_members' => 0],
            ['team_name' => 'Alfa lobo',   'project_name' => 'MediConnect',         'status' => 'Pendiente', 'extra_members' => 1],
        ]);

        if ($search) {
            $projects = $projects->filter(function ($p) use ($search) {
                return stripos($p['project_name'], $search) !== false
                    || stripos($p['team_name'], $search) !== false;
            });
        }
    }

    return view('admin.evaluations.projects-list', [
        'projects' => $projects,
        'search'   => $search,
    ]);
}


    // PANTALLA "Evaluación del proyecto" (2ª imagen)
    public function projectEvaluations(string $project)
{
    $projectName = urldecode($project);

    // Traer evaluaciones reales de la BD
    $realEvaluations = Evaluation::where('project_name', $projectName)
        ->orderBy('created_at', 'desc')
        ->get();

    $isDemoMode = false;
    $evaluations = $realEvaluations;

    // Si NO hay evaluaciones reales, activamos modo demo
    if ($realEvaluations->isEmpty()) {
        $isDemoMode = true;

        $evaluations = collect([
            (object)[
                'id'           => null,
                'project_name' => 'GreenTech Solutions',
                'team'         => 'Dinamita',
                'status'       => 'Activa',
            ],
            (object)[
                'id'           => null,
                'project_name' => 'EduTrack',
                'team'         => 'Escuadron',
                'status'       => 'Inactiva',
            ],
            (object)[
                'id'           => null,
                'project_name' => 'SafeRoute',
                'team'         => 'Buena onda',
                'status'       => 'Activa',
            ],
        ]);
    }

    return view('admin.evaluations.project-evaluations', [
        'projectName' => $projectName,
        'evaluations' => $evaluations,
        'isDemoMode'  => $isDemoMode,
    ]);
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
        ->route('admin.evaluations.projects_list')   
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


        // Formulario para crear proyecto
    public function createProject()
    {
        return view('admin.evaluations.create-project');
    }

    // Guardar proyecto
    public function storeProject(Request $request)
    {
        $data = $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'team'   => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:50'],
        ]);

        Project::create($data);

        return redirect()
            ->route('admin.evaluations.projects_list')
            ->with('success', 'Proyecto creado correctamente.');
    }

    // 👁️ VER DETALLE (solo lectura)
public function detail(Evaluation $evaluation)
{
    $mode        = 'view';
    $projectName = $evaluation->project_name;

    return view('admin.evaluations.detail', compact('evaluation', 'mode', 'projectName'));
}

// ✏️ EDITAR EVALUACIÓN
public function edit(Evaluation $evaluation)
{
    $mode        = 'edit';
    $projectName = $evaluation->project_name;

    return view('admin.evaluations.detail', compact('evaluation', 'mode', 'projectName'));
}

// 💾 ACTUALIZAR
public function update(Request $request, Evaluation $evaluation)
{
    $data = $request->validate([
        'creativity'    => ['required', 'integer', 'min:0', 'max:10'],
        'functionality' => ['required', 'integer', 'min:0', 'max:10'],
        'innovation'    => ['required', 'integer', 'min:0', 'max:10'],
        'rubric'        => ['nullable', 'string', 'max:100'],
        'comments'      => ['nullable', 'string'],
    ]);

    $evaluation->update($data);

    return redirect()
        ->route('admin.evaluations.projects_list')
        ->with('success', 'EVALUACIÓN actualizada correctamente.');
}

// 🗑️ ELIMINAR
public function destroy(Evaluation $evaluation)
{
    $evaluation->delete();

    return redirect()
        ->route('admin.evaluations.projects_list')
        ->with('success', 'EVALUACIÓN eliminada correctamente.');
}



}


