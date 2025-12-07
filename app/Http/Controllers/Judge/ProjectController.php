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

        // Mostrar proyectos asignados a este juez y pendientes de evaluación por él
        $projects = Project::with(['team','event', 'rubric'])
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
            ->paginate(10)
            ->withQueryString();

        // obtener qué eventos tienen rúbrica para optimizar la vista
        $eventIds = $projects->pluck('event_id')->filter()->unique()->toArray();
        // mapear event_id => rubric name para mostrar la rúbrica aplicada en la lista
        $rubricsByEvent = Rubric::whereIn('event_id', $eventIds)->pluck('name', 'event_id')->toArray();

        return view('judge.projects.index', compact('projects', 'rubricsByEvent'));
    }
}

