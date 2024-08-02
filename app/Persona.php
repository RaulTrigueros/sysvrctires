<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $fillable = ['codigo', 'nombre', 'dui', 'nit', 'nrc', 'giro', 'direccion', 'telefono', 'email'];

    public function provedor()
    {
        return $this->hasOne('App\Proveedor');
    }

    public function user()
    {
        return $this->hasOne('App\User');
    }

    public function pedidos()
    {
        return $this->hasMany('App\Pedido');
    }
}
