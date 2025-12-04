<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Evaluation;


class EvaluationController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $evaluation = new Evaluation;
        $evaluation->project_id = $project->id;
        $evaluation->evaluation_status = 'Evaluado';
        $evaluation->save();

        return redirect()->route('projects.index');
    }
}

