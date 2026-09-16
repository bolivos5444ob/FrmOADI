
{{-- resources/views/mi-vista.blade.php --}}
@extends('layouts.app')



@section('title', 'Mi página')



@section('content')



<style>
    .select2 {
        width:100%!important;
    }

    /* Ajusta la altura general del contenedor de Select2 para que coincida con el input */
    .select2-container .select2-selection--single {
        height: 50px !important; /* Cambia 38px por la altura exacta de tu textbox si es distinta */
        padding: 10px 5px;
        
    }

    /* Alinea verticalmente el texto dentro del Select2 */
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 28px !important; 
    }

    /* Alinea la flecha desplegable al centro */
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 50px !important;
    }

    @media (max-width: 767px) {
        .hide-on-mobile {
          display: none !important;
      }
  }
</style>


<!-- Main Content Area centered vertically and horizontally -->
<main class="min-h-screen w-full flex justify-center py-xl px-margin-mobile md:px-margin-desktop bg-cover bg-center bg-no-repeat inset-0 overflow-y-auto" style="background-image: linear-gradient(rgba(247, 249, 251, 0.7), rgba(247, 249, 251, 0.7)), url(&quot;https://lh3.googleusercontent.com/aida-public/AB6AXuAmTRL61ePV11H7fnQ5i0rFOkBNotlGiOQBRnpCxu_krQfcuEWBR8SAxlevuCwpaaDFTt8BgUMaP1c6C4XtFTu3C8C7L0WwhkCkJcsaqA6_Gznxv7kxdHJnOFftY7iRq_bqDGsaJwQmpSRBZ7zc89vHMa68Pr5YR9fkw_U1Z7-w0OdImav9odk_YVfNvQe5-Q9XNqQOzK-8UTg2GJ5jKEGHhhMKwaOSxvi9rNp303rHlwhj-wQ4rPbDi9q-Wrydny5RRSDLwCeBxoY&quot;);">
    <div class="max-w-[1000px] w-full relative z-10 flex flex-col items-center">
<!-- Logo centered above the form -->
<div class="mb-lg flex items-center gap-sm">
    <span class="material-symbols-outlined text-primary text-4xl" style="font-variation-settings: 'FILL' 1;">medical_services</span>
    <span class="font-headline-lg text-headline-lg font-bold text-primary tracking-tight">OADI</span>
</div>
<!-- Form Container (Glassmorphism) -->
<div class="glass-panel rounded-xl shadow-2xl p-lg md:p-xl flex flex-col gap-xl w-full" > 
<!-- Header Section within the Card -->
<div class="text-center">
    <h1 class="font-headline-md text-headline-md text-primary mb-xs">
        BUZÓN DE NOTIFICACIÓN DE INCIDENTES, <br>
        RECLAMOS Y SUGERENCIAS DE LOS INTERNOS
    </h1>
<!-- <p class="font-body-sm text-body-sm text-on-surface-variant max-w-2xl mx-auto">
                        Utilice este formulario institucional para reportar cualquier evento o sugerencia. Su identidad y reporte serán tratados con la máxima confidencialidad para la mejora continua de nuestro servicio.
                    </p> -->
                </div>
<!-- Section I Header -->
<div class="border-l-4 border-primary pl-md py-sm">
    <h2 class="font-headline-sm text-headline-sm text-on-surface uppercase tracking-wide">
        I. IDENTIFICACIÓN DEL INTERNO
    </h2>
</div>


<!-- Grid Layout for Fields -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-lg">
<!-- Row 1: Fecha & Hora -->
<div class="flex flex-col gap-xs">
    <label class="font-label-md text-label-md text-on-surface-variant">TIPO DE USUARIO</label>
    <div class="relative">
        <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-outline">person_search</span>
        <select class="select2 w-full pl-[48px] pr-md h-12 rounded-lg border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary bg-surface-container-lowest transition-all appearance-none" id="id_tipo_usuario" name="id_tipo_usuario">
            
            <option value="0" disabled selected >Seleccionar</option>
            @foreach ($tipoUsuarios as $tUsuario)
            <option value="{{ $tUsuario->id_tipo_usuario }}">{{ $tUsuario->descripcion}}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="flex flex-col gap-xs">
    <div class="grid grid-cols-2 gap-sm">
        <div class="flex flex-col gap-xs">
            <label class="font-label-md text-label-md text-on-surface-variant">FECHA</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-outline">calendar_today</span>
                <input readonly id="fecha" name="fecha"value="{{ now()->format('Y-m-d') }}"  class="w-full pl-[48px] pr-md h-12 rounded-lg border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary bg-surface-container-lowest transition-all" type="date">
            </div>
        </div> 

        <div class="flex flex-col gap-xs">
            <label class="font-label-md text-label-md text-on-surface-variant">HORA</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-outline">schedule</span>
                <input readonly id="hora" name="hora" value="{{ now()->format('H:i') }}" class="w-full pl-[48px] pr-md h-12 rounded-lg border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary bg-surface-container-lowest transition-all" type="time">
            </div>
        </div>
    </div>
</div>
<!-- Row 2: Documento & Nombres -->



<!-- Row 2: Documento & Nombres -->
<div class="flex flex-col gap-xs">
    <label class="font-label-md text-label-md text-on-surface-variant">NÚMERO DE DOCUMENTO</label>
    <div class="relative">
        <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-outline">badge</span>
        <input class="w-full pl-[48px] pr-md h-12 rounded-lg border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary bg-surface-container-lowest transition-all" placeholder="DNI" type="text" id="numero_documento" name="numero_documento" maxlength="8">
    </div>
</div>
<div class="flex flex-col gap-xs">
    <label class="font-label-md text-label-md text-on-surface-variant">NOMBRES Y APELLIDOS</label>
    <div class="relative">
        <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-outline">person</span>
        <input maxlength="100" class="w-full pl-[48px] pr-md h-12 rounded-lg border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary bg-surface-container-lowest transition-all" placeholder="Ingrese nombre completo" type="text" id="nombres_apellidos" name="nombres_apellidos">
    </div>
</div>
<!-- Row 3: Email & Telefono -->
<div class="flex flex-col gap-xs">
    <label class="font-label-md text-label-md text-on-surface-variant">EMAIL</label>
    <div class="relative">
        <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-outline">mail</span>
        <input maxlength="60" class="w-full pl-[48px] pr-md h-12 rounded-lg border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary bg-surface-container-lowest transition-all" placeholder="correo@ejemplo.com" type="email" id="email" name="email">
    </div>
</div>
<div class="flex flex-col gap-xs">
    <label class="font-label-md text-label-md text-on-surface-variant">TELÉFONO</label>
    <div class="relative">
        <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-outline">call</span>
        <input maxlength="9" class="w-full pl-[48px] pr-md h-12 rounded-lg border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary bg-surface-container-lowest transition-all" placeholder="900 000 000" type="tel" id="telefono" name="telefono">
    </div>
</div>
<!-- Row 4: Servicio & Universidad -->
<div class="flex flex-col gap-xs">
    <label class="font-label-md text-label-md text-on-surface-variant">SERVICIO DE ROTACIÓN</label>
    <div class="relative">

        <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-outline">person</span>

        <select class="select2 w-full pl-[48px] pr-md h-12 rounded-lg border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary bg-surface-container-lowest transition-all" placeholder="Seleccione del Servicio" id="id_servicio" name="id_servicio">

            <option value="0" disabled selected >Seleccionar</option>
            @foreach ($servicios as $servicio)
            <option value="{{ $servicio->IdServicio }}">{{ $servicio->Codigo .' = '. $servicio->Nombre }}</option>
            @endforeach

        </select>

</div>
</div>




<div class="flex flex-col gap-xs">
    <label class="font-label-md text-label-md text-on-surface-variant">UNIVERSIDAD</label>
    <div class="relative">
        <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-outline">school</span>

        <select class="select2 w-full pl-[48px] pr-md h-12 rounded-lg border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary bg-surface-container-lowest transition-all" placeholder="Entidad académica" id="id_universidad" name="id_universidad">

            <option value="0" disabled selected >Seleccionar</option>
            @foreach ($universidades as $uni)
            <option value="{{ $uni->id_universidad }}">{{ $uni->descripcion }}</option>
            @endforeach

        </select>
    </div>
</div>
</div>


<!-- Type of Report Selection (Interactive Cards) -->
<div class="flex flex-col gap-md">
    <label class="font-label-md text-label-md text-on-surface-variant">TIPO DE REPORTE</label>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-md">
<!-- Incidente Clínico -->
<label class="relative cursor-pointer group">
    <input checked class="peer sr-only" name="id_reporte" type="radio" value="1">
    <div class="h-full p-md border-2 border-outline-variant rounded-xl flex flex-col items-center gap-sm text-center transition-all peer-checked:border-primary peer-checked:bg-primary/5 hover:bg-surface-container">
        <span class="material-symbols-outlined text-4xl text-outline group-hover:text-primary transition-colors peer-checked:text-primary" style="font-variation-settings: 'FILL' 0;">emergency_home</span>
        <span class="font-label-md text-label-md font-bold uppercase">Incidente</span>
        <p class="text-[12px] text-on-surface-variant leading-tight"></p>
    </div>
    <div class="absolute top-2 right-2 opacity-0 peer-checked:opacity-100 transition-opacity">
        <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">check_circle</span>
    </div>
</label>
<!-- Reclamo -->
<label class="relative cursor-pointer group">
    <input class="peer sr-only" name="id_reporte" type="radio" value="2">
    <div class="h-full p-md border-2 border-outline-variant rounded-xl flex flex-col items-center gap-sm text-center transition-all peer-checked:border-primary peer-checked:bg-primary/5 hover:bg-surface-container">
        <span class="material-symbols-outlined text-4xl text-outline group-hover:text-primary transition-colors peer-checked:text-primary" style="font-variation-settings: 'FILL' 0;">report_problem</span>
        <span class="font-label-md text-label-md font-bold uppercase">Reclamo</span>
        <p class="text-[12px] text-on-surface-variant leading-tight">Inconformidades con servicios o procesos.</p>
    </div>
    <div class="absolute top-2 right-2 opacity-0 peer-checked:opacity-100 transition-opacity">
        <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">check_circle</span>
    </div>
</label>
<!-- Sugerencias -->
<label class="relative cursor-pointer group">
    <input class="peer sr-only" name="id_reporte" type="radio" value="3">
    <div class="h-full p-md border-2 border-outline-variant rounded-xl flex flex-col items-center gap-sm text-center transition-all peer-checked:border-primary peer-checked:bg-primary/5 hover:bg-surface-container">
        <span class="material-symbols-outlined text-4xl text-outline group-hover:text-primary transition-colors peer-checked:text-primary" style="font-variation-settings: 'FILL' 0;">lightbulb</span>
        <span class="font-label-md text-label-md font-bold uppercase">Sugerencias</span>
        <p class="text-[12px] text-on-surface-variant leading-tight">Propuestas para optimizar procesos y satisfacción.</p>
    </div>







    <div class="absolute top-2 right-2 opacity-0 peer-checked:opacity-100 transition-opacity">
        <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">check_circle</span>
    </div>
</label>
</div>
</div>
<!-- Footer Actions -->
<!-- Section II: Redacción -->
<div class="flex flex-col gap-lg">
    <div class="border-l-4 border-primary pl-md py-sm">
        <h2 class="font-headline-sm text-headline-sm text-on-surface uppercase tracking-wide">
            II. REDACTAR SEGÚN TIPO DE REPORTE
        </h2>
    </div>
    <div class="flex flex-col gap-xs">
        <label class="font-label-md text-label-md text-on-surface-variant">DESCRIPCIÓN DETALLADA DE LOS HECHOS</label>
        <textarea maxlength="2000" class="w-full p-md rounded-lg border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary bg-surface-container-lowest transition-all min-h-[150px]" placeholder="Describa detalladamente los hechos, participantes, lugar y contexto del reporte..." id="detalle_reporte" name="detalle_reporte"></textarea>
    </div>
<!-- File Upload Zone -->



<div class="flex flex-col gap-xs">
    <label class="font-label-md text-label-md text-on-surface-variant uppercase">Evidencias</label>

    <div class="col-md-12"style="margin-bottom: 1em;">
        <form  class="dropzone border-2 border-dashed border-outline-variant rounded-xl p-xl flex flex-col items-center justify-center gap-md hover:bg-surface-container transition-all cursor-pointer group" id="dz_Adjunto_evidencia">

            <span class="material-symbols-outlined text-4xl text-outline group-hover:text-primary transition-colors">cloud_upload</span>
            <div class="text-center">
                <p class="font-label-md text-on-surface">HAZ CLIC AQUÍ O ARRASTRA EL ARCHIVO – EVIDENCIAS PARA SUBIRLO</p>
                <b><p style="color: #f50555;" class="text-body-sm text-on-surface-variant opacity-100">Solo Soporta el formato PDF (Máx 3 mb)</p></b>
            </form>


        </div>



    </div>
</div>
<!-- Section III: Autorización -->
<div class="flex flex-col gap-lg">
    <div class="border-l-4 border-primary pl-md py-sm">
        <h2 class="font-headline-sm text-headline-sm text-on-surface uppercase tracking-wide">
            III. AUTORIZO LA NOTIFICACIÓN DEL RESULTADO AL REPORTE REALIZADO
        </h2>
    </div>
    <label class="flex items-start gap-md cursor-pointer group">
        <div class="relative flex items-center">
            <input class="w-6 h-6 rounded border-outline-variant text-primary focus:ring-primary cursor-pointer" type="checkbox" id="not_reclamo">
        </div>
        <span class="font-body-sm text-on-surface-variant">
            Autorizo la notificación del resultado al reporte realizado y declaro que toda la información proporcionada es verídica.
        </span>
    </label>
</div><div class="flex flex-col md:flex-row justify-between items-center gap-md pt-lg border-t border-outline-variant">
    <p class="font-body-sm text-body-sm text-on-surface-variant max-w-sm text-center md:text-left italic">
        * Al continuar, usted autoriza el tratamiento de estos datos para fines institucionales y de seguimiento según el tipo de notificación.
    </p>

    <a id="btn_guardar" style="cursor: pointer;" class="w-full md:w-auto h-12 px-xl bg-primary text-on-primary font-label-md rounded-full hover:bg-primary-container transition-all active:scale-95 shadow-lg flex items-center justify-center gap-sm" type="submit"><span class="">ENVIAR</span>
        <span class="material-symbols-outlined">send</span>
    </a>

    </div>
</div>
<!-- Simplified Public Footer -->
<footer class="mt-lg flex flex-col md:flex-row justify-center items-center gap-gutter opacity-60 w-full">
    <p class="text-label-sm font-label-sm">© OADI - HNDAC</p>

</footer>
</div>
</main>

@endsection



@push('scripts')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- JavaScript de Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/min/dropzone.min.js"></script>


<script>
    const saveUrl = "{{ route('buzon_incidentes_reclamo.save') }}";
    const obtenerRazonRucUrl = "{{ route('buzon_incidentes_reclamo.consultarRuc') }}";
    const dropzoneSubir = "{{ route('buzon_incidentes_reclamo.DZsubirAdjunto') }}";
    const dropzoneEliminar = "{{ route('buzon_incidentes_reclamo.DZeliminarAdjunto') }}";
</script>

<script src="{{ asset('assets/js/buzon_incidentes_reclamo.js') }}"></script>
@endpush

