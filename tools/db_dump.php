<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$projects = DB::table('projects')->select('id','name','rubric_id')->get();
$rubrics = DB::table('rubrics')->select('id','name')->get();
$evaluations = DB::table('evaluations')->select('id','project_id','judge_id','rubric_id','status','final_score')->get();

echo json_encode([
    'projects' => $projects,
    'rubrics' => $rubrics,
    'evaluations' => $evaluations,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
