<?php

namespace App\Http\Controllers\Judge;

use App\Http\Controllers\Controller;
use App\Models\Rubric;
use App\Models\RubricCriterion;
use App\Models\Event;
use Illuminate\Http\Request;

class RubricController extends Controller
{
    /**
     * Mostrar todas las rúbricas (solo lectura para jueces)
     */
    public function index(Request $request)
    {
        $rubrics = Rubric::with('event')->get();

        $rubric = null;
        if ($request->has('rubric')) {
            $rubric = Rubric::with('criteria')->find($request->rubric);
        }

        return view('judge.rubrics.index', compact('rubrics', 'rubric'));
    }

    public function create()
    {
        $events = Event::all();
        return view('judge.rubrics.create', compact('events'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'event_id' => 'nullable|exists:events,id',
            'status' => 'required|in:activa,inactiva',
        ]);

        $rubric = Rubric::create($data);

        // Si el usuario pidió aplicar inmediatamente
        if ($request->input('action') === 'apply') {
            // Validar que la rúbrica esté activa
            if ($rubric->status === 'inactiva') {
                return redirect()->route('judge.rubrics.index', ['rubric' => $rubric->id])
                    ->with('error', 'No se puede aplicar una rúbrica inactiva.');
            }

            $judge = auth()->user();

            // Buscar proyectos asignados a este juez
            $projectsQuery = \App\Models\Project::whereHas('judges', function ($q) use ($judge) {
                $q->where('users.id', $judge->id);
            });

            // Si la rúbrica está asociada a un evento, aplicar solo a proyectos de ese evento
            if ($rubric->event_id) {
                $projectsQuery->where('event_id', $rubric->event_id);
            }

            $projects = $projectsQuery->get();

            foreach ($projects as $project) {
                $project->rubric_id = $rubric->id;
                $project->save();
            }

            return redirect()->route('judge.rubrics.index', ['rubric' => $rubric->id, 'apply' => 1])
                ->with('success', 'Rúbrica creada y aplicada a ' . $projects->count() . ' proyecto(s).');
        }

        return redirect()->route('judge.rubrics.index', ['rubric' => $rubric->id])
            ->with('success', 'Rúbrica creada correctamente.');
    }

    public function edit(Rubric $rubric)
    {
        $events = Event::all();
        return view('judge.rubrics.edit', compact('rubric', 'events'));
    }

    public function update(Request $request, Rubric $rubric)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'event_id' => 'nullable|exists:events,id',
            'status' => 'required|in:activa,inactiva',
        ]);

        $rubric->update($data);

        // Si el usuario pidió aplicar después de guardar
        if ($request->input('action') === 'apply') {
            // Validar que la rúbrica esté activa
            if ($rubric->status === 'inactiva') {
                return redirect()->route('judge.rubrics.index', ['rubric' => $rubric->id])
                    ->with('error', 'No se puede aplicar una rúbrica inactiva.');
            }

            $judge = auth()->user();

            // Buscar proyectos asignados a este juez
            $projectsQuery = \App\Models\Project::whereHas('judges', function ($q) use ($judge) {
                $q->where('users.id', $judge->id);
            });

            // Si la rúbrica está asociada a un evento, aplicar solo a proyectos de ese evento
            if ($rubric->event_id) {
                $projectsQuery->where('event_id', $rubric->event_id);
            }

            $projects = $projectsQuery->get();

            foreach ($projects as $project) {
                $project->rubric_id = $rubric->id;
                $project->save();
            }

            return redirect()->route('judge.rubrics.index', ['rubric' => $rubric->id, 'apply' => 1])
                ->with('success', 'Rúbrica actualizada y aplicada a ' . $projects->count() . ' proyecto(s).');
        }

        return redirect()->route('judge.rubrics.index', ['rubric' => $rubric->id])
            ->with('success', 'Rúbrica actualizada correctamente.');
    }

    public function destroy(Rubric $rubric)
    {
        $rubric->delete();
        return redirect()->route('judge.rubrics.index')
            ->with('success', 'Rúbrica eliminada.');
    }

    // Criterios
    public function storeCriterion(Request $request, Rubric $rubric)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'weight' => 'required|numeric|min:0',
            'min_score' => 'required|numeric|min:0',
            'max_score' => 'required|numeric|gt:min_score',
        ]);

        $data['rubric_id'] = $rubric->id;
        RubricCriterion::create($data);

        return redirect()->route('judge.rubrics.index', ['rubric' => $rubric->id])
            ->with('success', 'Criterio agregado.');
    }

    public function updateCriterion(Request $request, RubricCriterion $criterion)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'weight' => 'required|numeric|min:0',
            'min_score' => 'required|numeric|min:0',
            'max_score' => 'required|numeric|gt:min_score',
        ]);

        $criterion->update($data);

        $rubric = $criterion->rubric()->first();

        // Si el usuario pidió aplicar después de guardar
        if ($request->input('action') === 'apply') {
            // Validar que la rúbrica esté activa
            if ($rubric->status === 'inactiva') {
                return back()->with('error', 'No se puede aplicar una rúbrica inactiva.');
            }

            $judge = auth()->user();

            // Buscar proyectos asignados a este juez
            $projectsQuery = \App\Models\Project::whereHas('judges', function ($q) use ($judge) {
                $q->where('users.id', $judge->id);
            });

            // Si la rúbrica está asociada a un evento, aplicar solo a proyectos de ese evento
            if ($rubric->event_id) {
                $projectsQuery->where('event_id', $rubric->event_id);
            }

            $projects = $projectsQuery->get();

            foreach ($projects as $project) {
                $project->rubric_id = $rubric->id;
                $project->save();
            }

            return redirect()->route('judge.rubrics.index', ['rubric' => $rubric->id, 'apply' => 1])
                ->with('success', 'Criterio actualizado y rúbrica aplicada a ' . $projects->count() . ' proyecto(s).');
        }

        return redirect()->route('judge.rubrics.index', ['rubric' => $rubric->id])
            ->with('success', 'Criterio actualizado.');
    }

    public function destroyCriterion(RubricCriterion $criterion)
    {
        $rubricId = $criterion->rubric_id;
        $criterion->delete();
        return redirect()->route('judge.rubrics.index', ['rubric' => $rubricId])
            ->with('success', 'Criterio eliminado.');
    }

    public function editCriterion(RubricCriterion $criterion)
    {
        $rubric = $criterion->rubric()->first();
        return view('judge.rubrics.criteria.edit', compact('criterion', 'rubric'));
    }

    /**
     * Bulk update criteria (inline save)
     */
    public function bulkUpdate(Request $request, Rubric $rubric)
    {
        $data = $request->validate([
            'criteria' => 'required|array',
            'criteria.*.id' => 'required|exists:rubric_criteria,id',
            'criteria.*.name' => 'required|string|max:255',
            'criteria.*.description' => 'nullable|string',
            'criteria.*.weight' => 'required|numeric|min:0',
            'criteria.*.min_score' => 'required|numeric|min:0',
            'criteria.*.max_score' => 'required|numeric|min:0',
        ]);

        foreach ($data['criteria'] as $c) {
            // ensure max > min
            if ((int)$c['max_score'] <= (int)$c['min_score']) {
                continue; // skip invalid rows
            }

            $criterion = RubricCriterion::find($c['id']);
            if ($criterion && $criterion->rubric_id == $rubric->id) {
                $criterion->update([
                    'name' => $c['name'],
                    'description' => $c['description'] ?? null,
                    'weight' => $c['weight'],
                    'min_score' => $c['min_score'],
                    'max_score' => $c['max_score'],
                ]);
            }
        }

        return redirect()->route('judge.rubrics.index', ['rubric' => $rubric->id])
            ->with('success', 'Criterios actualizados correctamente.');
    }

    /**
     * Apply rubric to projects assigned to current judge.
     */
    public function apply(Request $request, Rubric $rubric)
    {
        // Validar que la rúbrica esté activa
        if ($rubric->status === 'inactiva') {
            return redirect()->route('judge.rubrics.index', ['rubric' => $rubric->id])
                ->with('error', 'No se puede aplicar una rúbrica inactiva.');
        }

        $judge = auth()->user();

        // Buscar proyectos asignados a este juez
        $projectsQuery = \App\Models\Project::whereHas('judges', function ($q) use ($judge) {
            $q->where('users.id', $judge->id);
        });

        // Si la rúbrica está asociada a un evento, aplicar solo a proyectos de ese evento
        if ($rubric->event_id) {
            $projectsQuery->where('event_id', $rubric->event_id);
        }

        $projects = $projectsQuery->get();

        foreach ($projects as $project) {
            $project->rubric_id = $rubric->id;
            $project->save();
        }

        return redirect()->route('judge.rubrics.index', ['rubric' => $rubric->id, 'apply' => 1])
            ->with('success', 'Rúbrica aplicada a ' . $projects->count() . ' proyecto(s).');
    }

    /**
     * Mostrar una rúbrica específica con sus criterios.
     */
    public function show(Rubric $rubric)
    {
        $rubric->load('criteria');

        return view('judge.rubrics.show', compact('rubric'));
    }
}
