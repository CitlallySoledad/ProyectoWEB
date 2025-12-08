<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Team;
use App\Models\Event;

echo "=== Test Submission Logic ===\n\n";

// Get a user with student role
$student = User::role('student')->first();

if (!$student) {
    echo "❌ No student user found. Run seeders first.\n";
    exit(1);
}

echo "✅ Testing with user: {$student->name} (ID: {$student->id})\n\n";

// Get teams where user is leader
$leaderTeams = Team::where('leader_id', $student->id)->get();
echo "📋 Teams where user is leader: " . $leaderTeams->count() . "\n";

foreach ($leaderTeams as $team) {
    echo "   • {$team->name} (ID: {$team->id})\n";
}
echo "\n";

// Get teams in active events
$eligibleTeams = Team::where('leader_id', $student->id)
    ->whereHas('events', function ($query) {
        $query->where('status', 'activo');
    })
    ->with(['events' => function ($query) {
        $query->where('status', 'activo');
    }])
    ->get();

echo "🎯 Eligible teams (leader + active event): " . $eligibleTeams->count() . "\n";

foreach ($eligibleTeams as $team) {
    $event = $team->events->first();
    echo "   • {$team->name} → Event: {$event->name} (Status: {$event->status})\n";
}

if ($eligibleTeams->isEmpty()) {
    echo "\n⚠️  No eligible teams found!\n";
    echo "To test properly:\n";
    echo "1. Create a team with this user as leader\n";
    echo "2. Enroll the team in an event\n";
    echo "3. Make sure the event status is 'activo'\n";
} else {
    echo "\n✅ User can submit projects with these teams!\n";
}

echo "\n=== Active Events ===\n";
$activeEvents = Event::where('status', 'activo')->get();
echo "Active events count: " . $activeEvents->count() . "\n";

foreach ($activeEvents as $event) {
    echo "   • {$event->name} (Start: {$event->start_date}, End: {$event->end_date})\n";
}

if ($activeEvents->isEmpty()) {
    echo "\n⚠️  No active events! Create an event with start_date = today or earlier.\n";
}
