<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;

class AdminEventController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('start_date', 'asc')->get();

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        // Obtener todos los usuarios con rol de juez
        $judges = User::role('judge')->orderBy('name')->get();
        
        // Obtener todas las rúbricas activas
        $rubrics = \App\Models\Rubric::where('status', 'activa')->orderBy('name')->get();
        
        return view('admin.events.create', compact('judges', 'rubrics'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'place'        => ['nullable', 'string', 'max:255'],
            'capacity'     => ['nullable', 'integer', 'min:1'],
            'start_date'   => ['nullable', 'date'],
            'end_date'     => ['nullable', 'date', 'after_or_equal:start_date'],
            'status'       => ['nullable', 'string', 'max:50'],
            'category'     => ['nullable', 'string', 'max:255'],
            'judge_ids'    => ['nullable', 'array'],
            'judge_ids.*'  => ['exists:users,id'],
            'rubric_ids'   => ['nullable', 'array'],
            'rubric_ids.*' => ['exists:rubrics,id'],
        ]);

        Event::create($data);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Evento creado correctamente.');
    }

    public function edit(Event $event)
    {
        // Obtener todos los usuarios con rol de juez
        $judges = User::role('judge')->orderBy('name')->get();
        
        // Obtener todas las rúbricas activas
        $rubrics = \App\Models\Rubric::where('status', 'activa')->orderBy('name')->get();
        
        return view('admin.events.edit', compact('event', 'judges', 'rubrics'));
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'place'        => ['nullable', 'string', 'max:255'],
            'capacity'     => ['nullable', 'integer', 'min:1'],
            'start_date'   => ['nullable', 'date'],
            'end_date'     => ['nullable', 'date', 'after_or_equal:start_date'],
            'status'       => ['nullable', 'string', 'max:50'],
            'category'     => ['nullable', 'string', 'max:255'],
            'judge_ids'    => ['nullable', 'array'],
            'judge_ids.*'  => ['exists:users,id'],
            'rubric_ids'   => ['nullable', 'array'],
            'rubric_ids.*' => ['exists:rubrics,id'],
        ]);

        $event->update($data);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Evento actualizado correctamente.');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Evento eliminado.');
    }

    public function showResults(Event $event)
    {
        // Cargar equipos inscritos con sus proyectos y evaluaciones
        $teams = $event->teams()->with(['members', 'projects' => function($query) use ($event) {
            $query->where('event_id', $event->id)
                  ->with(['evaluations.judge', 'evaluations.rubric']);
        }])->get();

        // Calcular promedio de calificaciones por equipo
        $teamsWithScores = $teams->map(function($team) {
            $project = $team->projects->first();
            if ($project && $project->evaluations->isNotEmpty()) {
                $avgScore = $project->evaluations->avg('final_score');
                $team->avg_score = $avgScore;
                $team->has_score = true;
            } else {
                $team->avg_score = 0;
                $team->has_score = false;
            }
            return $team;
        });

        // Ordenar por promedio de calificación (solo equipos con evaluaciones)
        $rankedTeams = $teamsWithScores->filter(fn($team) => $team->has_score)
                                       ->sortByDesc('avg_score')
                                       ->values();

        // Asignar lugares
        $rankings = collect();
        foreach ($rankedTeams as $index => $team) {
            $place = $index + 1;
            if ($place <= 3) {
                $rankings->push([
                    'place' => $place,
                    'team' => $team,
                    'score' => $team->avg_score
                ]);
            }
        }

        // Calcular estadísticas
        $totalTeams = $teams->count();
        $teamsWithProjects = $teams->filter(fn($team) => $team->projects->isNotEmpty())->count();
        $totalEvaluations = $teams->flatMap(fn($team) => $team->projects->flatMap(fn($project) => $project->evaluations))->count();
        
        return view('admin.events.results', compact('event', 'teams', 'totalTeams', 'teamsWithProjects', 'totalEvaluations', 'rankings'));
    }
}

