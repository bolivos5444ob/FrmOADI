<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Libro de Reclamaciones</title>
</head>
<body>
    <h1>Reporte Libro de Reclamacionsses (Versión Web)</h1>

    <p><strong>Cliente:</strong> {{ $cliente }}</p>
    <p><strong>Descripción:</strong> {{ $descripcion }}</p>

    <h3>Imagen:</h3>
    <img src="{{ asset('storage/imagenes/' . $imagen) }}" alt="Imagen del reclamo" style="max-width: 300px;">
</body>
</html>