<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Llanta;
use App\Repuesto;

class ProductoController extends Controller
{
    public function showProducto($tipo, $codigo)
    {
        $producto = Llanta::where('codigo', $codigo)->first();

        if (!$producto) {
            $producto = Repuesto::where('codigo', $codigo)->first();
        }

        if ($producto) {
            return response()->json($producto);
        } else {
            return response()->json(['error' => 'Producto no encontrado'], 404);
        }
    }
}
