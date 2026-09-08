<?php

use App\Models\Solicitud;
use Illuminate\Database\Seeder;

class ActualizarCorrelativosSeeder extends Seeder
{
    public function run()
    {
        $solicitudes = Solicitud::whereNull('correlativo')->get();

        foreach ($solicitudes as $solicitud) {
            $numero = str_pad($solicitud->id, 4, '0', STR_PAD_LEFT);
            $solicitud->correlativo = 'AIP-HNDAC' . $numero;
            $solicitud->save();
        }
    }
}