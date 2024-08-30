<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = ['persona_id', 'tipo_pago', 'tipo_cliente', 'fecha_hora', 'estado'];

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    public function pedidos()
    {
        return $this->hasMany(detallePedido::class);
    }
}
