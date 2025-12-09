<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Evaluation;

echo "=== Checking Evaluation ID 1 Relations ===\n\n";

$evaluation = Evaluation::with(['rubric', 'judge', 'project'])->find(1);

if (!$evaluation) {
    echo "Evaluation not found!\n";
    exit;
}

echo "Evaluation ID: {$evaluation->id}\n";
echo "Rubric ID: {$evaluation->rubric_id}\n";
echo "Judge ID: {$evaluation->judge_id}\n";
echo "Project ID: {$evaluation->project_id}\n\n";

echo "Rubric loaded: " . ($evaluation->rubric ? 'YES' : 'NO') . "\n";
if ($evaluation->rubric) {
    echo "  Rubric Name: {$evaluation->rubric->name}\n";
} else {
    echo "  Rubric Name: NULL\n";
}

echo "\nJudge loaded: " . ($evaluation->judge ? 'YES' : 'NO') . "\n";
if ($evaluation->judge) {
    echo "  Judge Name: {$evaluation->judge->name}\n";
    echo "  Judge Email: {$evaluation->judge->email}\n";
} else {
    echo "  Judge Name: NULL\n";
}

echo "\nProject loaded: " . ($evaluation->project ? 'YES' : 'NO') . "\n";
if ($evaluation->project) {
    echo "  Project Name: {$evaluation->project->name}\n";
}

echo "\n=== Done ===\n";
