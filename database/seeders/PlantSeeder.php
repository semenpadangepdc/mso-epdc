<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plant;

class PlantSeeder extends Seeder
{
    public function run()
    {
        $plants = [
            'Indarung 23',
            'Indarung 4',
            'Indarung 5',
            'Indarung 6',
        ];

        foreach ($plants as $p) {
            Plant::firstOrCreate(['name' => $p]);
        }
    }
}
