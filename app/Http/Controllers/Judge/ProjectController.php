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

        // Obtener proyectos asignados a este juez y pendientes de evaluación por él
        $allProjects = Project::with(['team.members','event', 'rubric', 'documents'])
            ->whereHas('judges', function ($jb) use ($judge) {
                $jb->where('users.id', $judge->id);
            })
            ->whereDoesntHave('evaluations', function ($qBuilder) use ($judge) {
                $qBuilder->where('judge_id', $judge->id);
            })
            ->when($q, function ($qb) use ($q) {
                $qb->where('name', 'like', "%{$q}%")
                   ->orWhereHas('team', function ($t) use ($q) {
                       $t->where('name', 'like', "%{$q}%");
                   });
            })
            ->get();

        // Agrupar proyectos por evento
        $projectsByEvent = $allProjects->groupBy('event_id');
        
        // Obtener información de los eventos
        $events = \App\Models\Event::whereIn('id', $projectsByEvent->keys())->get()->keyBy('id');

        return view('judge.projects.index', compact('projectsByEvent', 'events', 'q'));
    }
}

