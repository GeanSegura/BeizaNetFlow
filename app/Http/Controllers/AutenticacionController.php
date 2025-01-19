<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AutenticacionController extends Controller
{
    public function login(Request $request)
    {
       

        $result = DB::select('CALL validar_usuario(?, ?)', [
            $request->input('usuario'),
            $request->input('contrasena')
        ]);


        if ( $result[0]->RESULTADO == '1') {
            Session::put('rol', $result[0]->ROL);
            return redirect()->route('GestionLotes'); 
        } else {
            if(  $request->input('usuario') && $request->input('contrasena')){
            $mensaje = "Usuario o contraseña incorrectos";
            return view('login', compact('mensaje'));
            }
            else{
                return view('login');
            }
        }
    }

    function registrar(){
        return view('registrarse');
    }
   
}
