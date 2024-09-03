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
                'pedidos.tipo_pago',
                'pedidos.tipo_cliente',
                'pedidos.fecha_hora',
                'pedidos.estado',
                'personas.nombre',
                'personas.codigo as codigo_persona'
            );

        if ($buscar != '') {
            if ($criterio == 'nombre' || $criterio == 'codigo_persona') {
                // Busca en la tabla de personas
                $query->where('personas.' . $criterio, 'like', '%' . $buscar . '%');
            } else {
                // Busca en la tabla de pedidos
                $query->where('pedidos.' . $criterio, 'like', '%' . $buscar . '%');
            }
        }

        $pedidos = $query->orderBy('pedidos.id', 'desc')->paginate(3);

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
                'pedidos.tipo_pago',
                'pedidos.tipo_cliente',
                'pedidos.fecha_hora',
                'personas.nombre',
                'personas.codigo as codigo_persona',
                'personas.direccion',
                'personas.telefono'
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
                'detalle_pedidos.cantidad',
                'llantas.tipoproducto as tipoproducto',
                'llantas.medida',
                'llantas.precio',
                'llantas.descripcion',
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
                'pedidos.tipo_pago',
                'pedidos.tipo_cliente',
                'pedidos.estado',
                'personas.nombre',
                'personas.direccion',
                'personas.telefono',
                'personas.codigo as codigo_persona'

            )
            ->where('pedidos.id', '=', $id)
            ->orderBy('pedidos.id', 'desc')->take(1)->get();

        $detalles = DetallePedido::join('llantas', 'detalle_pedidos.llanta_id', '=', 'llantas.id')
            ->join('repuestos', 'pedidos.repuestos_id', '=', 'repuestos.id')
            ->select(
                'detalle_pedidos.cantidad',
                'llantas.tipoproducto as tipoproducto',
                'llantas.descripcion',
                'repuestos.nombre as nombre_repuesto',
                'repuestos.descripcion'
            )
            ->where('detalle_pedidos.pedido_id', '=', $id)
            ->orderBy('detalle_pedidos.id', 'desc')->get();

        $numpedido = Pedido::join('personas', 'pedidos.persona_id', '=', 'personas.id')
            ->select('personas.codigo as codigo_persona')->where('id', $id)->get();

        $pdf = \PDF::loadView('pdf.pedido', ['pedido' => $pedido, 'detalles' => $detalles]);
        return $pdf->download('pedido-' . $numpedido[0]->codigo . '.pdf');
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try {
            DB::beginTransaction();

            $mytime = Carbon::now();

            $pedido = new Pedido();
            $pedido->persona_id = $request->persona_id;
            $pedido->tipo_pago = $request->tipo_pago;
            $pedido->tipo_cliente = $request->tipo_cliente;
            // $pedido->direccion = $request->direccion;
            //$pedido->telefono = $request->telefono;
            $pedido->fecha_hora = $mytime->toDateString();
            $pedido->estado = '1';
            $pedido->save();

            $detalles = $request->data; //Array de detalles
            //Recorro todos los elementos

            foreach ($detalles as $ep => $det) {
                $detalle = new DetallePedido();
                $detalle->pedido_id = $pedido->id;
                $detalle->llanta_id = $det['llanta_id'];
                // $detalle->repuesto_id = $det['repuesto_id'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->save();
            }

            /* $fechaActual = date('Y-m-d');
            $numPedidos = DB::table('pedidos')->whereDate('created_at', $fechaActual)->count();
            $numIngresos = DB::table('ingresos')->whereDate('created_at', $fechaActual)->count();

            $arregloDatos = [
                'pedidos' => [
                    'numero' => $numPedidos,
                    'msj' => 'Pedidos'
                ],
                'ingresos' => [
                    'numero' => $numIngresos,
                    'msj' => 'Ingresos'
                ]
            ];
            $allUsers = User::all();

            foreach ($allUsers as $notificar) {
                User::findOrFail($notificar->id)->notify(new NotifyAdmin($arregloDatos));
            }

            DB::commit();
            return [
                'id' => $pedido->id
            ]*/
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function desactivarPedido(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $pedido = Pedido::findOrFail($request->id);
        $pedido->estado = '0';
        $pedido->save();

        $this->bitacoraService->store('Pedido entregado', 'Pedido');
        return "Éxito";
    }

    public function activarPedido(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $pedido = Pedido::findOrFail($request->id);
        $pedido->estado = '1';
        $pedido->save();

        $this->bitacoraService->store('Entrega de pedido anulada', 'Pedido');
        return "Éxito";
    }
}
