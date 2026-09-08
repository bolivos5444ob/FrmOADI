<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Correlativo extends Model
{
    use HasFactory;

    protected $table = 'RCL_correlativo'; // <- aquí defines la tabla correcta

    protected $connection = 'sqlsrv_externa';

    public $timestamps = false;

    protected $fillable = [
        'descripcion',
        'siglas',
        'numero_correlativo',
    ];
}