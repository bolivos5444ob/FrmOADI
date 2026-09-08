<?php

namespace Database\Seeders;

use App\Models\Departamento;
use App\Models\Provincia;
use App\Models\Distrito;
use App\Models\Area;
use App\Models\Solicitud;
use Illuminate\Database\Seeder;

class SolicitudSeeder extends Seeder
{
    public function run()
    {
        // Buscar el departamento existente (LIMA)
        $departamento = Departamento::where('nombre', 'LIMA')->first();

        if (!$departamento) {
            throw new \Exception("No se encontró el departamento LIMA.");
        }

        // Buscar la provincia existente (LIMA) relacionada con el departamento
        $provincia = Provincia::where('nombre', 'LIMA')
            ->where('departamento_id', $departamento->id)
            ->first();

        if (!$provincia) {
            throw new \Exception("No se encontró la provincia LIMA en el departamento LIMA.");
        }

        // Buscar el distrito existente (MIRAFLORES) relacionado con la provincia
        $distrito = Distrito::where('nombre', 'MIRAFLORES')
            ->where('provincia_id', $provincia->id)
            ->first();

        if (!$distrito) {
            throw new \Exception("No se encontró el distrito MIRAFLORES en la provincia LIMA.");
        }

        // Crear un área (si no existe)
        $area = Area::firstOrCreate(['nombre' => 'HOSPITAL NACIONAL DANIEL ALCIDES CARRION']);

        // Crear una solicitud con relaciones
        Solicitud::create([
            'nombres_razon_social' => 'Juan Pérez',
            'tipo_documento' => 'DNI',
            'documento_identidad' => '12345678',
            'sexo' => 'M',
            'edad' => 30,
            'autoidentificacion_etnica' => 'MESTIZO',
            'discapacidad' => 'N',
            'lengua_materna' => 'Español',
            'area_geografica' => 'Lima',
            'domicilio' => 'Av. Libertad 123',
            'numero_urbanizacion' => 'Apto 456',
            'departamento_id' => $departamento->id, // ID de LIMA
            'provincia_id' => $provincia->id, // ID de LIMA (provincia)
            'distrito_id' => $distrito->id, // ID de MIRAFLORES (distrito)
            'area_id' => $area->id,
            'correo_electronico' => 'juan@example.com',
            'telefono' => '987654321',
            'informacion_solicitada' => 'Información sobre trámites',
            'observaciones' => 'Ninguna',
        ]);
    }
}
