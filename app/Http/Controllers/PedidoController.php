<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pedido;
use App\PedidoProducto;

class PedidoController extends Controller
{
    public function store(Request $request)
    {
        $pedido = Pedido::create([
            'persona_id' => $request->persona_id,
            'tipo_pago' => $request->tipo_pago,
            'tipo_cliente' => $request->tipo_cliente,
        ]);

        foreach ($request->productos as $producto) {
            PedidoProducto::create([
                'pedido_id' => $pedido->id,
                'codigo_producto' => $producto['codigo_producto'],
                'tipo_producto' => $producto['tipo_producto'],
                'cantidad' => $producto['cantidad'],
                'precio' => $producto['precio'],
            ]);
        }

        return response()->json($pedido);
    }

    // Método para obtener la lista de pedidos
    public function index()
    {
        $pedidos = Pedido::with('productos')->get();
        return response()->json($pedidos);
    }
}
