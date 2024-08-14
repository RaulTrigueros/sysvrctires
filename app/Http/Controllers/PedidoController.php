<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Pedido;
use App\Persona;
use App\Llanta;
use App\Repuesto;
use App\Services\BitacoraService;
use Carbon\Carbon;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::with('persona', 'detalles')->get();
        return response()->json($pedidos);
    }

    public function store(Request $request)
    {
        $pedido = Pedido::create($request->only('persona_id', 'tipo_pago', 'tipo_cliente'));

        foreach ($request->detalles as $detalle) {
            if ($detalle['tipo_producto'] === 'llanta' || $detalle['tipo_producto'] === 'tubo') {
                $producto = Llanta::where('codigo', $detalle['codigo_producto'])->first();
            } else if ($detalle['tipo_producto'] === 'repuesto') {
                $producto = Repuesto::where('codigo', $detalle['codigo_producto'])->first();
            }

            if ($producto) {
                $pedido->detalles()->create([
                    'tipo_producto' => $detalle['tipo_producto'],
                    'codigo_producto' => $detalle['codigo_producto'],
                    'cantidad' => $detalle['cantidad']
                ]);
            }
        }

        return response()->json($pedido->load('detalles'));
    }

    public function show($id)
    {
        $pedido = Pedido::with('persona', 'detalles')->findOrFail($id);
        return response()->json($pedido);
    }

    public function update(Request $request, $id)
    {
        $pedido = Pedido::findOrFail($id);
        $pedido->update($request->only('persona_id', 'tipo_pago', 'tipo_cliente'));

        $pedido->detalles()->delete(); // Elimina detalles existentes para reemplazar con los nuevos

        foreach ($request->detalles as $detalle) {
            $pedido->detalles()->create([
                'tipo_producto' => $detalle['tipo_producto'],
                'codigo_producto' => $detalle['codigo_producto'],
                'cantidad' => $detalle['cantidad']
            ]);
        }

        return response()->json($pedido->load('detalles'));
    }

    public function destroy($id)
    {
        $pedido = Pedido::findOrFail($id);
        $pedido->delete();

        return response()->json(['message' => 'Pedido eliminado correctamente']);
    }
}
