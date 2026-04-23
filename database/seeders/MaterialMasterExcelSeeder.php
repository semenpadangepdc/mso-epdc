<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MaterialMaster;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MaterialMasterExcelSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/data/MatMaster.xlsx');

        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Remove header
        unset($rows[0]);

        foreach ($rows as $row) {
            MaterialMaster::updateOrCreate(
                ['material_code' => trim($row[0])],
                [
                    'material_description' => $row[1],
                    'long_text'            => $row[2],
                    'base_uom'             => $row[3],
                    'mrp_type'             => $row[4],
                    'price'                => is_numeric($row[5]) ? $row[5] : null,
                    'material_group'       => $row[6],
                    'gl_account'           => $row[7],
                    'safety_stock'         => is_numeric($row[8]) ? $row[8] : null,
                    'critical_part'        => strtolower($row[9]) === 'yes',
                ]
            );
        }
    }
}
