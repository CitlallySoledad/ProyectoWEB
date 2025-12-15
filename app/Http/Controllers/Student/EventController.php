<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

use App\Models\Event;
use App\Models\Team;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Obtener equipos donde el usuario es líder (opcionalmente filtrando por evento)
     */
    public function getUserLeaderTeams(Request $request)
    {
        $user = $request->user();
        $eventId = $request->input('event_id');
        
        $teams = Team::where('leader_id', $user->id)
            ->withCount('members')
            ->with('events') // Cargar relación con eventos
            ->get()
            ->filter(function ($team) {
                // Equipos con al menos un integrante (líder cuenta)
                return $team->members_count >= 1;
            })
            ->map(function ($team) use ($eventId) {
                $isEnrolled = false;
                
                // Verificar si el equipo ya está inscrito en este evento
                if ($eventId) {
                    $isEnrolled = $team->events()->where('events.id', $eventId)->exists();
                }
                
                return [
                    'id' => $team->id,
                    'name' => $team->name,
                    'members_count' => $team->members_count,
                    'is_enrolled' => $isEnrolled,
                ];
            })
            ->values(); // Reiniciar índices después del filtro

        return response()->json([
            'teams' => $teams
        ]);
    }

    /**
     * Inscribir el equipo del usuario al evento
     */
    public function joinEvent(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $user = $request->user();

        // Validar que se envió un team_id
        $request->validate([
            'team_id' => 'required|exists:teams,id'
        ]);

        $team = Team::findOrFail($request->team_id);

        // ========================================
        // VALIDACIÓN 1: Usuario debe ser líder
        // ========================================
        if ($team->leader_id !== $user->id) {
            return back()->with('error', 'Solo el líder del equipo puede inscribirlo en eventos.');
        }

        // ========================================
        // VALIDACIÓN 2: Evento debe estar en estado 'publicado' para aceptar inscripciones
        // ========================================
        if ($event->status !== 'publicado') {
            $statusMessages = [
                'borrador' => 'aún no está disponible para inscripciones',
                'activo' => 'ya ha comenzado y no acepta más inscripciones',
                'cerrado' => 'ya ha finalizado'
            ];
            $statusText = $statusMessages[$event->status] ?? 'no acepta inscripciones';
            return back()->with('error', 'No puedes inscribirte porque este evento ' . $statusText . '.');
        }

        // ========================================
        // VALIDACIÓN 3: Evento no debe haber finalizado
        // ========================================
        if ($event->hasEnded()) {
            return back()->with('error', 'Este evento ya ha finalizado. No se aceptan más inscripciones.');
        }

        // ========================================
        // VALIDACIÓN 4: Evento no debe estar lleno
        // ========================================
        if ($event->isFull()) {
            return back()->with('error', 'El evento ha alcanzado su capacidad máxima de ' . $event->capacity . ' equipos.');
        }

        // ========================================
        // VALIDACIÓN 5: Equipo no debe estar ya inscrito
        // ========================================
        if ($event->teams()->where('teams.id', $team->id)->exists()) {
            return back()->with('info', 'Este equipo ya está inscrito en el evento.');
        }

        // ========================================
        // VALIDACIÓN 6: Equipo debe tener al menos 1 miembro
        // ========================================
        $membersCount = $team->members()->count();
        if ($membersCount < 1) {
            return back()->with('error', 'El equipo debe tener al menos 1 miembro para inscribirse en un evento.');
        }

        // ========================================
        // INSCRIPCIÓN EXITOSA
        // ========================================
        $event->teams()->attach($team->id, [
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $slotsLeft = $event->availableSlots();
        $slotsMessage = is_numeric($slotsLeft) ? " (Quedan $slotsLeft cupos)" : '';

        return back()->with('success', '¡El equipo "' . $team->name . '" ha sido inscrito exitosamente en el evento: ' . $event->title . '!' . $slotsMessage);
    }

    /**
     * Retirar un equipo de un evento
     */
    public function leaveEvent($eventId, $teamId)
    {
        $event = Event::findOrFail($eventId);
        $team = Team::findOrFail($teamId);
        $user = $request->user();

        // Verificar que el usuario sea el líder del equipo
        if ($team->leader_id !== $user->id) {
            return back()->with('error', 'Solo el líder del equipo puede retirarlo de eventos.');
        }

        // Verificar que el equipo esté inscrito en el evento
        if (!$event->teams()->where('teams.id', $team->id)->exists()) {
            return back()->with('error', 'Este equipo no está inscrito en el evento.');
        }

        // Retirar el equipo del evento
        $event->teams()->detach($team->id);

        return back()->with('success', 'El equipo "' . $team->name . '" ha sido retirado del evento: ' . $event->title);
    }
}
