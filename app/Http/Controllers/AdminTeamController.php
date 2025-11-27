<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminTeamController extends Controller
{
    public function index()
    {
        // Más adelante puedes pasar equipos desde la BD.
        return view('admin.teams.index');
    }
}
