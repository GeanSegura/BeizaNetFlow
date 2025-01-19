<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class EliminarArchivoBucketController extends Controller
{
    public function eliminarArchivoAWS( $loteId)
    {

        $result = DB::select('CALL obtener_archivo_lote( ?)', [
            $loteId,
        ]);

        if (!empty($result[0]->resultado ?? null)) {
            $fileName = $result[0]->resultado;
        } else {
            $fileName = '';
        }
        // URL base de tu API Gateway
        $url = 'https://2w5hx6ly67.execute-api.sa-east-1.amazonaws.com/dev/beizanet-protocolos/' . $fileName;

        // Crear una instancia de Guzzle
        $client = new Client();

        try {

            $response = $client->delete($url);

            // Verificar la respuesta
            if ($response->getStatusCode() == 200) {

                $resultDelete = DB::select('CALL eliminar_lote_BD(?)', [
                    $loteId
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Archivo eliminado exitosamente.',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'La respuesta del servidor no fue exitosa. Código de estado: ' . $response->getStatusCode(),
                ], 500);
            }
        } catch (\Exception $e) {
            // Si ocurre un error
            return response()->json([
                'success' => false,
                'error' => 'Error al eliminar el archivo: ' . $e->getMessage(),
            ], 500);
        }
    }
}
