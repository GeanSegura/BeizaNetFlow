<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubirArchivoController;
use App\Http\Controllers\ArchivoMostrarController;
use App\Http\Controllers\DescargarArchivoController;
use App\Http\Controllers\AutenticacionController;
use App\Http\Controllers\EliminarArchivoBucketController;
use App\Http\Controllers\GestionLotesController;
use App\Http\Controllers\RegistrarController;
use App\Http\Controllers\AsitenteChatBotController;
use App\Http\Controllers\ExcelImportController;
use App\Http\Controllers\PrecioArticulosController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('login');
});

// Route::get('/login', [AutenticacionController::class, 'login'])->name('login');
Route::match(['get', 'post'],'/login', [AutenticacionController::class, 'login'])->name('login');
Route::match(['get', 'post'],'/registrarse', [AutenticacionController::class, 'registrar'])->name('registrar');

Route::get('/GestionLotes', [GestionLotesController::class, 'index'])->name('GestionLotes');
Route::get('/PrecioArticulos', [PrecioArticulosController::class, 'index'])->name('PrecioArticulos');

Route::get('/GestionLotesLaboratorio/{laboratorio_id}', [GestionLotesController::class, 'obtenerArticulos'])->name('GestionLotesLaboratorio');
Route::get('/GestionLotesArticulo/{articulo_id}', [GestionLotesController::class, 'obtenerLotes'])->name('GestionLotesArticulo');
Route::match(['get', 'post'], 'AgregarObtenerLote', [GestionLotesController::class, 'agregarObtenerLote']);
Route::match(['get', 'delete'], '/EliminarLote/{lote_id}', [GestionLotesController::class, 'eliminarLote']);

Route::post('/Subir', [SubirArchivoController::class, 'Guardar'])->name('archivo.cargar');
Route::match(['post', 'get'],'/asistenteChatBot', [AsitenteChatBotController::class, 'cargarChatBot']);
Route::get('/VerArchivo/{loteId}', [ArchivoMostrarController::class, 'mostrarArchivo'])->name('archivo.mostrar');
Route::get('/DescargarArchivo/{loteId}', [DescargarArchivoController::class, 'descargarArchivo'])->name('descargar.archivo');
Route::match(['post', 'get'],'/RegistrarUsuario', [RegistrarController::class, 'insertarUsuario']);
Route::match(['delete', 'get'],'/EliminarArchivoAWS/{loteId}', [EliminarArchivoBucketController::class, 'eliminarArchivoAWS']);

//LABORATORIOS EXCEL
Route::get('/lista-laboratorios-excel', [ExcelImportController::class, 'listaLaboratoriosExcel'])->name('listaLaboratoriosExcel');
Route::get('/ListarArticulosExcel', [ExcelImportController::class, 'ListarArticulosExcel'])->name('ListarArticulosExcel');
Route::get('/ListarDatosArticulo', [ExcelImportController::class, 'ListarDatosArticulo'])->name('ListarDatosArticulo');
Route::get('/ListarDatosArticuloAll', [ExcelImportController::class, 'ListarDatosArticuloAll'])->name('ListarDatosArticuloAll');
Route::get('/ListarDatosArticuloVisualizar', [ExcelImportController::class, 'ListarDatosArticuloVisualizar'])->name('ListarDatosArticuloVisualizar');
Route::get('/ListarDatosArticuloVisualizarAll', [ExcelImportController::class, 'ListarDatosArticuloVisualizarAll'])->name('ListarDatosArticuloVisualizarAll');
Route::post('/subir-excel', [ExcelImportController::class, 'subirExcel'])->name('subirExcel');
Route::post('/guardar-configuracion', [ExcelImportController::class, 'guardarConfiguracion'])->name('guardarConfiguracion');

// sin filtro
Route::get('/ListarDatosArticuloAllSF', [ExcelImportController::class, 'ListarDatosArticuloAllSF'])->name('ListarDatosArticuloAllSF');
Route::get('/ListarDatosArticuloVisualizarSF', [ExcelImportController::class, 'ListarDatosArticuloVisualizarSF'])->name('ListarDatosArticuloVisualizarSF');
Route::get('/listaArticulosExcelSF', [ExcelImportController::class, 'listaArticulosExcelSF'])->name('listaArticulosExcelSF');




