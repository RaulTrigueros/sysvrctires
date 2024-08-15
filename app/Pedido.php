<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = ['persona_id', 'tipo_pago', 'tipo_cliente'];

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    public function productos()
    {
        return $this->hasMany(PedidoProducto::class);
    }
}
