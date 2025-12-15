<?php

// Script para probar factories
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🧪 Probando Factories...\n\n";

try {
    // Test TeamFactory
    $team = App\Models\Team::factory()->make();
    echo "✅ TeamFactory: {$team->name}\n";

    // Test EventFactory
    $event = App\Models\Event::factory()->make();
    echo "✅ EventFactory: {$event->title}\n";

    // Test ProjectFactory
    $project = App\Models\Project::factory()->make();
    echo "✅ ProjectFactory: {$project->name}\n";

    // Test RubricFactory
    $rubric = App\Models\Rubric::factory()->make();
    echo "✅ RubricFactory: {$rubric->name}\n";

    // Test RubricCriterionFactory
    $criterion = App\Models\RubricCriterion::factory()->make();
    echo "✅ RubricCriterionFactory: {$criterion->name}\n";

    // Test EvaluationFactory
    $evaluation = App\Models\Evaluation::factory()->make();
    echo "✅ EvaluationFactory: Proyecto {$evaluation->project_name}\n";

    // Test EvaluationScoreFactory
    $score = App\Models\EvaluationScore::factory()->make();
    echo "✅ EvaluationScoreFactory: Score {$score->score}\n";

    echo "\n🎉 ¡Todos los factories funcionan correctamente!\n";

} catch (\Exception $e) {
    echo "❌ Error: {$e->getMessage()}\n";
    echo "Archivo: {$e->getFile()}:{$e->getLine()}\n";
    exit(1);
}
