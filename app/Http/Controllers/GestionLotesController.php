<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;


class GestionLotesController extends Controller
{

    function index()
    {

        $laboratorios = DB::select('CALL obtener_laboratorios()');

        $rol = Session::get('rol');

        $desactivar = session('rol') !== '002';

        return view('gestionLotes', compact('desactivar', 'laboratorios'));
    }

    function obtenerArticulos($laboratorio_id)
    {
        $articulos = DB::select('CALL obtener_articulos(?)', array($laboratorio_id));
        return response()->json($articulos);
    }

    function obtenerLotes($articuloId)
    {
        $lotes = DB::select('CALL obtener_lotes(?)', [$articuloId]);

        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = array_slice($lotes, ($currentPage - 1) * $perPage, $perPage);

        $paginatedArticulos = new LengthAwarePaginator(
            $currentItems,
            count($lotes),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return response()->json($paginatedArticulos);
    }

    function agregarObtenerLote(Request $request)
    {
        try {
            // Llamada al procedimiento almacenado o inserción directa
           
            $result = DB::select('CALL agregar_obtener_lote(?, ?,?,?)', [
                $request->input('lote_id'),
                $request->input('almacen'),
                $request->input('fecha_creacion'),
                $request->input('articulo_id')
            ]);
        
            if ( $result[0]->validacion == '0') {
                return response()->json(['mensaje' => '1']);

            } else {
                return response()->json(['mensaje' => '0',
                'articulo' => $result[0]->articulo,
                'laboratorio' => $result[0]->laboratorio]); // Usa null si no está definido]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 500);
        }
    }

    function eliminarLote( $loteId)
    {
        try {
    
           
            $result = DB::select('CALL eliminar_lote(?)', [
                $loteId
            ]);
        
                return response()->json(['mensaje' => 'Lote eliminado exitosamente.']);

        } catch (\Exception $e) {
            return response()->json(['error' => $e], 500);
        }
    }
}
