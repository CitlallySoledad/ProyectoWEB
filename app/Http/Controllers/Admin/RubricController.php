<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rubric;
use App\Models\RubricCriterion;
use App\Models\Event;
use Illuminate\Http\Request;

class RubricController extends Controller
{
    /**
     * Mostrar todas las rúbricas (Admin)
     */
    public function index(Request $request)
    {
        $rubrics = Rubric::with('event')->get();

        $rubric = null;
        if ($request->has('rubric')) {
            $rubric = Rubric::with('criteria')->find($request->rubric);
        }

        return view('admin.rubrics.index', compact('rubrics', 'rubric'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $events = Event::all();
        return view('admin.rubrics.create', compact('events'));
    }

    /**
     * Crear nueva rúbrica
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'event_id' => 'nullable|exists:events,id',
            'status' => 'required|in:activa,inactiva',
        ]);

        $rubric = Rubric::create($data);

        return redirect()->route('admin.rubrics.index', ['rubric' => $rubric->id])
            ->with('success', 'Rúbrica creada correctamente.');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Rubric $rubric)
    {
        $events = Event::all();
        return view('admin.rubrics.edit', compact('rubric', 'events'));
    }

    /**
     * Actualizar rúbrica
     */
    public function update(Request $request, Rubric $rubric)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'event_id' => 'nullable|exists:events,id',
            'status' => 'required|in:activa,inactiva',
        ]);

        $rubric->update($data);

        return redirect()->route('admin.rubrics.index', ['rubric' => $rubric->id])
            ->with('success', 'Rúbrica actualizada correctamente.');
    }

    /**
     * Eliminar rúbrica
     */
    public function destroy(Rubric $rubric)
    {
        $rubric->delete();
        return redirect()->route('admin.rubrics.index')
            ->with('success', 'Rúbrica eliminada.');
    }

    /**
     * Agregar criterio a la rúbrica
     */
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

        return redirect()->route('admin.rubrics.index', ['rubric' => $rubric->id])
            ->with('success', 'Criterio agregado.');
    }

    /**
     * Actualizar criterio
     */
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

        return redirect()->route('admin.rubrics.index', ['rubric' => $criterion->rubric_id])
            ->with('success', 'Criterio actualizado.');
    }

    /**
     * Eliminar criterio
     */
    public function destroyCriterion(RubricCriterion $criterion)
    {
        $rubricId = $criterion->rubric_id;
        $criterion->delete();
        
        return redirect()->route('admin.rubrics.index', ['rubric' => $rubricId])
            ->with('success', 'Criterio eliminado.');
    }

    /**
     * Actualización masiva de criterios
     */
    public function bulkUpdate(Request $request, Rubric $rubric)
    {
        $request->validate([
            'criteria' => 'required|array',
            'criteria.*.id' => 'required|exists:rubric_criteria,id',
            'criteria.*.name' => 'required|string|max:255',
            'criteria.*.weight' => 'required|numeric|min:0',
            'criteria.*.min_score' => 'required|numeric|min:0',
            'criteria.*.max_score' => 'required|numeric',
        ]);

        foreach ($request->criteria as $criterionData) {
            $criterion = RubricCriterion::find($criterionData['id']);
            if ($criterion && $criterion->rubric_id == $rubric->id) {
                $criterion->update([
                    'name' => $criterionData['name'],
                    'description' => $criterionData['description'] ?? null,
                    'weight' => $criterionData['weight'],
                    'min_score' => $criterionData['min_score'],
                    'max_score' => $criterionData['max_score'],
                ]);
            }
        }

        return redirect()->route('admin.rubrics.index', ['rubric' => $rubric->id])
            ->with('success', 'Criterios actualizados correctamente.');
    }
}
