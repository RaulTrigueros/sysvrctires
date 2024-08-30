<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Llanta;
use App\Services\BitacoraService;

class LlantaController extends Controller
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

        if ($buscar == '') {
            $llantas = Llanta::select(
                'llantas.id',
                'llantas.codigo',
                'llantas.tipoproducto',
                'llantas.medida',
                'llantas.precio',
                'llantas.descripcion'
            )
                ->orderBy('llantas.id', 'desc')->paginate(10);
        } else {
            $llantas = Llanta::select(
                'llantas.id',
                'llantas.codigo',
                'llantas.tipoproducto',
                'llantas.medida',
                'llantas.precio',
                'llantas.descripcion'
            )
                ->Where('llantas.' . $criterio, 'like', '%' . $buscar . '%')
                ->orderBy('llantas.id', 'desc')->paginate(10);
        }

        return [
            'pagination' => [
                'total' => $llantas->total(),
                'current_page' => $llantas->currentPage(),
                'per_page' => $llantas->perPage(),
                'last_page' => $llantas->lastPage(),
                'from' => $llantas->firstItem(),
                'to' => $llantas->lastItem(),
            ],
            'llantas' => $llantas
        ];
    }

    public function selectLlanta(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $llantas = Llanta::where('codigo', 'like', '%' . $filtro . '%')
            ->orWhere('medida', 'like', '%' . $filtro . '%')
            ->select('id', 'codigo', 'tipoproducto', 'medida', 'precio', 'descripcion')
            ->orderBy('codigo', 'asc')->get();

        return ['llantas' => $llantas];
    }


    public function store(Request $request)
    {
        $llantas = new Llanta();
        $llantas->codigo = $request->codigo;
        $llantas->tipoproducto = $request->tipoproducto;
        $llantas->medida = $request->medida;
        $llantas->precio = $request->precio;
        $llantas->descripcion = $request->descripcion;
        $llantas->save();

        $this->bitacoraService->store('Creación de registro', 'Llanta o Tubo');
        return "Éxito";
    }

    public function update(Request $request)
    {
        $llantas = Llanta::findOrFail($request->id);
        $llantas->codigo = $request->codigo;
        $llantas->tipoproducto = $request->tipoproducto;
        $llantas->medida = $request->medida;
        $llantas->precio = $request->precio;
        $llantas->descripcion = $request->descripcion;
        $llantas->save();

        $this->bitacoraService->store('Actualización de registro', 'Llanta o Tubo');
        return "Éxito";
    }

    public function destroy(Request $request)
    {
        $llantas = Llanta::findOrFail($request->id);
        $llantas->delete();

        $this->bitacoraService->store('Eliminación de registro', 'Llanta');
        return "Éxito";
    }

    public function listarTipoproductoPedido(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '') {
            $llantas = Llanta::select(
                'llantas.id',
                'llantas.codigo',
                'llantas.tipoproducto',
                'llantas.medida',
                'llantas.precio',
                'llantas.descripcion'
            )
                ->orderBy('llantas.id', 'desc')->paginate(10);
        } else {
            $llantas = Llanta::select(
                'llantas.id',
                'llantas.codigo',
                'llantas.tipoproducto',
                'llantas.medida',
                'llantas.precio',
                'llantas.descripcion'
            )
                ->Where('llantas.' . $criterio, 'like', '%' . $buscar . '%')
                ->orderBy('llantas.id', 'desc')->paginate(10);
        }

        return ['llantas' => $llantas];
    }

    public function buscarTipoproductoPedido(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $llantas = Llanta::where('codigo', '=', $filtro)
            ->select('id', 'codigo', 'tipoproducto', 'medida', 'descripcion', 'precio')
            ->orderBy('codigo', 'asc')
            ->take(1)->get();

        return ['llantas' => $llantas];
    }
}
