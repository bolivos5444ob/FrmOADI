<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuzonIncidentesReclamos extends Model
{
    use HasFactory;

    protected $table = 'BIM_BuzonIncidentesReclamos';

    protected $connection = 'sqlsrv_externa';

    protected $primaryKey = 'id_buz_inc';

    //protected $fillable = ['nombre', 'id_departamento'];

    public $timestamps = false;

    // Campos que se pueden asignar masivamente:
    protected $fillable = [
        'correlativo',
        'fecha',
        'hora',
        'numero_documento',
        'nombres_apellidos',
        'email',
        'telefono',
        'id_servicio',
        'id_universidad',
        'id_reporte',
        'detalle_reporte',
        'not_reclamo',
        'ruta_evidencia',
        'fecha_creacion',
        'id_tipo_usuario'
    ];

    protected $casts = [
        //'fecha' => 'date',
        //'fecha_creacion' => 'datetime',
    ];
}