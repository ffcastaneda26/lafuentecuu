<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        DB::table('user_permissions')->truncate();
        DB::table('user_roles')->truncate();
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();

        // Crear permisos
        $permissions = [
            // News
            'view_news',
            'create_news',
            'edit_news',
            'delete_news',
            'publish_news',

            // Categories
            'view_categories',
            'create_categories',
            'edit_categories',
            'delete_categories',

            // Sponsors & Advertisements
            'view_sponsors',
            'create_sponsors',
            'edit_sponsors',
            'delete_sponsors',
            'view_advertisements',
            'create_advertisements',
            'edit_advertisements',
            'delete_advertisements',

            // Galleries
            'view_galleries',
            'create_galleries',
            'edit_galleries',
            'delete_galleries',

            // Widgets
            'view_widgets',
            'create_widgets',
            'edit_widgets',
            'delete_widgets',

            // Users
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',

            // Contact Info
            'view_contact_info',
            'edit_contact_info',

            // System
            'access_admin_panel',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Crear roles y asignar permisos
        $administrador = Role::create(['name' => 'Super Admin']);

        // Desarrollador - Acceso total
        $desarrollador = Role::create(['name' => 'Desarrollador']);
        $desarrollador->givePermissionTo(Permission::all());

        // Administrador - Gestión completa excepto usuarios

        $administrador = Role::create(['name' => 'Administrador']);
        $administrador->givePermissionTo([
            'view_news', 'create_news', 'edit_news', 'delete_news', 'publish_news',
            'view_categories', 'create_categories', 'edit_categories', 'delete_categories',
            'view_sponsors', 'create_sponsors', 'edit_sponsors', 'delete_sponsors',
            'view_advertisements', 'create_advertisements', 'edit_advertisements', 'delete_advertisements',
            'view_galleries', 'create_galleries', 'edit_galleries', 'delete_galleries',
            'view_widgets', 'create_widgets', 'edit_widgets', 'delete_widgets',
            'view_contact_info', 'edit_contact_info',
            'access_admin_panel',
        ]);

        // Colaborador - Solo gestión de contenido
        $colaborador = Role::create(['name' => 'Colaborador']);
        $colaborador->givePermissionTo([
            'view_news', 'create_news', 'edit_news',
            'view_categories',
            'view_galleries', 'create_galleries', 'edit_galleries',
            'access_admin_panel',
        ]);
        $general = Role::create(['name' => 'General']);
        $general->givePermissionTo([
            'view_news',
        ]);

    }
}
