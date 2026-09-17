<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicios extends Model
{
    use HasFactory;

    protected $table = 'Servicios';

    //protected $connection = 'sqlsrv_externa';

    protected $primaryKey = 'IdServicio';

    // Campos que se pueden asignar masivamente:
    protected $fillable = [
        'Nombre',
        'Codigo',
        'IdEspecialidad'
    ];


    // public function buzonIncidentesReclamos()
    // {
    //     return $this->hasMany(BuzonIncidentesReclamos::class);
    // }

}