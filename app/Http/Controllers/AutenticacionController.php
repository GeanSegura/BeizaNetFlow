<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class AutenticacionController extends Controller
{
    public function login(Request $request)
    {
       

        $result = DB::select('CALL validar_usuario(?, ?)', [
            $request->input('usuario'),
            $request->input('contrasena')
        ]);


        if ( $result[0]->resultado == '1') {
            return redirect()->route('GestionLotes'); 
        } else {
           
            return Redirect::back()->withErrors(['message' => 'Usuario o contraseña incorrectos']);
        }
    }

    function registrar(){
        return view('registrarse');
    }
   
}
