<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Notificación Registrada</title>
</head>
<body>
    <h2>NOTIFICACIÓN DE INCIDENTES Generada: {{ $libroreclamacion->correlativo }}</h2>
    <p>Se ha registrado una nueva Notificacion <strong>{{ $libroreclamacion->correlativo }}</strong> en el BUZÓN DE NOTIFICACIÓN DE INCIDENTES,
RECLAMOS Y SUGERENCIAS DE LOS MÉDICOS RESIDENTES</p>

    @if ($libroreclamacion->ruta_imagen != null)
        <p>Se adjunta el PDF del reclamo y el archivo de evidencia.</p>
    @else
        <p>Se adjunta el PDF del reclamo.</p>
    @endif

</body>
</html>