<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User; 
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@admin.com'], // puedes cambiarlo
            [
                'name' => 'Administrador',
                'password' => Hash::make('password123'), 
                'is_admin' => true,   // cámbialo luego
            ]
        );
        
        // Asignar rol admin
        $admin->assignRole('admin');
    }
}
