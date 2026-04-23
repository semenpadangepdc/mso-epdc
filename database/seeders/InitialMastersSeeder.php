<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plant;
use App\Models\Area;
use App\Models\MaintenanceType;
use App\Models\Nomenclature;

class InitialMastersSeeder extends Seeder
{
    public function run()
    {
        $plant = Plant::firstOrCreate(['name'=>'Plant 1','code'=>'P1']);
        $area = Area::firstOrCreate(['plant_id'=>$plant->id,'name'=>'Area A']);
        $mt = MaintenanceType::firstOrCreate(['name'=>'Basic']);
        Nomenclature::firstOrCreate(['area_id'=>$area->id,'name'=>'Nomen A','description'=>'Contoh']);
    }
}
