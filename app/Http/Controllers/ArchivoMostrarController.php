<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class ArchivoMostrarController extends Controller
{
    public function mostrarArchivo($loteId)
    {
        // URL base de tu API Gateway

        $result = DB::select('CALL obtener_archivo_lote( ?)', [
            $loteId,
        ]);


        if (!empty($result[0]->resultado ?? null)) {
            $fileName = $result[0]->resultado;
        } else {
            $fileName = '';
        }

        $baseUrl = 'https://2w5hx6ly67.execute-api.sa-east-1.amazonaws.com/dev/beizanet-protocolos/';

        // Construye la URL completa del archivo
        $url = $baseUrl . $fileName;

        // Instancia del cliente Guzzle
        $client = new Client();

        try {
            // Realiza la solicitud GET al archivo en S3
            $response = $client->get($url);
            // Verifica si la respuesta es exitosa
            if ($response->getStatusCode() === 200) {
                $contentType = $response->getHeader('Content-Type')[0]; // Tipo de contenido (PDF, imagen, etc.)
                $content = $response->getBody()->getContents(); // Contenido del archivo

                 return response($content, 200)
                 ->header('Content-Type', $contentType);
            }
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'error' => 'Error al obtener el archivo: ' . $e->getMessage(),
            ], 500);
        }
    }
}
