<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;

use Illuminate\Http\Request;

class DescargarArchivoController extends Controller
{
    function descargarArchivo($fileName) {
     // URL base de tu API Gateway
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

             // Retorna la respuesta al navegador con el archivo adjunto
             return response($content, 200)
                 ->header('Content-Type', $contentType)
                 ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
         }

     } catch (\Exception $e) {
         // Manejo de errores
         return response()->json([
             'error' => 'Error al obtener el archivo: ' . $e->getMessage(),
         ], 500);
     }
    }
}
