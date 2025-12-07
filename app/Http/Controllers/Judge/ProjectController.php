<?php

namespace App\Http\Controllers\Judge;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        $judge = Auth::user();

        // Filtrar proyectos que tengan evaluaciones hechas por este juez
        $projects = Project::with('team')
            ->whereHas('evaluations', function ($q) use ($judge) {
                $q->where('judge_id', $judge->id);
            })
            ->get();

        return view('judge.projects.index', compact('projects'));
    }
}

