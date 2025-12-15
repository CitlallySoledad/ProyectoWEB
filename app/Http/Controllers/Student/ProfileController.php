<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Mostrar la vista del perfil con sus secciones dinámicas.
     */
    public function show(Request $request): View
    {
        $user = $request->user();

        // Detectar qué sección está activa
        $editDatos  = $request->query('edit') === 'datos';
        $editEquipo = $request->query('edit') === 'equipo';

        // Cargar equipos del usuario con líder y miembros
        $teams = $user->teams()
            ->with(['leader', 'members'])
            ->get();

        return view('pagPrincipal.perfil', compact(
            'user',
            'editDatos',
            'editEquipo',
            'teams'
        ));
    }

    /**
     * Actualizar los datos personales del usuario.
     */
    public function updateDatos(UpdateProfileRequest $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validated();

        // Manejar la subida de foto de perfil (guardar en storage/app/public/profile_photos)
        if ($request->hasFile('profile_photo')) {
            // Eliminar foto anterior si existe
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $file = $request->file('profile_photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            Storage::disk('public')->makeDirectory('profile_photos');
            Storage::disk('public')->putFileAs('profile_photos', $file, $fileName);
            $data['profile_photo'] = 'profile_photos/' . $fileName;
        }

        $user->update($data);

        return Redirect::route('panel.perfil', ['edit' => 'datos'])
            ->with('success', 'Datos personales actualizados correctamente.');
    }

    /**
     * Actualizar solo la foto de perfil del usuario.
     */
    public function updatePhoto(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'profile_photo' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ], [
                'profile_photo.file' => 'Debes seleccionar un archivo válido.',
                'profile_photo.image' => 'El archivo debe ser una imagen.',
                'profile_photo.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif o webp.',
                'profile_photo.max' => 'La imagen no puede superar los 2MB.',
                'profile_photo.uploaded' => 'La imagen es demasiado grande o hubo un error al subirla. Intenta con una imagen más pequeña (menor a 2MB).',
            ]);

            if (!$request->hasFile('profile_photo')) {
                return Redirect::route('panel.perfil')
                    ->with('error', 'No se seleccionó ninguna imagen.');
            }

            $file = $request->file('profile_photo');
            
            if (!$file->isValid()) {
                return Redirect::route('panel.perfil')
                    ->with('error', 'El archivo no es válido o es demasiado grande. Intenta con una imagen menor a 2MB.');
            }

            $user = $request->user();

            // Eliminar foto anterior si existe
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            // Guardar nueva foto en storage/app/public/profile_photos
            $fileName = time() . '_' . $file->getClientOriginalName();
            Storage::disk('public')->makeDirectory('profile_photos');
            Storage::disk('public')->putFileAs('profile_photos', $file, $fileName);
            $relativePath = 'profile_photos/' . $fileName;
            
            if (!$relativePath) {
                return Redirect::route('panel.perfil')
                    ->with('error', 'No se pudo guardar la imagen en el servidor.');
            }
            
            $user->update(['profile_photo' => $relativePath]);

            return Redirect::route('panel.perfil')
                ->with('success', 'Foto de perfil actualizada correctamente.');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return Redirect::route('panel.perfil')
                ->withErrors($e->errors())
                ->with('error', implode(' ', $errors));
        } catch (\Exception $e) {
            return Redirect::route('panel.perfil')
                ->with('error', 'Error al subir la foto. Asegúrate de que la imagen sea menor a 2MB.');
        }
    }
}
