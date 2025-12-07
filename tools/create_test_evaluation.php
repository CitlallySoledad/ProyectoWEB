<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Evaluation;
use App\Models\EvaluationEvidence;
use App\Models\User;
use App\Models\Project;

$judge = User::where('email', 'judge@example.com')->first();
$project = Project::find(1);

if (!$judge || !$project) {
    echo "Falta usuario juez o proyecto\n";
    exit(1);
}

$evaluation = Evaluation::create([
    'project_id' => $project->id,
    'project_name' => $project->name,
    'rubric_id' => $project->rubric_id,
    'judge_id' => $judge->id,
    'status' => 'pendiente',
    'creativity' => 0,
    'functionality' => 0,
    'innovation' => 0,
]);

EvaluationEvidence::create([
    'evaluation_id' => $evaluation->id,
    'file_name' => 'test.txt',
    'file_path' => 'evaluations/' . $evaluation->id . '/test.txt',
    'mime_type' => 'text/plain',
    'file_size' => 123,
    'description' => 'Evidencia de prueba',
]);

echo "Evaluación y evidencia de prueba creadas. Evaluation ID: {$evaluation->id}\n";