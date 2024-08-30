<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    protected $table = 'detalle_pedidos';
    protected $fillable = [
        'pedido_id',
        'llanta_id',
        'repuesto_id',
        'cantidad',
    ];
    public $timestamps = false;
}
