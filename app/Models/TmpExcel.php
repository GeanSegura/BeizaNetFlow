<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TmpExcel extends Model
{
    protected $table = 'tbl_temp_articulo_laboratorio_precios';

    protected $fillable = [
        'id_articulo',
        'articulo',
        'laboratorio',
        'stock',
        'precio_minimo',
        'precio_lista_1',
        'precio_costo',
        'porcentaje',
        'precio_final'
    ];

    public $timestamps = false; // si tu tabla no tiene created_at y updated_at
}
