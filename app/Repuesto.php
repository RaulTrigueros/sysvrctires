<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Repuesto extends Model
{
    protected $fillable = [
        'codigo',
        'nombre',
        'precio',
        'descripcion'
    ];


    public function pedidos()
    {
        return $this->hasMany('App\Pedido');
    }
}
