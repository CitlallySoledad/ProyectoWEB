<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Evaluation;
use App\Models\Rubric;
use App\Models\User;

echo "=== Testing Manual Relation Loading ===\n\n";

$evaluation = Evaluation::find(1);

echo "Before manual loading:\n";
echo "  Rubric: " . ($evaluation->rubric ? 'LOADED' : 'NULL') . "\n";
echo "  Judge: " . ($evaluation->judge ? 'LOADED' : 'NULL') . "\n\n";

// Cargar manualmente como en el controlador
$evaluation->setRelation('rubric', Rubric::with('criteria')->find($evaluation->rubric_id));
$evaluation->setRelation('judge', User::find($evaluation->judge_id));

echo "After manual loading:\n";
echo "  Rubric: " . ($evaluation->rubric ? 'LOADED' : 'NULL') . "\n";
if ($evaluation->rubric) {
    echo "    Name: {$evaluation->rubric->name}\n";
    echo "    Criteria count: " . ($evaluation->rubric->criteria ? $evaluation->rubric->criteria->count() : 0) . "\n";
}
echo "  Judge: " . ($evaluation->judge ? 'LOADED' : 'NULL') . "\n";
if ($evaluation->judge) {
    echo "    Name: {$evaluation->judge->name}\n";
}

echo "\n=== Done ===\n";
