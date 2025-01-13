<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubirArchivoController;
use App\Http\Controllers\ArchivoMostrarController;
use App\Http\Controllers\DescargarArchivoController;
use App\Http\Controllers\AutenticacionController;
use App\Http\Controllers\GestionLotesController;

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
Route::post('/login', [AutenticacionController::class, 'login'])->name('login');

Route::get('/GestionLotes', [GestionLotesController::class, 'index'])->name('GestionLotes');


Route::get('/GestionLotesLaboratorio/{laboratorio_id}', [GestionLotesController::class, 'obtenerArticulos'])->name('GestionLotesLaboratorio');
Route::get('/GestionLotesArticulo/{articulo_id}', [GestionLotesController::class, 'obtenerLotes'])->name('GestionLotesArticulo');
Route::match(['get', 'post'], 'AgregarObtenerLote', [GestionLotesController::class, 'agregarObtenerLote']);

Route::post('/Subir', [SubirArchivoController::class, 'Guardar'])->name('archivo.cargar');
Route::get('/VerArchivo/{fileName}', [ArchivoMostrarController::class, 'mostrarArchivo'])->name('archivo.mostrar');
Route::get('/VerArchivo/{fileName}', [DescargarArchivoController::class, 'descargarArchivo'])->name('descargar.archivo');
