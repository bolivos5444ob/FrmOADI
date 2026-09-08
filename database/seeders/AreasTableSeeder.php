<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreasTableSeeder extends Seeder
{
    public function run()
    {
        Area::updateOrCreate(
            ['id' => '001'],
            [
                'nombre' => 'HOSPITAL NACIONAL DANIEL ALCIDES CARRION',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
    }
}