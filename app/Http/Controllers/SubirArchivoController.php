<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class SubirArchivoController extends Controller
{

    // public function Guardar(Request $request)
    // {
    //     // Obtiene el archivo
    //     $file = $request->file('file');
    //     $fileName = time() . '_' . $file->getClientOriginalName();
    //     $filePath = $file->getRealPath();  // Ruta del archivo en el servidor

    //     // Crear una instancia de Guzzle
    //     $client = new Client();

    //     // Configurar los parámetros del PUT en la URL de la API Gateway de S3
    //     $url = 'https://2w5hx6ly67.execute-api.sa-east-1.amazonaws.com/dev/beizanet-protocolos/' . $fileName;

    //     try {
    //         // Enviar el archivo a S3 a través de API Gateway
    //         $response = $client->put($url, [
    //             'headers' => [
    //                 'Content-Type' => $file->getMimeType(),
    //             ],
    //             'body' => fopen($filePath, 'r'), // Cargar el archivo
    //         ]);

    //         // Verificar la respuesta
    //         if ($response->getStatusCode() == 200) {
    //             return response()->json([
    //                 'message' => 'Archivo subido exitosamente a S3',
    //                 'file' => $fileName,
    //                 'path' => $url,
    //             ]);
    //         }

    //     } catch (\Exception $e) {
    //         // Si ocurre un error
    //         return response()->json([
    //             'error' => 'Error al subir el archivo: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }
    // }

    function Guardar(Request $request)
    {
        // Validar el archivo enviado (solo permitir pdf, png, jpg, jpeg)
        $request->validate([
            'file' => 'required|file|mimes:pdf,png,jpg,jpeg|max:10240', // Tamaño máximo: 10MB
        ]);

        // Obtiene el archivo
        $loteId = $request->input('lote_id');   
        $file = $request->file('file');
        $fileName = date('Ymd_Hi') . '_' . $file->getClientOriginalName();
        $filePath = $file->getRealPath();  // Ruta del archivo en el servidor

        // Crear una instancia de Guzzle
        $client = new Client();

        // Configurar los parámetros del PUT en la URL de la API Gateway de S3
        $url = 'https://2w5hx6ly67.execute-api.sa-east-1.amazonaws.com/dev/beizanet-protocolos/' . $fileName;

        try {


            $loteArchivo = DB::select('CALL guardar_archivo_lote(?,?)', [
                $loteId,
                $fileName
            ]);

            // Enviar el archivo a S3 a través de API Gateway
            $response = $client->put($url, [
                'headers' => [
                    'Content-Type' => $file->getMimeType(),
                ],
                'body' => fopen($filePath, 'r'), // Cargar el archivo
            ]);

            // Verificar la respuesta
            if ($response->getStatusCode() == 200) {
                return response()->json([
                    'message' => 'Archivo subido exitosamente a S3',
                    'file' => $fileName,
                    'path' => $url,
                ]);
            } else {
                return response()->json([
                    'error' => 'La respuesta del servidor no fue exitosa. Código de estado: ' . $response->getStatusCode(),
                ], 500);
            }
        } catch (\Exception $e) {
            // Si ocurre un error
            return response()->json([
                'error' => 'Error al subir el archivo: ' . $e->getMessage(),
            ], 500);
        }
    }
}
