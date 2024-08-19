<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PedidoProducto extends Model
{
    protected $table = 'pedido_productos';

    protected $fillable = [
        'pedido_id',
        'codigo_producto',
        'tipo_producto',
        'cantidad',
        'precio'
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }
}
