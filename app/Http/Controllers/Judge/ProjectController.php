<?php

namespace App\Http\Controllers\Judge;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Rubric;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $judge = Auth::user();
        $q = request('q');

        // Obtener todos los eventos donde este juez está asignado
        // Usar sintaxis compatible con SQLite y MySQL
        $eventsWithJudge = \App\Models\Event::where(function($query) use ($judge) {
            $query->whereRaw("INSTR(judge_ids, ?) > 0", ['"'.$judge->id.'"'])
                  ->orWhereRaw("judge_ids LIKE ?", ['%"'.$judge->id.'"%']);
        })
            ->pluck('id');

        // Obtener TODOS los proyectos enviados de esos eventos (incluso si no están en pivot project_judge)
        $allProjects = Project::with(['team.members','event', 'rubric', 'documents'])
            ->whereIn('event_id', $eventsWithJudge)
            ->where('status', 'enviado') // Solo proyectos enviados
            ->when($q, function ($qb) use ($q) {
                $qb->where('name', 'like', "%{$q}%")
                   ->orWhereHas('team', function ($t) use ($q) {
                       $t->where('name', 'like', "%{$q}%");
                   });
            })
            ->get();

        // Marcar cuáles ya fueron evaluados por este juez
        $allProjects->each(function($project) use ($judge) {
            $project->already_evaluated = $project->evaluations()
                ->where('judge_id', $judge->id)
                ->exists();
        });

        // Agrupar proyectos por evento
        $projectsByEvent = $allProjects->groupBy('event_id');
        
        // Obtener información de los eventos
        $events = \App\Models\Event::whereIn('id', $projectsByEvent->keys())->get()->keyBy('id');

        return view('judge.projects.index', compact('projectsByEvent', 'events', 'q'));
    }
}

