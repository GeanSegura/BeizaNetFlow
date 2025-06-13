<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Http;


class AsitenteChatBotController extends Controller
{

public function cargarChatBot(Request $request){


   $msg = $request->input('msg_user');

// Revisa si ya se saludó al usuario
$yaSaludo = session('ya_saludo', false);

// System prompt base
$system_prompt = "<<<PROMPT
Eres un asistente experto del sistema BeizaNet - Gestión de Lotes.

🧠 Conocimiento actual:
- Hasta la fecha existen 1922 lotes registrados.
- EL SW FUE CREADO POR JOAN, JEAN Y GEAN.
- Si hay alguna duda que no puedas resolver, informa que pueden escribir a: joan@gmail.com

📦 Laboratorios registrados:
- '2' → SERVICIOS
- '3' → Artículos
- '4' → PRODUCTOS DE BONI
- '5' → GENFAR PERU S.A.
- '6' → TERBOL PERU S.A.
- '7' → MEDROCK - LINEA COME
- '8' → SCIENTIA PHARMA SAC
- '9' → PHARMAGEN
- '10' → QUILAB FARMA
- '11' → OQPHARMA S.A.C.
- '12' → DRO PE SAC

🔄 Tipos de lotes por categoría:
- '1' → ALQUILER DE LOCAL COMERCIAL (SERVICIOS)
- '2' → APOYO PROMOCIONAL (SERVICIOS)
- '3' → Mantenimiento (SERVICIOS)
- '4' → Producción encargada a terceros (SERVICIOS)
- '5' → Servicios-ventas (SERVICIOS)
- '6' → Contabilidad (SERVICIOS)
- '7' → Servicios legales (SERVICIOS)

🎯 Objetivo:
Ayuda al usuario a registrar, buscar, modificar o consultar lotes de productos, archivos, documentos u operaciones. Proporciona respuestas claras, técnicas y útiles. Mantén un tono amable y profesional, y responde incluso si los mensajes están parafraseados o son breves.
PROMPT";

// Si aún no se ha saludado, añade la instrucción para pedir el nombre
if (!$yaSaludo) {
    $system_prompt .= "\n\nAntes de comenzar, por favor pide amablemente el nombre del usuario. salúdalos como si ya los conocieras.";
}

// Unir prompt e input
$input = $system_prompt . "\nUsuario: " . $msg;

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-goog-api-key' => env('GOOGLE_GEMINI_API_KEY'),
        ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent', [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $input]
                    ],
                ],
            ],
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $respuesta = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Sin respuesta.';
            return response()->json(['reply' => $respuesta]);
        } else {
            return response()->json(['reply' => 'Error al consultar Gemini: ' . $response->body()], 500);
        }
}

}
