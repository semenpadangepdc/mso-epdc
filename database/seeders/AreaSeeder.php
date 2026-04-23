<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plant;
use App\Models\Area;

class AreaSeeder extends Seeder
{
    public function run()
    {
        $areasByPlant = [
            'Indarung 5' => [
                'Finish Mill',
                'SP-KILN-Cooler',
                'Raw Mill',
                'Coal Mill',
            ],

            'Indarung 4' => [
                'Finish Mill',
                'Raw Mill',
                'SP-KILN-Cooler',
                'Coal Mill',
            ],

            'Indarung 23' => [
                'Finish Mill',
                'Raw Mill',
                'SP-KILN-Cooler',
                'Coal Mill',
            ],
            
            'Indarung 6' => [
                'Finish Mill',
                'Raw Mill',
                'SP-KILN-Cooler',
                'Coal Mill',
            ],
        ];

        foreach ($areasByPlant as $plantName => $areas) {

            $plant = Plant::where('name', $plantName)->first();

            if (!$plant) {
                dump("Plant $plantName tidak ditemukan!");
                continue;
            }

            foreach ($areas as $areaName) {
                Area::firstOrCreate([
                    'plant_id' => $plant->id,
                    'name'     => $areaName,
                ]);
            }
        }
    }
}
