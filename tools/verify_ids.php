<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Rubric;
use App\Models\User;

echo "=== Checking if Rubric ID 4 exists ===\n";
$rubric = Rubric::find(4);
if ($rubric) {
    echo "✓ Rubric ID 4 EXISTS\n";
    echo "  Name: {$rubric->name}\n";
} else {
    echo "✗ Rubric ID 4 NOT FOUND\n";
}

echo "\n=== Checking if User ID 3 exists ===\n";
$user = User::find(3);
if ($user) {
    echo "✓ User ID 3 EXISTS\n";
    echo "  Name: {$user->name}\n";
    echo "  Email: {$user->email}\n";
} else {
    echo "✗ User ID 3 NOT FOUND\n";
}

echo "\n=== All Rubrics ===\n";
$rubrics = Rubric::all();
foreach ($rubrics as $r) {
    echo "ID: {$r->id} - Name: {$r->name}\n";
}

echo "\n=== All Users ===\n";
$users = User::all();
foreach ($users as $u) {
    echo "ID: {$u->id} - Name: {$u->name} - Email: {$u->email}\n";
}
