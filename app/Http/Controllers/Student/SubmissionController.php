<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Event;
use App\Models\Project;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    /**
     * Muestra la pantalla de "Submisión del proyecto" para el participante.
     */
    public function show()
    {
        $user = auth()->user();
        
        // Obtener equipos donde el usuario es líder
        $eligibleTeams = Team::where('leader_id', $user->id)
            ->withCount('members')
            ->with('events')
            ->get();

        // Obtener eventos activos en los que los equipos del usuario están inscritos
        $teamIds = $eligibleTeams->pluck('id');
        $now = now()->startOfDay();
        $activeEvents = Event::where('status', '!=', 'borrador')
            ->where('start_date', '<=', $now)
            ->where(function($q) use ($now) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', $now);
            })
            ->whereHas('teams', function($query) use ($teamIds) {
                $query->whereIn('teams.id', $teamIds);
            })
            ->get();

        // Obtener TODOS los proyectos del usuario (como líder) con documentos y evento
        // Ordenar por updated_at descendente para mostrar el más reciente primero
        $projects = Project::with(['documents', 'team', 'event'])
            ->whereIn('team_id', $teamIds)
            ->orderBy('updated_at', 'desc')
            ->get();

        // Para compatibilidad con vistas antiguas, mantener $project como el más reciente
        $project = $projects->first();

        return view('pagPrincipal.submission', compact('project', 'projects', 'eligibleTeams', 'activeEvents'));
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
            'team_id'      => 'required|exists:teams,id',
            'event_id'     => 'required|exists:events,id',
        ]);

        // Validar que el usuario es líder del equipo
        $team = Team::findOrFail($request->team_id);
        if ($team->leader_id !== $user->id) {
            return redirect()
                ->route('panel.submission')
                ->with('error', 'No eres el líder de este equipo.');
        }
        
        // Verificar si ya existe un proyecto para este EQUIPO y EVENTO específico
        $existingProject = Project::where('team_id', $request->team_id)
            ->where('event_id', $request->event_id)
            ->first();
        
        if ($existingProject && $existingProject->status === 'enviado') {
            return redirect()
                ->route('panel.submission')
                ->with('error', 'No puedes editar un proyecto que ya ha sido enviado.');
        }

        // Validar que el equipo está inscrito en el evento seleccionado
        $event = Event::findOrFail($request->event_id);
        $isEnrolled = $team->events()->where('events.id', $event->id)->exists();
        
        if (!$isEnrolled) {
            return redirect()
                ->route('panel.submission')
                ->with('error', 'El equipo no está inscrito en el evento seleccionado.');
        }

        // Validar que el evento está activo
        $now = now()->startOfDay();
        if ($event->status === 'borrador' || 
            $event->start_date > $now || 
            ($event->end_date && $event->end_date < $now)) {
            return redirect()
                ->route('panel.submission')
                ->with('error', 'El evento seleccionado no está activo.');
        }

        // Crear o actualizar el proyecto (clave única: team_id + event_id)
        $project = Project::updateOrCreate(
            [
                'team_id' => $request->team_id,
                'event_id' => $request->event_id
            ],
            [
                'name' => $request->project_name,
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
            'project_id' => 'required|exists:projects,id', // Ahora necesitamos saber a qué proyecto pertenece
        ]);

        // Verificar que el proyecto existe y pertenece a un equipo del usuario
        $project = Project::whereHas('team', function ($query) use ($user) {
            $query->where('leader_id', $user->id);
        })->findOrFail($request->project_id);

        // Verificar si el proyecto ya está enviado
        if ($project->status === 'enviado') {
            return redirect()
                ->route('panel.submission')
                ->with('error', 'No puedes subir documentos a un proyecto ya enviado.');
        }

        // Guardar el archivo
        if ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $originalName = $file->getClientOriginalName();
            $safeName = time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.pdf';
            $fileSize = $file->getSize(); // capturar antes de mover

            // Guardar en storage/app/public/pdfs (requiere enlace a /pdfs)
            Storage::disk('public')->makeDirectory('pdfs');
            $relativePath = Storage::disk('public')->putFileAs('pdfs', $file, $safeName);
            
            // Guardar información en la base de datos
            \App\Models\ProjectDocument::create([
                'project_id' => $project->id,
                'file_name' => $safeName,
                'file_path' => $relativePath,
                'original_name' => $originalName,
                'file_size' => $fileSize,
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

    /**
     * Eliminar un documento PDF
     */
    public function deletePdf($documentId)
    {
        $user = auth()->user();
        
        $document = \App\Models\ProjectDocument::findOrFail($documentId);
        
        // Verificar que el documento pertenece a un proyecto del usuario
        $project = $document->project;
        if ($project->team->leader_id !== $user->id) {
            return redirect()
                ->route('panel.submission')
                ->with('error', 'No tienes permiso para eliminar este documento.');
        }
        
        // Verificar si el proyecto ya está enviado
        if ($project->status === 'enviado') {
            return redirect()
                ->route('panel.submission')
                ->with('error', 'No puedes eliminar documentos de un proyecto ya enviado.');
        }
        
        // Eliminar el archivo del storage público
        Storage::disk('public')->delete($document->file_path);
        
        // Eliminar el registro de la base de datos
        $document->delete();
        
        return redirect()
            ->route('panel.submission')
            ->with('success', 'Documento eliminado correctamente.');
    }

    /**
     * Confirmar y enviar el proyecto (cambia estado pero sigue siendo editable)
     */
    public function confirmSubmission(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'project_id' => 'required|exists:projects,id',
        ]);
        
        $project = Project::with('event')->whereHas('team', function ($query) use ($user) {
            $query->where('leader_id', $user->id);
        })->findOrFail($request->project_id);
        
        if ($project->documents()->count() === 0) {
            return redirect()
                ->route('panel.submission')
                ->with('error', 'Debes subir al menos un documento antes de enviar el proyecto.');
        }
        
        // Obtener las rúbricas del evento
        $event = $project->event;
        $rubricIds = $event->rubric_ids ?? [];
        
        if (empty($rubricIds)) {
            return redirect()
                ->route('panel.submission')
                ->with('error', 'El evento no tiene rúbricas asignadas. Contacta al administrador.');
        }
        
        // Asignar la primera rúbrica del evento al proyecto
        $rubricId = is_array($rubricIds) ? $rubricIds[0] : $rubricIds;
        
        // Obtener los jueces del evento
        $judgeIds = $event->judge_ids ?? [];
        
        if (empty($judgeIds)) {
            return redirect()
                ->route('panel.submission')
                ->with('error', 'El evento no tiene jueces asignados. Contacta al administrador.');
        }
        
        // Actualizar el estado del proyecto y asignar rúbrica
        $project->update([
            'status' => 'enviado',
            'rubric_id' => $rubricId,
        ]);
        
        // Asignar todos los jueces del evento al proyecto
        foreach ($judgeIds as $judgeId) {
            // Verificar si ya está asignado
            $exists = \DB::table('project_judge')
                ->where('project_id', $project->id)
                ->where('user_id', $judgeId)
                ->exists();
            
            if (!$exists) {
                \DB::table('project_judge')->insert([
                    'project_id' => $project->id,
                    'user_id' => $judgeId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        
        return redirect()
            ->route('panel.submission')
            ->with('success', '¡Proyecto enviado correctamente! Los jueces ya pueden evaluarlo. El proyecto ha sido bloqueado y no podrá ser editado.');
    }
}
