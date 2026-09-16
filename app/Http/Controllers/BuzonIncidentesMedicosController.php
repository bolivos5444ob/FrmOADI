<?php

namespace App\Http\Controllers;

use App\Models\BuzonIncidentesReclamos;
use App\Models\Correlativo;
use App\Models\Tipodocumento;
use App\Models\TipoReporte;
use App\Models\TipoUsuario;
use App\Models\Servicios;
use App\Models\Universidad;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\SolicitudRegistradaMail;
use App\Mail\SolicitudCiudadanoMail;
use Illuminate\Support\Facades\DB;
use setasign\Fpdi\Fpdi;



class BuzonIncidentesMedicosController extends Controller
{   

    
    public function index()
    {   
        $servicios = DB::select("
            select * from Servicios
            where IdServicio in (1,
            2,3,4,5,6,7,11,12,13,26,28,29,
            30,51,53,55,56,72,73,87,91,335,336,337,
            424,800,829,1319)");

        $tipoUsuarios = DB::connection('sqlsrv_externa')
        ->select('Select * from BIM_TipoUsuario');

        $universidades = DB::connection('sqlsrv_externa')
        ->select('Select * from BIM_Universidad');

        return view('buzon_incidentes_reclamo/index', [
            'servicios' => $servicios,
            'universidades' => $universidades,
            'tipoUsuarios' => $tipoUsuarios,
        ]);
    }


    public function DZeliminarAdjunto(Request $request)
    {
        $nombre = $request->input('nombre');
        $modo = $request->input('modo');
        $carpeta = '';

        if ($modo == 1) { //Formulario Libro Reclamacion HNDAC
            
            $carpeta = '/evidencia/';
        }
        else if ($modo == 2) { //Respuesta para registro de Libro Reclamacion HNDAC
            
            $carpeta = '/respuesta/';
        }
        else if ($modo == 3) { //Respuesta para registro de Libro Reclamacion HNDAC
            
            $carpeta = '/login/';
        }

        $baseSisadm = base_path('../adjuntos');
        $ruta = $baseSisadm. $carpeta . $nombre;

        if (file_exists($ruta)) {
            unlink($ruta);
            return response()->json(['success' => true, 'mensaje' => 'Archivo eliminado.']);
        }

        return response()->json(['success' => false, 'mensaje' => 'Archivo no encontrado.'], 404);
    }


    public function DZsubirAdjunto(Request $request)
    {   

        // \Log::info('Intentando subir archivo. Modo: ' . $request->input('modo'));

        try {
            $modo = $request->input('modo');
            $carpeta = '';
            $rutaAdjunto = null;

            if ($modo == 1) { //Formulario Libro Reclamacion HNDAC
                $carpeta = '/evidencia/';
            }
            else if ($modo == 2) { //Respuesta para registro de Libro Reclamacion HNDAC
                $carpeta = '/respuesta/';
            }

            if ($request->hasFile('file')) {

                $file = $request->file('file');
                $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                $relativePath = $carpeta . $filename; // SIN "public/"

                $baseSisadm = base_path('../adjuntos'); // Ruta absoluta al proyecto sisadm
                $destinationPath = $baseSisadm . $relativePath;
                $rutaAdjunto = 'adjuntos' . $relativePath; // ruta db

                $dir = dirname($destinationPath);
                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }

                $file->move($dir, $filename);
     
                return response()->json([
                    'success' => true,
                    'ruta' => $rutaAdjunto,
                    'nombre' => $filename
                ]);
            }

            return response()->json(['success' => false, 'message' => 'No se envió archivo'], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'archivo' => $e->getFile(),
                'linea' => $e->getLine()
            ], 200); // Ponemos código 200 temporalmente para que el navegador sí lo muestre en texto y no lo oculte
        }
    }


    
    private function generarPDFyGuardar($reclamo, $rutaPDF)
    {
        
        $notificacion = 'NO';

        if ($reclamo->not_reclamo == 1) {
            $notificacion = 'SI';
        }

        $pdf = new Fpdi();
        $pdf->AddPage();

        $pdf->SetTitle(utf8_decode('HNDAC_'.$reclamo->correlativo));

        $pdf->Image(storage_path('app/public/imagenes/logo_hdnac_gob.jpg'), 15, 10, 50);
        $pdf->Image(storage_path('app/public/imagenes/logo_susalud.jpg'), 155, 10, 35);

        $pdf->SetFont('Arial', 'B', 12);

        $pdf->SetXY(50, 22);
        $pdf->Cell(110, 6, utf8_decode('BUZÓN DE NOTIFICACIÓN DE INCIDENTES,'), 0, 1, 'C');

        $pdf->SetXY(50, 28);
        $pdf->Cell(110, 6, utf8_decode('RECLAMOS Y SUGERENCIAS DE LOS MÉDICOS RESIDENTES'), 0, 1, 'C');

        //marco Ipress y hospital
        $pdf->Rect(15, 40, 105, 13);

        $pdf->Ln(10);

        $pdf->SetFont('Arial', 'B', 7.5);
        $pdf->SetXY(15, 40);

        $pdf->Cell(0, 7, utf8_decode('Nombre de la IPRESS:      HOSPITAL NACIONAL DANIEL ALCIDES CARRIÓN'), 0, 1);
        $pdf->SetXY(15, 46);
        $pdf->Cell(0, 7, utf8_decode('Dirección del establecimiento:        Av. Guardia Chalaca 2176, Bellavista - Callao'), 0, 1);
        

        //Correlativo
        $pdf->Rect(150, 40, 50, 13);

        $pdf->SetXY(150, 40);
        $pdf->Cell(0, 7, utf8_decode('BUZÓN DE NOTIFICACIÓN'), 0, 1);

        $pdf->SetXY(150, 46);
        $pdf->Cell(0, 7, utf8_decode($reclamo->correlativo), 0, 1);


        //fecha
        $pdf->Rect(15, 57, 26, 7);

        $pdf->SetXY(15, 57);
        $pdf->Cell(0, 7, utf8_decode('Fecha: ' . Carbon::parse($reclamo->fecha)->format('d/m/Y')), 0, 1);


        // Sección 1
        $pdf->Rect(15,75, 186, 41);
        $pdf->SetXY(15, 67);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(0, 7, utf8_decode('1. IDENTIFICACIÓN DEL MÉDICO RESIDENTE'), 0, 1);


        $pdf->SetFont('Arial', 'B', 7.5);
        $pdf->SetXY(15, 75);
        $pdf->Cell(0, 7, utf8_decode('Tipo de Usuario:'), 0, 1);

        $pdf->SetFont('Arial', '', 7.5);
        $pdf->SetXY(15, 80);

        $tipoUsuario = TipoUsuario::find($reclamo->id_tipo_usuario);
        $pdf->Cell(0, 7, utf8_decode($tipoUsuario->descripcion), 0, 1);


        $pdf->SetFont('Arial', 'B', 7.5);
        $pdf->SetXY(15, 85);
        $pdf->Cell(0, 7, utf8_decode('Nombres y Apellidos:'), 0, 1);

        $pdf->SetFont('Arial', '', 7.5);
        $pdf->SetXY(15, 90);
        $pdf->Cell(0, 7, utf8_decode($reclamo->nombres_apellidos), 0, 1);


        $pdf->SetFont('Arial', 'B', 7.5);
        $pdf->SetXY(15, 95);
        $pdf->Cell(0, 7, utf8_decode('Servicio de Rotación:'), 0, 1);

        $pdf->SetFont('Arial', '', 7.5);
        $pdf->SetXY(15, 100);

        $servicio = Servicios::find($reclamo->id_servicio);
        $pdf->Cell(0, 7, utf8_decode($servicio->Codigo .' = '. $servicio->Nombre), 0, 1);

        $pdf->SetFont('Arial', 'B', 7.5);
        $pdf->SetXY(15, 105);
        $pdf->Cell(0, 7, utf8_decode('Tipo de Reporte:'), 0, 1);

        $pdf->SetFont('Arial', '', 7.5);
        $pdf->SetXY(15, 110);

        $tipoReporte = TipoReporte::find($reclamo->id_reporte);
        $pdf->Cell(0, 7, utf8_decode($tipoReporte->descripcion), 0, 1);



        $pdf->SetFont('Arial', 'B', 7.5);
        $pdf->SetXY(120, 75);
        $pdf->Cell(0, 7, utf8_decode('EMAIL:'), 0, 1);

        $pdf->SetFont('Arial', '', 7.5);
        $pdf->SetXY(120, 80);
        $pdf->Cell(0, 7, utf8_decode($reclamo->email), 0, 1);

        $pdf->SetFont('Arial', 'B', 7.5);
        $pdf->SetXY(120, 85);
        $pdf->Cell(0, 7, utf8_decode('Teléfono Fijo Y/O Celular:'), 0, 1);

        $pdf->SetFont('Arial', '', 7.5);
        $pdf->SetXY(120, 90);
        $pdf->Cell(0, 7, utf8_decode($reclamo->telefono), 0, 1);


        $pdf->SetFont('Arial', 'B', 7.5);
        $pdf->SetXY(120, 95);
        $pdf->Cell(0, 7, utf8_decode('N° Documento:'), 0, 1);

        $pdf->SetFont('Arial', '', 7.5);
        $pdf->SetXY(120, 100);
        $pdf->Cell(0, 7, utf8_decode($reclamo->numero_documento), 0, 1);

        $pdf->SetFont('Arial', 'B', 7.5);
        $pdf->SetXY(120, 105);

        $universidad = Universidad::find($reclamo->id_universidad);
        $pdf->Cell(0, 7, utf8_decode('Universidad:'), 0, 1);

        $pdf->SetFont('Arial', '', 7.5);
        $pdf->SetXY(120, 110);
        $pdf->Cell(0, 7, utf8_decode($universidad->descripcion), 0, 1);


        // Sección 3
        $pdf->SetXY(15, 122);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(0, 7, utf8_decode('2. REDACTAR SEGÚN TIPO DE REPORTE'), 0, 1);

        $descripcion = $reclamo->detalle_reporte;

        // Asegura que los saltos de línea funcionen en MultiCell
        $descripcion = str_replace(["\r\n", "\r"], "\n", $descripcion);


        if ($reclamo->ruta_evidencia != null) {
            $descripcion .= "\n" . '(Se adjuntó documento de evidencia.)';
        }
        // Si usas tildes o eñes
        $descripcion = utf8_decode($descripcion);

        $pdf->SetXY(15, 130);
        $pdf->MultiCell(136, 6, $descripcion, 1);


        $x = $pdf->GetX();
        $y = $pdf->GetY();

        // Sección 4
        $pdf->SetXY($x+5, $y+3);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(0, 7, utf8_decode('3. AUTORIZO LA NOTIFICACIÓN DEL RESULTADO AL REPORTE REALIZADO:        '.$notificacion), 0, 1);

        /// Guardar el archivo en disco
        $pdf->Output('F', $rutaPDF);
    }


    public function save(Request $request)
    {

        try {

            // GUARDAR IMAGEN
            $pathImagen = null;
            $pdfPath = null;

            //AUMENTAR CORRELATIVO
            if ($request->id_tipo_usuario == 1) {
                $idCorrelativo = 3;
            }
            else{
                $idCorrelativo = 5;
            }
            
            $correlativo = Correlativo::find($idCorrelativo);//buzon de incidencias medicas
            $correlativo->numero_correlativo  += 1;
            
            $numero = str_pad($correlativo->numero_correlativo, 11, '0', STR_PAD_LEFT);
            $numero = $correlativo->siglas.'-'.$numero;
            $correlativo->save();


            //CREAR REGISTRO EN BUZON DE INCIDENCIAS MEDICAS
            $buzon = new BuzonIncidentesReclamos();
            $buzon->correlativo = $numero;
            $buzon->fecha = isset($request->fecha) ? Carbon::parse($request->fecha)->format('Y-m-d') : null;
            $buzon->hora = isset($request->hora) ? substr($request->hora, 0, 5) : null;
            $buzon->numero_documento = $request->numero_documento;
            $buzon->nombres_apellidos = $request->nombres_apellidos;
            $buzon->email = $request->email;
            $buzon->telefono = $request->telefono;
            $buzon->id_servicio = $request->id_servicio;
            $buzon->id_universidad = $request->id_universidad;
            $buzon->id_reporte = $request->id_reporte;
            $buzon->detalle_reporte = $request->detalle_reporte;
            $buzon->not_reclamo = $request->not_reclamo;
            $buzon->ruta_evidencia = $request->ruta_evidencia;
            $buzon->id_tipo_usuario = $request->id_tipo_usuario;
            $buzon->fecha_creacion = DB::raw('GETDATE()');

            $buzon->save();
            
            //ENVIAR EMAIL -----------------------------------------------
            //$correo_principal = 'paus@hndac.gob.pe';
            $correo_principal = 'bolivos@hndac.gob.pe';
            $correo_adicional = $buzon->email;

            // Ruta donde se guardará el PDF temporalmente
            $pdfPath = storage_path('app/public/reportes/BUZON-'.$buzon->correlativo . '.pdf');

            // Llama a una función que genere y guarde el PDF
            $this->generarPDFyGuardar($buzon,$pdfPath);

            // Enviar el correo con el PDF e imagen adjunto
            Mail::to($correo_principal)
                ->send(new SolicitudRegistradaMail($buzon,$pdfPath,$pathImagen));

            // Enviar al ciudadano (sin adjuntos, solo mensaje y landing page)
            Mail::to($correo_adicional)
                ->send(new SolicitudCiudadanoMail($buzon));

            return response()->json(['success' => true, 'correlativo' => $buzon->correlativo]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error al guardar: ' .$pdfPath. '--  '. $pathImagen. ' -- '. $e->getMessage()
                //'correlativo' => $buzon->correlativo
            ], 500);
        }
    }


    // public function generarPDF()
    // {   

    //     $reclamo = BuzonIncidentesReclamos::findOrFail($id);

    //     $notificacion = 'NO';

    //     if ($reclamo->notificacion == 1) {
    //         $notificacion = 'SI';
    //     }
        

    //     $pdf = new Fpdi();
    //     $pdf->AddPage();

    //     $pdf->SetTitle(utf8_decode('HNDAC_'.$reclamo->correlativo));

    //     // Logos y titulo
    //     $pdf->Image(storage_path('app/public/imagenes/logo_hdnac_gob.jpg'), 15, 10, 50);
    //     $pdf->SetFont('Arial', 'B', 12);
    //     $pdf->Cell(0, 9, utf8_decode('Libro de Reclamación'), 0, 1, 'C');

    //     $pdf->Image(storage_path('app/public/imagenes/logo_susalud.jpg'), 155, 10, 35);
    //     $pdf->SetFont('Arial', 'B', 14);


    //     //marco Ipress y hospital
    //     $pdf->Rect(15, 30, 105, 13);

    //     $pdf->Ln(10);

    //     $pdf->SetFont('Arial', 'B', 7.5);
    //     $pdf->SetXY(15, 30);

    //     $pdf->Cell(0, 7, utf8_decode('Nombre de la IPRESS:      HOSPITAL NACIONAL DANIEL ALCIDES CARRIÓN'), 0, 1);
    //     $pdf->SetXY(15, 36);
    //     $pdf->Cell(0, 7, utf8_decode('Dirección del establecimiento:        Av. Guardia Chalaca 2176, Bellavista - Callao'), 0, 1);
        

    //     //Correlativo
    //     $pdf->Rect(150, 30, 50, 13);

    //     $pdf->SetXY(150, 30);
    //     $pdf->Cell(0, 7, utf8_decode('HOJA DE RECLAMACIÓN EN SALUD'), 0, 1);

    //     $pdf->SetXY(150, 36);
    //     $pdf->Cell(0, 7, utf8_decode($reclamo->correlativo), 0, 1);


    //     //fecha
    //     $pdf->Rect(15, 47, 26, 7);

    //     $pdf->SetXY(15, 47);
    //     $pdf->Cell(0, 7, utf8_decode('Fecha: ' .$reclamo->fecha->format('d/m/Y')), 0, 1);


    //     // Sección 1
    //     $pdf->Rect(15, 65, 186, 31);
    //     $pdf->SetXY(15, 57);
    //     $pdf->SetFont('Arial', 'B', 8);
    //     $pdf->Cell(0, 7, utf8_decode('1. IDENTIFICACIÓN DEL USUARIO O TERCERO LEGITIMADO'), 0, 1);


    //     $pdf->SetFont('Arial', 'B', 7.5);
    //     $pdf->SetXY(15, 65);
    //     $pdf->Cell(0, 7, utf8_decode('Nombre o Razón Social:'), 0, 1);

    //     $pdf->SetFont('Arial', '', 7.5);
    //     $pdf->SetXY(15, 70);
    //     $pdf->Cell(0, 7, utf8_decode($reclamo->nombre_rz), 0, 1);


    //     $pdf->SetFont('Arial', 'B', 7.5);
    //     $pdf->SetXY(15, 75);
    //     $pdf->Cell(0, 7, utf8_decode('Domicilio o Referencia:'), 0, 1);

    //     $pdf->SetFont('Arial', '', 7.5);
    //     $pdf->SetXY(15, 80);
    //     $pdf->Cell(0, 7, utf8_decode($reclamo->domicilio), 0, 1);


    //     $pdf->SetFont('Arial', 'B', 7.5);
    //     $pdf->SetXY(15, 85);
    //     $pdf->Cell(0, 7, utf8_decode('Documento de Identidad:'), 0, 1);

    //     $pdf->SetFont('Arial', '', 7.5);
    //     $pdf->SetXY(15, 90);
    //     //$pdf->Cell(0, 7, utf8_decode($tip_documento_1->siglas), 0, 1);


    //     $pdf->SetFont('Arial', 'B', 7.5);
    //     $pdf->SetXY(120, 65);
    //     $pdf->Cell(0, 7, utf8_decode('EMAIL:'), 0, 1);

    //     $pdf->SetFont('Arial', '', 7.5);
    //     $pdf->SetXY(120, 70);
    //     $pdf->Cell(0, 7, utf8_decode($reclamo->email), 0, 1);

    //     $pdf->SetFont('Arial', 'B', 7.5);
    //     $pdf->SetXY(120, 75);
    //     $pdf->Cell(0, 7, utf8_decode('Teléfono Fijo Y/O Celular:'), 0, 1);

    //     $pdf->SetFont('Arial', '', 7.5);
    //     $pdf->SetXY(120, 80);
    //     $pdf->Cell(0, 7, utf8_decode($reclamo->telefono), 0, 1);


    //     $pdf->SetFont('Arial', 'B', 7.5);
    //     $pdf->SetXY(120, 85);
    //     $pdf->Cell(0, 7, utf8_decode('N° Documento:'), 0, 1);

    //     $pdf->SetFont('Arial', '', 7.5);
    //     $pdf->SetXY(120, 90);
    //     $pdf->Cell(0, 7, utf8_decode($reclamo->numero_documento), 0, 1);




    //     // Sección 2
    //     $pdf->Rect(15, 108, 186, 31);
    //     $pdf->SetXY(15, 100);
    //     $pdf->SetFont('Arial', 'B', 8);
    //     $pdf->Cell(0, 7, utf8_decode('2. IDENTIFICACIÓN DE QUIEN PRESENTA EL RECLAMO (En caso de ser el usuario afectado no es necesario su llenado)'), 0, 1);


    //     $pdf->SetFont('Arial', 'B', 7.5);
    //     $pdf->SetXY(15, 108);
    //     $pdf->Cell(0, 7, utf8_decode('Nombre o Razón Social:'), 0, 1);

    //     $pdf->SetFont('Arial', '', 7.5);
    //     $pdf->SetXY(15, 113);
    //     $pdf->Cell(0, 7, utf8_decode($reclamo->nombre_rz_2), 0, 1);


    //     $pdf->SetFont('Arial', 'B', 7.5);
    //     $pdf->SetXY(15, 118);
    //     $pdf->Cell(0, 7, utf8_decode('Domicilio o Referencia:'), 0, 1);

    //     $pdf->SetFont('Arial', '', 7.5);
    //     $pdf->SetXY(15, 123);
    //     $pdf->Cell(0, 7, utf8_decode($reclamo->domicilio_2), 0, 1);


    //     $pdf->SetFont('Arial', 'B', 7.5);
    //     $pdf->SetXY(15, 128);
    //     $pdf->Cell(0, 7, utf8_decode('Documento de Identidad:'), 0, 1);

    //     $pdf->SetFont('Arial', '', 7.5);
    //     $pdf->SetXY(15, 133);
    //     //$pdf->Cell(0, 7, utf8_decode($tip_documento_2), 0, 1);


    //     $pdf->SetFont('Arial', 'B', 7.5);
    //     $pdf->SetXY(120, 108);
    //     $pdf->Cell(0, 7, utf8_decode('EMAIL:'), 0, 1);

    //     $pdf->SetFont('Arial', '', 7.5);
    //     $pdf->SetXY(120, 113);
    //     $pdf->Cell(0, 7, utf8_decode($reclamo->email_2), 0, 1);

    //     $pdf->SetFont('Arial', 'B', 7.5);
    //     $pdf->SetXY(120, 118);
    //     $pdf->Cell(0, 7, utf8_decode('Teléfono Fijo Y/O Celular:'), 0, 1);

    //     $pdf->SetFont('Arial', '', 7.5);
    //     $pdf->SetXY(120, 123);
    //     $pdf->Cell(0, 7, utf8_decode($reclamo->telefono_2), 0, 1);


    //     $pdf->SetFont('Arial', 'B', 7.5);
    //     $pdf->SetXY(120, 128);
    //     $pdf->Cell(0, 7, utf8_decode('N° Documento:'), 0, 1);

    //     $pdf->SetFont('Arial', '', 7.5);
    //     $pdf->SetXY(120, 133);
    //     $pdf->Cell(0, 7, utf8_decode($reclamo->numero_documento_2), 0, 1);



    //     // Sección 3
    //     $pdf->SetXY(15, 142);
    //     $pdf->SetFont('Arial', 'B', 8);
    //     $pdf->Cell(0, 7, utf8_decode('3. DETALLE DEL RECLAMO'), 0, 1);

    //     $descripcion = $reclamo->detalle_reclamo;

    //     // Asegura que los saltos de línea funcionen en MultiCell
    //     $descripcion = str_replace(["\r\n", "\r"], "\n", $descripcion);


    //     if ($reclamo->ruta_evidencia != null) {
    //         $descripcion .= "\n" . '(Se adjuntó documento de evidencia.)';
    //     }
    //     // Si usas tildes o eñes
    //     $descripcion = utf8_decode($descripcion);

    //     $pdf->SetXY(15, 150);
    //     $pdf->MultiCell(186, 6, $descripcion, 1);



    //     $x = $pdf->GetX();
    //     $y = $pdf->GetY();

    //     // Sección 4
    //     $pdf->SetXY($x+5, $y+3);
    //     $pdf->SetFont('Arial', 'B', 8);
    //     $pdf->Cell(0, 7, utf8_decode('4. AUTORIZO NOTIFICACIÓN DEL RESULTADO DEL RECLAMO AL EMAIL:        '.$notificacion), 0, 1);


    //     $pdf->Output('I', 'HNDAC_'.$reclamo->correlativo.'.pdf');

    //     /// Guardar el archivo en disco
    //     //$pdf->Output('F', $rutaPDF);
    // }

}














