<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminEventController extends Controller
{
    public function index()
    {
        // Más adelante puedes pasar eventos desde la BD.
        return view('admin.events.index');
    }
}
