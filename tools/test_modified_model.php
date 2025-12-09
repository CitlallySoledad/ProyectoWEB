<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Evaluation;

echo "=== Testing Modified getAttribute ===\n\n";

$evaluation = Evaluation::find(1);

echo "Before loading relations:\n";
echo "  Accessing rubric: ";
$rubric = $evaluation->rubric;
if ($rubric) {
    echo "✓ LOADED - Name: {$rubric->name}\n";
} else {
    echo "✗ NULL\n";
}

echo "  Accessing judge: ";
$judge = $evaluation->judge;
if ($judge) {
    echo "✓ LOADED - Name: {$judge->name}\n";
} else {
    echo "✗ NULL\n";
}

echo "\nAfter explicit load:\n";
$evaluation2 = Evaluation::with(['rubric.criteria', 'judge'])->find(1);

echo "  Accessing rubric: ";
if ($evaluation2->rubric) {
    echo "✓ LOADED - Name: {$evaluation2->rubric->name}\n";
    echo "    Criteria count: " . $evaluation2->rubric->criteria->count() . "\n";
} else {
    echo "✗ NULL\n";
}

echo "  Accessing judge: ";
if ($evaluation2->judge) {
    echo "✓ LOADED - Name: {$evaluation2->judge->name}\n";
} else {
    echo "✗ NULL\n";
}

echo "\n=== Done ===\n";
