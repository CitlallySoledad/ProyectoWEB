<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Team;
use App\Models\Event;

echo "=== Setup Test Enrollment ===\n\n";

// Get first student
$student = User::role('student')->first();
if (!$student) {
    echo "❌ No student found\n";
    exit(1);
}

// Get their leader team
$team = Team::where('leader_id', $student->id)->first();
if (!$team) {
    echo "❌ No team where user is leader\n";
    exit(1);
}

// Get first active event
$event = Event::where('status', 'activo')->first();
if (!$event) {
    echo "❌ No active event found\n";
    exit(1);
}

echo "User: {$student->name}\n";
echo "Team: {$team->name}\n";
echo "Event: {$event->name}\n\n";

// Check if already enrolled
if ($team->events()->where('events.id', $event->id)->exists()) {
    echo "✅ Team already enrolled in this event!\n";
} else {
    // Enroll the team
    $team->events()->attach($event->id);
    echo "✅ Team enrolled in event successfully!\n";
}

echo "\nNow run test_submission_logic.php again to verify.\n";
