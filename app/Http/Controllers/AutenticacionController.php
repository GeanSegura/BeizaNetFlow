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

        $PerfilFuncionalidad = $request->input('opcionFuncionalidad');

        $result = DB::select('CALL validar_usuario(?, ?)', [
            $request->input('usuario'),
            $request->input('contrasena')
        ]);


        switch ($PerfilFuncionalidad) {
            case '1':
                if ($result[0]->RESULTADO == '1') {

                    if ($result[0]->ROL == '001' || $result[0]->ROL == '002') {
                        Session::put('rol', $result[0]->ROL);
                        return redirect()->route('GestionLotes');
                    } else {
                        $mensaje = "No tiene permisos para subir PDF de Lotes.";
                        return view('login', compact('mensaje'));
                    }

                } else {
                    if ($request->input('usuario') && $request->input('contrasena')) {
                        $mensaje = "Usuario o contraseña incorrectos";
                        return view('login', compact('mensaje'));
                    } else {
                        return view('login');
                    }
                }

            case '2':

                 if ($result[0]->RESULTADO == '1') {

                    if ($result[0]->ROL == '003' || $result[0]->ROL == '004') {
                        Session::put('rol', $result[0]->ROL);
                        if ($result[0]->ROL == '003') {
                             return redirect()->route('PrecioArticulos')->with('isConfiguracion', '0');
                        } else {
                             return redirect()->route('PrecioArticulos')->with('isConfiguracion', '1');
                        }

                    } else {

                        $mensaje = "No tiene permisos para Visualizar precios de artículos.";
                        return view('login', compact('mensaje'));
                    }

                } else {
                    if ($request->input('usuario') && $request->input('contrasena')) {
                        $mensaje = "Usuario o contraseña incorrectos";
                        return view('login', compact('mensaje'));
                    } else {
                        return view('login');
                    }
                }

            default:
                return view('login');
        };
    }

    function registrar()
    {
        return view('registrarse');
    }
}
