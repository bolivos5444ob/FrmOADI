<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificación de Incidentes {{ $buzon->correlativo }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.5;
            color: #333;
            background-color: #e97e30;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 650px;
            margin: 20px auto;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #1a56a7;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .data-table th {
            text-align: left;
            padding: 8px;
            background-color: #f0f7ff;
            color: #1a56a7;
            font-size: 14px;
        }
        .data-table td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .highlight {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
            border-left: 3px solid #1a56a7;
        }
        .footer {
            text-align: center;
            padding: 15px;
            background-color: #f0f4f8;
            font-size: 12px;
            color: #718096;
        }
    </style>
</head>
<body>

	<h2>Notificación de Incidentes: {{ $buzon->correlativo }}</h2>
    <p>Tu notificación con número <strong>{{ $buzon->correlativo }}</strong> ha sido registrada con éxito.</p>
    <p>Lo atenderemos dentro de los 30 días hábiles.</p>
    <br>
    
    <div class="container">
        <div class="header">
            <h2>BUZON DE NOTIFICACION OADI {{ $buzon->correlativo }}</h2>
            <p>Hospital Nacional Daniel Alcides Carrión</p>
            <p>Av. Guardia Chalaca 2176, Bellavista - Callao</p>
        </div>

        <div class="content">

            <table class="data-table">
                <tr>
                    <th colspan="4">I. IDENTIFICACIÓN DEL INTERNO</th>
                </tr>

                <tr>
                    <td width="30%"><strong>Fecha:</strong></td>
                    <td>{{ date('d/m/Y', strtotime($buzon->fecha)) }}</td>
                </tr>

                <tr>
                    <td width="30%"><strong>Hora:</strong></td>
                    <td>{{ $buzon->hora }}</td>
                </tr>


                <tr>
                    <td width="30%"><strong>Nombres y apellidos:</strong></td>
                    <td colspan="4">{{ $buzon->nombres_apellidos }}</td>
                </tr>

                

                <tr>
                    <td width="30%"><strong>Tipo Usuario:</strong></td>
                    <td>{{ $buzon->tipoUsuario->descripcion }}</td>
                </tr>
                <tr>
                    
                    <td width="30%"><strong>N° Documento:</strong></td>
                    <td>{{ $buzon->numero_documento }}</td>
                </tr>


               <tr>
                    <td width="30%"><strong>Servicio de Rotación:</strong></td>
                    <td>{{ $buzon->servicios()->Nombre }}</td>
                </tr>
                <tr>
                    
                    <td width="30%"><strong>Universidad:</strong></td>
                    <td>{{ $buzon->universidad->descripcion }}</td>
                </tr>

                <tr>
                    <td width="30%"><strong>Teléfono:</strong></td>
                    <td>{{ $buzon->telefono }}</td>
                </tr>
                <tr>
                    
                    <td width="30%"><strong>Email:</strong></td>
                    <td>{{ $buzon->email }}</td>
                </tr>

                <tr>
                    
                    <td width="30%"><strong>Tipo de Reporte:</strong></td>
                    <td>{{ $buzon->tipoReporte->descripcion }}</td>
                </tr>

            </table>



            <table class="data-table">
                <tr>
                    <th colspan="2">II. REDACTAR SEGÚN TIPO DE REPORTE</th>
                </tr>
                <tr>
                    <td>
                        <p>{{ $buzon->detalle_reporte }}</p>
                    </td>
                </tr>
            </table>

            <table class="data-table">
                <tr>
                    <th colspan="2">IV. AUTORIZO NOTIFICACIÓN DEL RESULTADO AL REPORTE REALIZADO</th>
                </tr>
                <tr>
                    <td width="30%"><strong>Autorizo:</strong></td>
                    <td>{{ $buzon->not_reclamo == 1 ? 'SI' : 'NO' }}</td>
                </tr>
            </table>




            <p style="color: #666; font-size: 14px;">
                <strong>Nota:</strong> Esta reclamo requiere respuesta dentro del plazo legal establecido. Nos pondremos en contacto contigo a traves de los canales de contacto brindados luego de evaluar el requerimiento de tu solicitud.
            </p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }}  HNDAC OESI
        </div>
    </div>
</body>
</html>