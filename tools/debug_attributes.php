<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Rubric;
use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "=== Direct Model Queries ===\n\n";

$rubricId = 4;
$judgeId = 3;

echo "Rubric ID $rubricId:\n";
$rubric = Rubric::with('criteria')->find($rubricId);
if ($rubric) {
    echo "  ✓ Found: {$rubric->name}\n";
    echo "  Criteria: " . $rubric->criteria->count() . "\n";
} else {
    echo "  ✗ Not found\n";
}

echo "\nJudge ID $judgeId:\n";
$judge = User::find($judgeId);
if ($judge) {
    echo "  ✓ Found: {$judge->name}\n";
    echo "  Email: {$judge->email}\n";
} else {
    echo "  ✗ Not found\n";
}

echo "\n=== Raw Evaluation Data ===\n";
$evalRaw = DB::table('evaluations')->where('id', 1)->first();
echo "Raw rubric column value: " . var_export($evalRaw->rubric, true) . "\n";
echo "Raw judge column value: " . var_export($evalRaw->judge, true) . "\n";
echo "Raw rubric_id: {$evalRaw->rubric_id}\n";
echo "Raw judge_id: {$evalRaw->judge_id}\n";

echo "\n=== Testing getAttribute ===\n";
use App\Models\Evaluation;
$evaluation = Evaluation::find(1);

echo "\nUsing getAttribute('rubric'):\n";
$rubricAttr = $evaluation->getAttribute('rubric');
echo "Type: " . gettype($rubricAttr) . "\n";
echo "Value: " . var_export($rubricAttr, true) . "\n";

echo "\nUsing getRelation('rubric'):\n";
try {
    $rubricRel = $evaluation->getRelation('rubric');
    echo "Type: " . gettype($rubricRel) . "\n";
    echo "Value: " . var_export($rubricRel, true) . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== Done ===\n";
