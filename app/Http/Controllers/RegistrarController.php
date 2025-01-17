<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegistrarController extends Controller
{
     // Insertar los datos recibidos en la base de datos
     public function registrarUsuario(Request $request)
    {
        // Insertar los datos recibidos en la base de datos
        $usuario = $request->input('usuario');
        $correo = $request->input('correo');
        $contrasena = $request->input('contrasena'); // Encriptar la contraseña
        // $contrasena = bcrypt($request->input('contrasena')); // Encriptar la contraseña

        try {
            // Llamada al procedimiento almacenado o inserción directa
           
            $result = DB::select('CALL insertar_usuario(?, ?,?)', [
                $usuario,
                $correo,
                $contrasena,

            ]);
        
            if ( $result[0]->resultado == '1') {
                return response()->json(['mensaje' => 'Se registro corretamnete.']);
    
            } else {
                return response()->json(['mensaje' => 'No se pudo registrar el usuario.']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 500);
        }
    }

  
}
