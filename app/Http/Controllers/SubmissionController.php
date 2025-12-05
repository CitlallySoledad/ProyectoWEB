<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;

class SubmissionController extends Controller
{
    /**
     * Muestra la pantalla de "Submisión del proyecto" para el participante.
     */
    public function show()
    {
        // Por ahora tomamos el primer equipo como ejemplo de proyecto del usuario.
        // Más adelante lo puedes cambiar para que tome sólo el equipo del usuario logueado.
        $team = Team::orderBy('created_at', 'desc')->first();

        $project = null;

        if ($team) {
            $project = [
                'name'       => $team->name,
                'visibility' => 'privado', // valor por defecto
            ];
        }

        return view('pagPrincipal.submission', compact('project'));
    }

    /**
     * Procesa el formulario de actualización (nombre y visibilidad).
     * Solo validamos y mostramos mensaje; luego puedes guardar en BD.
     */
    public function update(Request $request)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
            'visibility'   => 'required|in:privado,publico',
        ]);

        // Aquí podrías guardar en BD la información real de la submisión.
        // Ejemplo:
        // Submission::updateOrCreate(
        //   ['user_id' => auth()->id()],
        //   ['project_name' => $request->project_name, 'visibility' => $request->visibility]
        // );

        return redirect()
            ->route('panel.submission')
            ->with('success', 'La submisión se ha actualizado correctamente (demo).');
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
}
