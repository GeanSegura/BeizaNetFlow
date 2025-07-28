<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\TmpExcel;

class ExcelImportController extends Controller
{
   public function subirExcel(Request $request)
{

    $request->validate([
        'archivo' => 'required|file|mimes:xlsx,xls,csv|max:102400'
    ]);

    dd($request->file('archivo'));

    TmpExcel::truncate();

    $datos = Excel::toArray([], $request->file('archivo'));
    $filas = $datos[0];

    if (count($filas) < 1) {
        return response()->json(['success' => false, 'message' => 'El archivo Excel no tiene datos.']);
    }

    $cabecera = array_map(function ($item) {
        return strtolower(str_replace([' ', '-', 'á', 'é', 'í', 'ó', 'ú'], ['_', '_', 'a', 'e', 'i', 'o', 'u'], trim($item)));
    }, $filas[0]);

    unset($filas[0]);

    foreach ($filas as $fila) {
        $filaAsociativa = array_combine($cabecera, $fila);

        TmpExcel::create([
            'id_articulo'     => $filaAsociativa['numero_de_articulo'] ?? '',
            'articulo'        => $filaAsociativa['descripcion_de_articulo'] ?? '',
            'laboratorio'     => $filaAsociativa['nombre_del_grupo_articulos'] ?? '',
            'stock'           => is_numeric($filaAsociativa['stock_actual'] ?? null) ? floatval($filaAsociativa['stock_actual']) : 0.00,
            'precio_minimo'   => is_numeric($filaAsociativa['precio_contado'] ?? null) ? floatval($filaAsociativa['precio_contado']) : 0.00,
            'precio_lista_1'  => is_numeric($filaAsociativa['precio_lista'] ?? null) ? floatval($filaAsociativa['precio_lista']) : 0.00,
            'precio_costo'    => is_numeric($filaAsociativa['precio_sin_igv_pl1'] ?? null) ? floatval($filaAsociativa['precio_sin_igv_pl1']) : 0.00,
            'porcentaje'      => 0.00
        ]);
    }

    return response()->json(['success' => true, 'message' => 'Archivo Excel importado correctamente']);
}
}

function safeFloat($value)
{
    return is_numeric($value) ? floatval($value) : 0.00;
}

