<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipodocumento extends Model
{
    use HasFactory;

    protected $table = 'RCL_tipodocumento';

    protected $connection = 'sqlsrv_externa';


    // Campos que se pueden asignar masivamente:
    protected $fillable = [
        'n_sunat',
        'descripcion',
        'siglas'
    ];

}