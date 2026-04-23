<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password')
        ]);
        $admin->assignRole('Admin');

        $supervisor = User::create([
            'name' => 'Supervisor',
            'email' => 'supervisor@example.com',
            'password' => Hash::make('password')
        ]);
        $supervisor->assignRole('Supervisor');

        $operator = User::create([
            'name' => 'Operator',
            'email' => 'operator@example.com',
            'password' => Hash::make('password')
        ]);
        $operator->assignRole('Operator');

        $viewer = User::create([
            'name' => 'Viewer',
            'email' => 'viewer@example.com',
            'password' => Hash::make('password')
        ]);
        $viewer->assignRole('Viewer');
    }
}
