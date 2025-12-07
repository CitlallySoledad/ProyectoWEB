<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // -------------------------------------------------------
        // Limpiar caché de permisos
        // -------------------------------------------------------
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // -------------------------------------------------------
        // PERMISOS DEL SISTEMA
        // -------------------------------------------------------
        $permissions = [
            // ====== PANEL PARTICIPANTE ======
            'panel.view',
            'panel.team.view',
            'panel.team.create',
            'panel.team.join',
            'panel.submission.view',
            'panel.submission.update',

            // ====== PERFIL ======
            'profile.view',
            'profile.update',

            // ====== ADMIN ======
            'admin.dashboard',
            'admin.teams.view',
            'admin.teams.create',
            'admin.teams.edit',
            'admin.teams.delete',
            'admin.events.view',
            'admin.events.create',
            'admin.events.edit',
            'admin.events.delete',
            'admin.evaluations.view',
            'admin.evaluations.create',
            'admin.evaluations.edit',
            'admin.evaluations.delete',
            'admin.users.view',
            'admin.users.create',

            // ====== JUEZ ======
            'judge.projects.view',
            'judge.project.evaluate',
            'judge.evaluations.view',
            'judge.rubrics.view',
        ];

        // Crear permisos con guard_name explícito
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // -------------------------------------------------------
        // CREAR ROLES
        // -------------------------------------------------------
        $admin   = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $judge   = Role::firstOrCreate(['name' => 'judge', 'guard_name' => 'web']);
        $student = Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);

        // -------------------------------------------------------
        // ASIGNACIÓN DE PERMISOS A ROLES
        // -------------------------------------------------------

        // ADMIN TIENE TODO
        $admin->syncPermissions(Permission::all());

        // JUEZ
        $judge->syncPermissions([
            'judge.projects.view',
            'judge.project.evaluate',
            'judge.evaluations.view',
            'judge.rubrics.view',
        ]);

        // PARTICIPANTE (student)
        $student->syncPermissions([
            'panel.view',
            'panel.team.view',
            'panel.team.create',
            'panel.team.join',
            'panel.submission.view',
            'panel.submission.update',
            'profile.view',
            'profile.update',
        ]);

        $this->command->info('🌟 Seeder ejecutado: Roles y permisos creados correctamente.');
    }
}