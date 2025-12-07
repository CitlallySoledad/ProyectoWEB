<?php

namespace App\Http\Controllers\Judge;

use App\Http\Controllers\Controller;
use App\Models\Rubric;
use Illuminate\Http\Request;

class RubricController extends Controller
{
    /**
     * Mostrar todas las rúbricas y opcionalmente una seleccionada.
     */
    public function index(Request $request)
    {
        $rubrics = Rubric::all();

        $rubric = null;
        if ($request->has('rubric')) {
            $rubric = Rubric::with('criteria')->find($request->rubric);
        }

        return view('judge.rubrics.index', compact('rubrics', 'rubric'));
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
