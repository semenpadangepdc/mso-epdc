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
            'manage material',
            'manage system',
            'view activity log',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        Role::findByName('Operator')->syncPermissions([
            'view mso',
            'create mso'
        ]);

        Role::findByName('Supervisor')->syncPermissions([
            'view mso',
            'create mso',
            'edit mso',
            'view activity log',
            'manage material'
        ]);

        Role::findByName('Admin')->syncPermissions(Permission::all());
    }
}
