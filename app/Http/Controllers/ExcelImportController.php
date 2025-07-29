<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;

class ExcelImportController extends Controller
{
    public function subirExcel(Request $request)
    {

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

            // Validar campos necesarios
            if (empty($valores[0]) || empty($valores[1])) continue;

            $id_articulo     = $valores[0];
            $articulo        = $valores[1];
            $precio_lista_1  = is_numeric($valores[2]) ? floatval($valores[2]) : 0;
            $precio_costo    = is_numeric($valores[3]) ? floatval($valores[3]) : 0;
            $laboratorio     = $valores[6];
            $stock           = is_numeric($valores[8]) ? intval($valores[8]) : 0;

            DB::statement("CALL sp_insertar_tbl_excel_temp(?, ?, ?, ?, ?, ?)", [
                $id_articulo,
                $articulo,
                $precio_lista_1,
                $precio_costo,
                $laboratorio,
                $stock
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
            DB::statement('CALL sp_guardar_config_laboratorio(?, ?)', [
                $config['laboratorio'],
                $config['porcentaje']
            ]);
        }

        return response()->json(['success' => true]);
    }
}
