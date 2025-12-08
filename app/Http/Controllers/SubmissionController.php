<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Event;
use App\Models\Project;

class SubmissionController extends Controller
{
    /**
     * Muestra la pantalla de "Submisión del proyecto" para el participante.
     */
    public function show()
    {
        $user = auth()->user();
        
        // Obtener equipos donde el usuario es líder Y están en eventos activos
        // Usamos lógica de fechas porque 'status' es un accessor, no una columna
        $now = now()->startOfDay();
        $eligibleTeams = Team::where('leader_id', $user->id)
            ->whereHas('events', function ($query) use ($now) {
                $query->where('status', '!=', 'borrador')
                    ->where('start_date', '<=', $now)
                    ->where(function($q) use ($now) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>=', $now);
                    });
            })
            ->withCount('members')
            ->with(['events' => function ($query) use ($now) {
                $query->where('status', '!=', 'borrador')
                    ->where('start_date', '<=', $now)
                    ->where(function($q) use ($now) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>=', $now);
                    });
            }])
            ->get();

        // Buscar proyecto existente del usuario
        $project = Project::where('team_id', function ($query) use ($user) {
            $query->select('id')
                ->from('teams')
                ->where('leader_id', $user->id)
                ->limit(1);
        })->first();

        return view('pagPrincipal.submission', compact('project', 'eligibleTeams'));
    }
    
    /**
     * API endpoint para obtener equipos elegibles (líder + evento activo)
     */
    public function getEligibleTeams()
    {
        $user = auth()->user();
        
        $now = now()->startOfDay();
        $teams = Team::where('leader_id', $user->id)
            ->whereHas('events', function ($query) use ($now) {
                $query->where('status', '!=', 'borrador')
                    ->where('start_date', '<=', $now)
                    ->where(function($q) use ($now) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>=', $now);
                    });
            })
            ->withCount('members')
            ->with(['events' => function ($query) use ($now) {
                $query->where('status', '!=', 'borrador')
                    ->where('start_date', '<=', $now)
                    ->where(function($q) use ($now) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>=', $now);
                    })
                    ->select('events.id', 'events.name', 'events.status', 'events.start_date', 'events.end_date');
            }])
            ->get()
            ->map(function ($team) {
                return [
                    'id' => $team->id,
                    'name' => $team->name,
                    'members_count' => $team->members_count,
                    'event' => $team->events->first(), // El evento activo
                ];
            });

        return response()->json([
            'teams' => $teams
        ]);
    }

    /**
     * Procesa el formulario de actualización (nombre, visibilidad y equipo).
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'project_name' => 'required|string|max:255',
            'visibility'   => 'required|in:privado,publico',
            'team_id'      => 'required|exists:teams,id',
        ]);

        // Validar que el usuario es líder del equipo
        $team = Team::findOrFail($request->team_id);
        if ($team->leader_id !== $user->id) {
            return redirect()
                ->route('panel.submission')
                ->with('error', 'No eres el líder de este equipo.');
        }

        // Validar que el equipo está en un evento activo (usando lógica de fechas)
        $now = now()->startOfDay();
        $activeEvent = $team->events()
            ->where('status', '!=', 'borrador')
            ->where('start_date', '<=', $now)
            ->where(function($q) use ($now) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', $now);
            })
            ->first();
        if (!$activeEvent) {
            return redirect()
                ->route('panel.submission')
                ->with('error', 'El equipo debe estar inscrito en un evento activo.');
        }

        // Crear o actualizar el proyecto
        $project = Project::updateOrCreate(
            ['team_id' => $request->team_id],
            [
                'name' => $request->project_name,
                'visibility' => $request->visibility,
                'event_id' => $activeEvent->id,
                'status' => 'pendiente',
            ]
        );

        return redirect()
            ->route('panel.submission')
            ->with('success', 'El proyecto se ha guardado correctamente.');
    }

    /**
     * Pantalla de gestión de repositorios asociados a la submisión.
     */
    public function repositories()
    {
        $team = Team::orderBy('created_at', 'desc')->first();
        $projectName = $team ? $team->name : null;

        // Datos de ejemplo de repositorios (puedes reemplazar por datos reales de la BD)
        $repositories = $projectName ? [
            [
                'name' => $projectName . '-Frontend',
                'url'  => 'https://github.com/ejemplo/' . $projectName . '-frontend',
            ],
            [
                'name' => $projectName . '-Backend',
                'url'  => 'https://github.com/ejemplo/' . $projectName . '-backend',
            ],
        ] : [];

        return view('pagPrincipal.submissionRepositories', compact('projectName', 'repositories'));
    }

    /**
     * Subir archivo PDF
     */
    public function uploadPdf(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'pdf_file' => 'required|file|mimes:pdf|max:10240', // 10MB max
            'description' => 'nullable|string|max:500',
        ]);

        // Verificar que el usuario tenga un proyecto activo
        $project = Project::whereHas('team', function ($query) use ($user) {
            $query->where('leader_id', $user->id);
        })->first();

        if (!$project) {
            return redirect()
                ->route('panel.submission')
                ->with('error', 'Debes guardar tu proyecto primero antes de subir documentos.');
        }

        // Guardar el archivo
        if ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $originalName = $file->getClientOriginalName();
            $fileName = time() . '_' . $originalName;
            
            // Guardar en storage/app/public/pdfs
            $path = $file->storeAs('pdfs', $fileName, 'public');
            
            // Guardar información en la base de datos
            \App\Models\ProjectDocument::create([
                'project_id' => $project->id,
                'file_name' => $fileName,
                'file_path' => $path,
                'original_name' => $originalName,
                'file_size' => $file->getSize(),
                'description' => $request->description,
            ]);
            
            return redirect()
                ->route('panel.submission')
                ->with('success', 'PDF subido correctamente: ' . $originalName);
        }

        return redirect()
            ->route('panel.submission')
            ->with('error', 'No se pudo subir el archivo.');
    }
}
