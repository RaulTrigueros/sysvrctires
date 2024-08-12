<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'idpersona',
        'idllanta',
        'idrepuesto',
        'tipopago',
        'tipocliente',
        'tipoproducto',
        'cantidad',
        'total',
        'estado'
    ];
    public function persona()
    {
        return $this->belongsTo('App\Persona');
    }
    public function llanta()
    {
        return $this->belongsTo('App\Llanta');
    }
    public function repuesto()
    {
        return $this->belongsTo('App\Repuesto');
    }
}
