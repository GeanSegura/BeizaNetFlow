<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrecioArticulosController extends Controller
{
     function index()
    {
        return view('precioArticulos');
    }
}
