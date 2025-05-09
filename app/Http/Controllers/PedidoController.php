<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Pedido;
use App\DetallePedido;
use App\User;
use App\Services\BitacoraService;
use App\Notifications\NotifyAdmin;
use PDF;

class PedidoController extends Controller
{
    protected $bitacoraService;

    public function __construct(BitacoraService $bitacoraService)
    {
        $this->bitacoraService = $bitacoraService;
    }

    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        $query = Pedido::join('personas', 'pedidos.persona_id', '=', 'personas.id')
            ->select(
                'pedidos.id',
                'pedidos.tipo_cliente',
                'pedidos.created_at as fecha_hora',
                'pedidos.estado',
                'personas.nombre',
                'personas.codigo as codigo_persona',
                'pedidos.totalPagar'
            );

        if ($buscar != '') {
            if ($criterio == 'nombre' || $criterio == 'codigo') {
                // Busca en la tabla de personas
                $query->where('personas.' . $criterio, 'like', '%' . $buscar . '%');
            } else {
                // Busca en la tabla de pedidos
                $query->where('pedidos.' . $criterio, 'like', '%' . $buscar . '%');
            }
        }

        $pedidos = $query->orderBy('pedidos.id', 'desc')->paginate(10);

        return [
            'pagination' => [
                'total'        => $pedidos->total(),
                'current_page' => $pedidos->currentPage(),
                'per_page'     => $pedidos->perPage(),
                'last_page'    => $pedidos->lastPage(),
                'from'         => $pedidos->firstItem(),
                'to'           => $pedidos->lastItem(),
            ],
            'pedidos' => $pedidos
        ];
    }

    public function obtenerCabecera(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $id = $request->id;
        $pedido = Pedido::join('personas', 'pedidos.persona_id', '=', 'personas.id')
            ->select(
                'pedidos.id',
                //'pedidos.tipo_pago',
                'pedidos.tipo_cliente',
                'pedidos.created_at as fecha_hora',
                'personas.nombre',
                'personas.codigo as codigo_persona',
                'personas.direccion',
                'personas.telefono',
                'personas.email',
                'personas.nit',
                'personas.nrc',
                'personas.giro',
                'pedidos.totalPagar'
            )
            ->where('pedidos.id', '=', $id)
            ->orderBy('pedidos.id', 'desc')->take(1)->get();

        return ['pedido' => $pedido];
    }
    public function obtenerDetalles(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $id = $request->id;
        $detalles = DetallePedido::join('llantas', 'detalle_pedidos.llanta_id', '=', 'llantas.id')
            //->join('repuestos', 'pedidos.repuestos_id', '=', 'repuestos.id')
            ->select(
                'llantas.codigo',
                'llantas.tipoproducto as tipoproducto',
                'llantas.medida',
                'llantas.precio',
                'llantas.descripcion',
                'detalle_pedidos.cantidad'
                //'repuestos.nombre as nombre_repuesto'
            )
            ->where('detalle_pedidos.pedido_id', '=', $id)
            ->orderBy('detalle_pedidos.id', 'desc')->get();

        return ['detalles' => $detalles];
    }

    public function pdf(Request $request, $id)
    {
        $pedido = Pedido::join('personas', 'pedidos.persona_id', '=', 'personas.id')
            ->select(
                'pedidos.id',
                // 'pedidos.tipo_pago',
                'pedidos.tipo_cliente',
                'pedidos.fecha_hora',
                'personas.nombre',
                'personas.direccion',
                'personas.telefono',
                'personas.email',
                'personas.nit',
                'personas.nrc',
                'personas.giro',
                'personas.codigo as codigo_persona',
                'pedidos.totalPagar'

            )
            ->where('pedidos.id', '=', $id)
            ->first();
        //->orderBy('pedidos.id', 'desc')->take(1)->get();

        $detalles = DetallePedido::join('llantas', 'detalle_pedidos.llanta_id', '=', 'llantas.id')
            ->select(
                'llantas.codigo',
                'llantas.tipoproducto as tipoproducto',
                'llantas.descripcion',
                'llantas.medida',
                'llantas.precio',
                'detalle_pedidos.cantidad'
            )
            ->where('detalle_pedidos.pedido_id', '=', $id)
            ->get();

        // Calcular el total parcial
        $totalParcial = $detalles->sum(function ($detalle) {
            return $detalle->precio * $detalle->cantidad;
        });
        // Obtener el porcentaje de descuento según el tipo de cliente
        $descuentos = [
            'tallerista' => 0,
            'mayoreo' => 7,
            'distribuidor' => 14,
            'importador' => 20,
        ];
        $porcentajeDescuento = $descuentos[$pedido->tipo_cliente] ?? 0; //se captura el porcentaje de descuento dependiendo del tipo de cliente
        // Calcular el descuento y el total a pagar
        $descuento = ($totalParcial * $porcentajeDescuento) / 100;
        $totalPagar = $totalParcial - $descuento;

        // Validación en caso de que el pedido no exista
        if (!$pedido) {
            return response()->json(['message' => 'Pedido no encontrado.'], 404);
        }
        // Generar el PDF
        $pdf = \PDF::loadView('pdf.pedidopdf', [
            'pedido' => $pedido,
            'detalles' => $detalles,
            'totalParcial' => $totalParcial,
            'descuento' => $descuento,
            'totalPagar' => $totalPagar,
            'porcentajeDescuento' => $porcentajeDescuento
        ]);

        // Cambiar a `stream()` para mostrar el PDF en el navegador
        return $pdf->stream('pedido-' . $pedido->codigo_persona . '.pdf');
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try {
            DB::beginTransaction();

            // $mytime = Carbon::now();

            $pedido = new Pedido();
            $pedido->persona_id = $request->persona_id;
            // $pedido->tipo_pago = $request->tipo_pago;
            $pedido->tipo_cliente = $request->tipo_cliente;
            $pedido->fecha_hora = now(); // Agregar la fecha y hora actual
            $pedido->totalPagar = $request->totalPagar;
            $pedido->save();

            $detalles = $request->data; //Array de detalles
            //Recorro todos los elementos

            foreach ($detalles as $ep => $det) {
                $detalle = new DetallePedido();
                $detalle->pedido_id = $pedido->id;
                $detalle->llanta_id = $det['llanta_id'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->save();
            }
        } catch (Exception $e) {
            DB::rollBack();
        }

        $this->bitacoraService->store('Pedido registrado', 'Pedido');
        return response()->json(['message' => 'Pedido registrado correctamente', 'id' => $pedido->id]);
    }

    public function cambiarEstado(Request $request, $id)
    {
        if (!$request->ajax()) return redirect('/');

        $pedido = Pedido::findOrFail($id); // Encuentra el pedido
        $pedido->estado = !$pedido->estado; // Cambia el estado (1 a 0, o viceversa)
        $pedido->save(); // Guarda los cambios

        $this->bitacoraService->store('Cambio de estado de pedido', 'Pedido');

        return response()->json([
            'success' => true,
            'message' => 'El estado del pedido ha cambiado.',
            'estado' => $pedido->estado
        ]);
    }


    public function destroy(Request $request)
    {
        $pedidos = Pedido::findOrFail($request->id);
        $pedidos->delete();

        $this->bitacoraService->store('Eliminación de registro', 'Pedido');
        return "Éxito";
    }
}
