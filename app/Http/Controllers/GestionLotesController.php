<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GestionLotesController extends Controller
{

    function index (){
        
        $laboratorios = DB::select('CALL obtener_laboratorios()');
        return view('gestionLotes', compact('laboratorios'));
    }

    function obtenerArticulos($laboratorio_id){
        $articulos = DB::select('CALL obtener_articulos(?)', array($laboratorio_id));
        return response()->json($articulos);
    }
   
}
