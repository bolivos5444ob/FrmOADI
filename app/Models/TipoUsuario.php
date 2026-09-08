<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoUsuario extends Model
{
    use HasFactory;

    protected $table = 'BIM_TipoUsuario';

    protected $primaryKey = 'id_tipo_usuario';

    protected $connection = 'sqlsrv_externa';


    // Campos que se pueden asignar masivamente:
    protected $fillable = [
        'descripcion',
    ];

}