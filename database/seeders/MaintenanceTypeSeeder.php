<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MaintenanceType;

class MaintenanceTypeSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => 'Basic Maint - Inspection'],
            ['name' => 'Corrective Maint - Troubleshooting'],
            ['name' => 'Patching - OverHoul'],
            ['name' => 'Pemeliharaan Level 2'],
            ['name' => 'Preparation'],
        ];

        foreach ($data as $item) {
            MaintenanceType::firstOrCreate($item);
        }
    }
}
