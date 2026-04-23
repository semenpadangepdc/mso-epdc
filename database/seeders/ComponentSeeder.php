<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Component;

class ComponentSeeder extends Seeder
{
    public function run()
    {
        $data = [

            'DDS' => [
                'Fan',
                'Motor Fan',
                'Bag',
                'Cage & Ventury',
                'Tubesheet',
                'Pipa Purging / Blow Pipe',
                'Solenoid Valve & Coil',
                'Housing, Chamber & Man Hole',
                'Tutup Planum',
                'Membran Valve',
                'Oil & Water Trap',
                'Header Tank & Pipe',
                'Timer / Panel & Controller',
                'Pressure Gauge',
                'DP Gauge/Indicator',
                'Temperarure Motor Fan',
            ],

            'JPF' => [
                'Fan',
                'Motor Fan',
                'Bag',
                'Cage & Ventury',
                'Tubesheet',
                'Pipa Purging / Blow Pipe',
                'Solenoid Valve & Coil',
                'Membran Valve',
                'Oil & Water Trap',
                'Header Tank & Pipe',
                'Timer / Panel & Controller',
                'Pressure Gauge',
                'DP Gauge/Indicator',
                'Rotary Valve',
                'Motor Rot.Valve',
                'Screw',
                'Motor Screw',
                'Ducting Inlet & Cek Hole',
                'Ducting Outlet & Cek Hole',
                'Housing, Chamber & Man Hole',
                'Tutup Planum',
                'Damper/Throtle Valve',
                'Temperarure Motor Fan',
                'Temperarure Motor Rotary',
                'Temperarure Motor Screw',
            ],

            'Main Filter' => [
                'Actuator Damper',
                'Explosion Door (CoM)',
                'Ruang Clean Air (Dinding&Lantai)',
                'Ruang Dirty Air (Dinding&Lantai)',
                'Baffle Plate (Pengarah Aliran)',
                'Timer / Panel & Controller',
            ],

            'ESP' => [
                'Housing, Chamber & Man Hole',
                'Atap & Dinding (Penthouse)',
                'Box Supporting Isolator',
                'Supporting Isolator',
                'Heater Isolator',
                'Heater Chamber',
                'Gantungan (Hanging Rod) Frame DE',
                'Frame Discharge Electrode',
                'Discharge Electrode (DE)',
                'Bantalan Rapping DE',
                'Rapping Hammer DE',
                'Drive Shaft Rapping Hammer DE',
                'Geared Motor Rapping DE',
                'Coupling Isolator Shaft',
                'Frame Collecting Plate',
                'Collecting Plate (CP)',
                'Rapping Bar CP',
                'Bantalan Rapping CP',
                'Rapping Hammer CP',
                'Drive Shaft Rapping Hammer CP',
                'Geared Motor Rapping CP',
                'Coupling/Chain-Sprocket Shaft Rapping CP',
                'Coupling/Chain-Sprocket Shaft Rapping DE',
                'Baffle Plate (Pengarah Aliran)',
                'Gas Distribution Screen (GDS) & Lovre',
                'Walkways / Platforms / Ladder (Tangga)',
                'Control Panel ( Modul/PLC/Card/HMI)',
                'Control Panel ( CB/Kontactor/TOR/Felay)',
                'Kabel Power LV & Control',
                'Kabel High Voltage (Tension)',
                'Trafo ESP',
                'Explosion Door (CoM)',
                'Grounding Wire & Hook',
                'Ruang Control Panel',
            ],
        ];

        foreach ($data as $type => $components) {
            foreach ($components as $name) {
                Component::firstOrCreate([
                    'name' => $name,
                    'type' => $type,
                ]);
            }
        }
    }
}
