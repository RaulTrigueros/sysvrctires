<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Llanta extends Model
{
    protected $fillable = [
        'codigo',
        'tipoproducto',
        'medida',
        'precio',
        'descripcion'
    ];


    public function pedidos()
    {
        return $this->hasMany('App\Pedido');
    }
}
