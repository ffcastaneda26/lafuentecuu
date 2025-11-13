<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Super Administrador',
            'email' => 'super_admin@medios.com',
            'password' => bcrypt('password'),
            'active' => 1,
        ])->assignRole('Super Admin');

        User::create([
            'name' => 'Administrador General',
            'email' => 'administrador@medios.com',
            'password' => bcrypt('password'),
            'active' => 1,
        ])->assignRole('Administrador');

        User::create([
            'name' => 'Desarrollador',
            'email' => 'desarrollador@medios.com',
            'password' => bcrypt('password'),
            'active' => 1,
        ])->assignRole('Desarrollador');

        User::create([
            'name' => 'Colaborador',
            'email' => 'colaborador@medios.com',
            'password' => bcrypt('password'),
            'active' => 1,
        ])->assignRole('Colaborador');

    }
}
