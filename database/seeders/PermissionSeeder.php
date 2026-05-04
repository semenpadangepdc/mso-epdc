<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view mso',
            'create mso',
            'edit mso',
            'delete mso',              // optional, hanya untuk Admin
            'manage material',
            'manage system',
            'view activity log',
            'view production calendar',   // baru
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Operator: view, create, edit MSO + view activity log + view production calendar
        Role::findByName('Operator')->syncPermissions([
            'view mso',
            'create mso',
            'edit mso',
            'view activity log',
            'view production calendar',
        ]);

        // Supervisor: semua kecuali delete mso & manage system (optional)
        Role::findByName('Supervisor')->syncPermissions([
            'view mso',
            'create mso',
            'edit mso',
            'view activity log',
            'manage material',
            'view production calendar',
        ]);

        // Admin: semua permission
        Role::findByName('Admin')->syncPermissions(Permission::all());

        // Viewer: hanya view saja
        Role::findByName('Viewer')->syncPermissions([
            'view mso',
            'view activity log',
            'view production calendar',
        ]);
    }
}