<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PlantSeeder::class,
            AreaSeeder::class,
            NomenclatureSeeder::class,
            MaintenanceTypeSeeder::class,
            NomenclatureStatusSeeder::class,
            ComponentSeeder::class,
            NomenclatureTypeSeeder::class,
            PermissionSeeder::class,
            MaterialMasterExcelSeeder::class
        ]);

    }

}
