<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BuzonIncidentesMedicosController;
//use App\Http\Controllers\AccesoInformacionPublicaController;
//use App\Http\Controllers\ProvinciaController;
//use App\Http\Controllers\DistritoController;


//LIBRO DE RECLAMACIONES

// Página de inicio
Route::get('/', [BuzonIncidentesMedicosController::class, 'index'])->name('home');
// Ruta para mostrar el formulario
Route::get('/buzon_incidentes_reclamo/index', [BuzonIncidentesMedicosController::class, 'index'])->name('buzon_incidentes_reclamo.index');
Route::post('/buzon_incidentes_reclamo/consultarRuc', [BuzonIncidentesMedicosController::class, 'consultarRuc'])->name('buzon_incidentes_reclamo.consultarRuc');
Route::post('/buzon_incidentes_reclamo/DZsubirAdjunto', [BuzonIncidentesMedicosController::class, 'DZsubirAdjunto'])->name('buzon_incidentes_reclamo.DZsubirAdjunto');
Route::post('/buzon_incidentes_reclamo/DZeliminarAdjunto', [BuzonIncidentesMedicosController::class, 'DZeliminarAdjunto'])->name('buzon_incidentes_reclamo.DZeliminarAdjunto');
Route::post('/buzon_incidentes_reclamo/save', [BuzonIncidentesMedicosController::class, 'save'])->name('buzon_incidentes_reclamo.save');
Route::get('/buzon_incidentes_reclamo/reporte', [BuzonIncidentesMedicosController::class, 'mostrarReporte']);
Route::get('/buzon_incidentes_reclamo/pdf', [BuzonIncidentesMedicosController::class, 'generarPDF']);
