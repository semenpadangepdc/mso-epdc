<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class InitialRolesSeeder extends Seeder
{
    public function run()
    {
        $roles = ['User','UserLevel2','Engineer','Admin'];
        foreach ($roles as $r) {
            Role::firstOrCreate(['name'=>$r]);
        }
    }
}
