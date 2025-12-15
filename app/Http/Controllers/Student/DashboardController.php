<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Configurar Carbon en español
        Carbon::setLocale('es');
        
        $user = Auth::user();
        
        // Obtener el próximo evento (activo o próximo a iniciar)
        $proximoEvento = Event::where('status', '!=', 'borrador')
            ->where('start_date', '>=', now()->startOfDay())
            ->orderBy('start_date', 'asc')
            ->first();
        
        // Si no hay eventos futuros, buscar el evento actual (en progreso)
        if (!$proximoEvento) {
            $proximoEvento = Event::where('status', 'activo')
                ->orderBy('start_date', 'desc')
                ->first();
        }
        
        // Obtener el equipo del usuario
        $equipo = $user->teams()->first();
        
        // Obtener actividad reciente del equipo (miembros que iniciaron sesión recientemente)
        $actividadReciente = [];
        if ($equipo) {
            // Obtener todos los miembros del equipo
            $miembros = $equipo->members()
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get();
            
            foreach ($miembros as $miembro) {
                // Considerar "reciente" si se actualizó en las últimas 24 horas
                if ($miembro->updated_at && $miembro->updated_at->gt(now()->subDay())) {
                    $actividadReciente[] = [
                        'nombre' => $miembro->name,
                        'tiempo' => $miembro->updated_at->diffForHumans(),
                    ];
                }
            }
        }
        
        return view('pagPrincipal.panelParticipante', compact('proximoEvento', 'equipo', 'actividadReciente'));
    }
}
