<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitudesTable extends Migration
{
    public function up()
    {
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();
            $table->string('nombres_razon_social');
            $table->enum('tipo_documento', ['DNI', 'LM', 'RUC', 'CE', 'OTRO']);
            $table->string('documento_identidad');
            $table->enum('sexo', ['M', 'F', 'N']);
            $table->integer('edad')->nullable();
            $table->string('autoidentificacion_etnica')->nullable();
            $table->enum('discapacidad', ['S', 'N']);
            $table->string('lengua_materna')->nullable();
            $table->string('area_geografica')->nullable();
            $table->string('domicilio');
            $table->string('numero_urbanizacion');
            $table->foreignId('departamento_id')->constrained('departamentos');
            $table->foreignId('provincia_id')->constrained('provincias');
            $table->foreignId('distrito_id')->constrained('distritos');
            $table->foreignId('area_id')->constrained('areas');
            $table->string('correo_electronico')->nullable();
            $table->string('telefono')->nullable();
            $table->text('informacion_solicitada');
            $table->text('observaciones')->nullable();
            $table->string('correlativo')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('solicitudes');
    }
}
