<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\NullsafeMethodCall;

class ExcelImportController extends Controller
{
    protected $valor;

    public function subirExcel(Request $request)
    {
        ignore_user_abort(false);
        ini_set('max_execution_time', 0);

        DB::statement("CALL sp_truncar_tbl_excel_temp()");

        $archivo = $request->file('archivo_excel');
$spreadsheet = IOFactory::load($archivo);
$hoja = $spreadsheet->getActiveSheet();

$headers = [];
foreach ($hoja->getRowIterator() as $index => $fila) {
    $celdas = $fila->getCellIterator();
    $celdas->setIterateOnlyExistingCells(false);

    $valores = [];
    foreach ($celdas as $celda) {
        $valores[] = trim((string) $celda->getValue());
    }

    // La primera fila es la cabecera
    if ($index === 1) {
        foreach ($valores as $colIndex => $valor) {
            if (!empty($valor) && $colIndex < 15) {
                // Guardamos hasta 15 columnas con cabecera
                $headers[$colIndex] = $valor;
            }
        }
    }

    // Saltar filas totalmente vacías
    if (count(array_filter($valores)) === 0) {
        continue;
    }

    // Crear arreglo de 15 columnas máximo
    $filaData = [];
    for ($i = 0; $i < 15; $i++) {
        $filaData[$i] = $valores[$i] ?? null;
    }

    // Llamar al SP con 15 parámetros fijos
    DB::statement("CALL sp_insertar_tbl_excel_temp(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [
        $filaData[0],
        $filaData[1],
        $filaData[2],
        $filaData[3],
        $filaData[4],
        $filaData[5],
        $filaData[6],
        $filaData[7],
        $filaData[8],
        $filaData[9],
        $filaData[10],
        $filaData[11],
        $filaData[12],
        $filaData[13],
        $filaData[14],
    ]);
}


        return back()
            ->with('success', 'Excel procesado correctamente.')
            ->with('abrir_modal', true);
    }

    public function listaLaboratoriosExcel(Request $request)
    {

        $laboratorios = DB::select('CALL sp_obtener_laboratorios_excel()');
        return response()->json($laboratorios);
    }

    public function guardarConfiguracion(Request $request)
    {
        $configuraciones = $request->input('configuraciones');

        foreach ($configuraciones as $config) {
            DB::statement('CALL sp_guardar_config_laboratorio(?, ?,?)', [
                $config['laboratorio'],
                $config['porcentaje'],
                $config['operacion']
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function ListarArticulosExcel(Request $request)
    {
        $laboratorio_id = $request->input('laboratorio_id');
        $articulos = DB::select('CALL sp_obtener_articulos_excel(?)', [$laboratorio_id]);
        return response()->json($articulos);
    }

    public function ListarDatosArticulo(Request $request)
    {
        $articulo_id = $request->input('articulo_id');
        $laboratorio_id = $request->input('laboratorio_id');
        $datos = DB::select('CALL sp_obtener_datos_articulo_excel(?,?)', [$laboratorio_id, $articulo_id]);
        return response()->json($datos);
    }
    // visor normal
    public function ListarDatosArticuloVisualizar(Request $request)
    {
        $articulo_id = $request->input('articulo_id');
        $laboratorio_id = $request->input('laboratorio_id');
        $datos = DB::select('CALL sp_obtener_datos_articulo_excel_rol_visualizar(?,?)', [$laboratorio_id, $articulo_id]);
        return response()->json($datos);
    }

    public function ListarDatosArticuloAll(Request $request)
    {
        $laboratorio_id = $request->input('laboratorio_id');
        $datos = DB::select('CALL sp_obtener_datos_articulo_excel_all(?)', [$laboratorio_id]);
        return response()->json($datos);
    }


    public function ListarDatosArticuloVisualizarAll(Request $request)
    {
        $laboratorio_id = $request->input('laboratorio_id');
        $datos = DB::select('CALL sp_obtener_datos_articulo_excel_rol_visualizar_all(?)', [$laboratorio_id]);
        return response()->json($datos);
    }

    public function ListarDatosArticuloAllSF(Request $request)
    {
        $laboratorio_id = $request->input('laboratorio_id');
        $datos = DB::select('CALL sp_obtener_datos_articulo_excel_sin_filtro_lab(?)', [$laboratorio_id]);
        return response()->json($datos);
    }

    public function ListarDatosArticuloVisualizarSF(Request $request)
    {
        $laboratorio_id = $request->input('laboratorio_id');
        $datos = DB::select('CALL sp_obtener_datos_articulo_excel_rol_visualizar_sin_filtro_lab(?)', [$laboratorio_id]);
        return response()->json($datos);
    }

    public function listaArticulosExcelSF(Request $request)
    {
        $q = $request->get('q', '');
        $datos = DB::select('CALL sp_listar_articulos_sin_filtro_lab(?)', [$q]);
        return response()->json($datos);
    }
}
