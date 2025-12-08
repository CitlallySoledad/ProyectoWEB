<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use App\Models\Team;
use App\Models\Event;

class JudgeAssignmentController extends Controller
{
    /**
     * Mostrar la pantalla de asignación de jurados
     */
    public function index()
    {
        // Obtener todos los proyectos con sus equipos, eventos y jueces asignados
        $projects = Project::with(['team', 'event', 'judges'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Obtener todos los usuarios con rol de juez (con conteo de proyectos)
        $judges = User::role('judge')
            ->withCount('projects')
            ->orderBy('name', 'asc')
            ->get();

        // Obtener eventos activos para filtrar
        $events = Event::orderBy('start_date', 'desc')->get();

        return view('admin.judge-assignment', compact('projects', 'judges', 'events'));
    }

    /**
     * Asignar un juez a un proyecto
     */
    public function assign(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'judge_id' => 'required|exists:users,id',
        ]);

        $project = Project::findOrFail($request->project_id);
        $judge = User::findOrFail($request->judge_id);

        // Verificar que el usuario es realmente un juez
        if (!$judge->hasRole('judge')) {
            return redirect()
                ->route('admin.judge-assignment.index')
                ->with('error', 'El usuario seleccionado no es un juez.');
        }

        // Verificar si ya está asignado
        if ($project->judges()->where('users.id', $judge->id)->exists()) {
            return redirect()
                ->route('admin.judge-assignment.index')
                ->with('warning', 'Este juez ya está asignado a este proyecto.');
        }

        // Asignar el juez al proyecto
        $project->judges()->attach($judge->id);

        return redirect()
            ->route('admin.judge-assignment.index')
            ->with('success', "Juez {$judge->name} asignado correctamente al proyecto {$project->name}.");
    }

    /**
     * Remover un juez de un proyecto
     */
    public function remove(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'judge_id' => 'required|exists:users,id',
        ]);

        $project = Project::findOrFail($request->project_id);
        $judge = User::findOrFail($request->judge_id);

        // Remover la asignación
        $project->judges()->detach($judge->id);

        return redirect()
            ->route('admin.judge-assignment.index')
            ->with('success', "Juez {$judge->name} removido del proyecto {$project->name}.");
    }

    /**
     * Asignación automática de jueces (distribuir equitativamente)
     */
    public function autoAssign(Request $request)
    {
        $request->validate([
            'event_id' => 'nullable|exists:events,id',
            'judges_per_project' => 'required|integer|min:1|max:5',
        ]);

        $judgesPerProject = $request->judges_per_project;
        $eventId = $request->event_id;

        // Obtener proyectos (filtrar por evento si se especifica)
        $projectsQuery = Project::query();
        if ($eventId) {
            $projectsQuery->where('event_id', $eventId);
        }
        $projects = $projectsQuery->get();

        if ($projects->isEmpty()) {
            return redirect()
                ->route('admin.judge-assignment.index')
                ->with('warning', 'No hay proyectos disponibles para asignar jueces.');
        }

        // Obtener todos los jueces disponibles
        $judges = User::role('judge')->get();

        if ($judges->isEmpty()) {
            return redirect()
                ->route('admin.judge-assignment.index')
                ->with('error', 'No hay jueces disponibles en el sistema.');
        }

        if ($judges->count() < $judgesPerProject) {
            return redirect()
                ->route('admin.judge-assignment.index')
                ->with('error', "No hay suficientes jueces. Se necesitan al menos {$judgesPerProject} jueces.");
        }

        $assignedCount = 0;

        // Algoritmo de distribución equitativa
        foreach ($projects as $project) {
            // Limpiar asignaciones anteriores (opcional)
            // $project->judges()->detach();

            // Obtener jueces ya asignados a este proyecto
            $assignedJudges = $project->judges()->pluck('users.id')->toArray();

            // Calcular cuántos jueces faltan
            $neededJudges = $judgesPerProject - count($assignedJudges);

            if ($neededJudges > 0) {
                // Obtener jueces disponibles (no asignados aún a este proyecto)
                $availableJudges = $judges->whereNotIn('id', $assignedJudges);

                // Ordenar por carga de trabajo (menos proyectos asignados primero)
                $availableJudges = $availableJudges->sortBy(function ($judge) {
                    return $judge->projects()->count();
                });

                // Asignar los jueces necesarios
                $judgesToAssign = $availableJudges->take($neededJudges);

                foreach ($judgesToAssign as $judge) {
                    $project->judges()->attach($judge->id);
                    $assignedCount++;
                }
            }
        }

        return redirect()
            ->route('admin.judge-assignment.index')
            ->with('success', "Asignación automática completada. Se realizaron {$assignedCount} asignaciones.");
    }
}
