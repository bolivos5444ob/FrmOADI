<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistritosTable extends Migration
{
    public function up()
    {
        Schema::create('distritos', function (Blueprint $table) {
            $table->id(); // Columna `id` autoincremental
            $table->foreignId('departamento_id')->constrained('departamentos'); // Clave foránea
            $table->foreignId('provincia_id')->constrained('provincias'); // Clave foránea
            $table->string('nombre');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('distritos');
    }
}
