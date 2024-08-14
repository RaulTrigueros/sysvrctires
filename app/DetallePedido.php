<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DEtallePedido extends Model
{
    protected $fillable = ['pedido_id', 'tipo_producto', 'codigo_producto', 'cantidad'];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function llantaTubo()
    {
        return $this->hasOne(Llanta::class, 'codigo', 'codigo_producto')
            ->whereIn('tipo_producto', ['llanta', 'tubo']);
    }

    public function repuesto()
    {
        return $this->hasOne(Repuesto::class, 'codigo', 'codigo_producto')
            ->where('tipo_producto', 'repuesto');
    }
}
