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


    // Relaciones
    public function servicios()
    {
        // Forzamos a buscar en el modelo Servicios usando su propia conexión (por defecto)
        return Servicios::where('IdServicio', $this->id_servicio)->first();
    }

    public function tipoReporte()
    {
        return $this->belongsTo(TipoReporte::class, 'id_reporte', 'id_reporte');
    }

    public function tipoUsuario()
    {
        return $this->belongsTo(TipoUsuario::class, 'id_tipo_usuario', 'id_tipo_usuario');
    }

    public function universidad()
    {
        return $this->belongsTo(Universidad::class, 'id_universidad', 'id_universidad');
    }
}