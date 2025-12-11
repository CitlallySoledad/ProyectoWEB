<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Team;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $eventsCount = Event::where('status', 'activo')->count();
        $teamsCount  = Team::count();
        $usersCount  = User::count();

        $recentTeams = Team::with('leader')
            ->latest('created_at')
            ->paginate(3);

        return view('admin.dashboard', compact('eventsCount', 'teamsCount', 'usersCount', 'recentTeams'));
    }
}
