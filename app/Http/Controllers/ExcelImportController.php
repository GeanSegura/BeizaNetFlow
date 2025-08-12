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

        foreach ($hoja->getRowIterator() as $index => $fila) {
            $celdas = $fila->getCellIterator();
            $celdas->setIterateOnlyExistingCells(false);

            $valores = [];
            foreach ($celdas as $celda) {
                $valores[] = trim((string) $celda->getValue());
            }

            // Saltar cabecera
            if ($index === 1) continue;

            for ($i = 0; $i <= 13; $i++) {
                if (!isset($valores[$i])) {
                    $valores[$i] = null;
                }
            }

            // Validar campos necesarios
            if (empty($valores[0]) || empty($valores[1])) continue;

            $id_articulo                  = $valores[0];
            $articulo                     = $valores[1];
            $precio_lista                 = is_numeric($valores[2]) ? $valores[2] : null;
            $prec_list_sin_igv_pl1        = is_numeric($valores[3]) ? $valores[3] : null;
            $precio_contado               = is_numeric($valores[4]) ? $valores[4] : null;
            $prec_list_sin_igv_pl2        = is_numeric($valores[5]) ? $valores[5] : null;
            $id_laboratorio               = $valores[6];
            $laboratorio                  = $valores[7];
            $stock                        = is_numeric($valores[8]) ? $valores[8] : null;
            $costo_proveedor              = is_numeric($valores[9]) ? $valores[9] : null;
            $costo_proveedor_con_igv      = is_numeric($valores[10]) ? $valores[10] : null;
            $adicional1                   = is_numeric($valores[11]) ? $valores[11] : null;
            $adicional2                   = is_numeric($valores[12]) ? $valores[12] : null;

            DB::statement("CALL sp_insertar_tbl_excel_temp(?, ?, ?, ?, ?, ?,?,?,?,?,?,?,?,?,?,?,?)", [
                $id_articulo,
                $articulo,
                $precio_lista,
                $prec_list_sin_igv_pl1,
                $precio_contado,
                $prec_list_sin_igv_pl2,
                $id_laboratorio,
                $laboratorio,
                $stock,
                $costo_proveedor,
                $costo_proveedor_con_igv,
                $adicional1,
                $adicional2,
                null,
                null,
                null,
                null
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

     public function ListarDatosArticuloAll(Request $request)
    {
        $laboratorio_id = $request->input('laboratorio_id');
        $datos = DB::select('CALL sp_obtener_datos_articulo_excel_all(?)', [$laboratorio_id]);
        return response()->json($datos);
    }

    public function ListarDatosArticuloVisualizar(Request $request)
    {
        $articulo_id = $request->input('articulo_id');
        $laboratorio_id = $request->input('laboratorio_id');
        $datos = DB::select('CALL sp_obtener_datos_articulo_excel_rol_visualizar(?,?)', [$laboratorio_id, $articulo_id]);
        return response()->json($datos);
    }

     public function ListarDatosArticuloVisualizarAll(Request $request)
    {
        $laboratorio_id = $request->input('laboratorio_id');
        $datos = DB::select('CALL sp_obtener_datos_articulo_excel_rol_visualizar_all(?)', [$laboratorio_id]);
        return response()->json($datos);
    }
}
