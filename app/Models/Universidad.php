<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Universidad extends Model
{
    use HasFactory;

    protected $table = 'BIM_Universidad';

    protected $primaryKey = 'id_universidad';

    protected $connection = 'sqlsrv_externa';


    // Campos que se pueden asignar masivamente:
    protected $fillable = [
        'descripcion',
    ];


    public function buzonIncidentesReclamos()
    {
        return $this->hasMany(BuzonIncidentesReclamos::class);
    }

}