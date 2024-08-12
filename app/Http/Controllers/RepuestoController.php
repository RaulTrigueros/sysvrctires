<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repuesto;
use App\Services\BitacoraService;

class RepuestoController extends Controller
{
    protected $bitacoraService;

    public function __construct(BitacoraService $bitacoraService)
    {
        $this->bitacoraService = $bitacoraService;
    }

    public function index(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '') {
            $repuestos = Repuesto::select(
                'repuestos.id',
                'repuestos.codigo',
                'repuestos.nombre',
                'repuestos.precio',
                'repuestos.descripcion'
            )
                ->orderBy('repuestos.id', 'desc')->paginate(10);
        } else {
            $repuestos = Repuesto::select(
                'repuestos.id',
                'repuestos.codigo',
                'repuestos.nombre',
                'repuestos.precio',
                'repuestos.descripcion'
            )
                ->Where('repuestos.' . $criterio, 'like', '%' . $buscar . '%')
                ->orderBy('repuestos.id', 'desc')->paginate(10);
        }

        return [
            'pagination' => [
                'total' => $repuestos->total(),
                'current_page' => $repuestos->currentPage(),
                'per_page' => $repuestos->perPage(),
                'last_page' => $repuestos->lastPage(),
                'from' => $repuestos->firstItem(),
                'to' => $repuestos->lastItem(),
            ],
            'repuestos' => $repuestos
        ];
    }

    public function selectRepuesto(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $repuestos = Repuesto::where('codigo', 'like', '%' . $filtro . '%')
            ->orWhere('nombre', 'like', '%' . $filtro . '%')
            ->select('id', 'codigo', 'nombre', 'precio', 'descripcion')
            ->orderBy('codigo', 'asc')->get();

        return ['repuestos' => $repuestos];
    }


    public function store(Request $request)
    {
        $repuestos = new Repuesto();
        $repuestos->codigo = $request->codigo;
        $repuestos->nombre = $request->nombre;
        $repuestos->precio = $request->precio;
        $repuestos->descripcion = $request->descripcion;
        $repuestos->save();

        $this->bitacoraService->store('Creación de registro', 'Repuesto');
        return "Éxito";
    }

    public function update(Request $request)
    {
        $repuestos = Repuesto::findOrFail($request->id);
        $repuestos->codigo = $request->codigo;
        $repuestos->nombre = $request->nombre;
        $repuestos->precio = $request->precio;
        $repuestos->descripcion = $request->descripcion;
        $repuestos->save();

        $this->bitacoraService->store('Actualización de registro', 'Repuesto');
        return "Éxito";
    }

    public function destroy(Request $request)
    {
        $repuestos = Repuesto::findOrFail($request->id);
        $repuestos->delete();

        $this->bitacoraService->store('Eliminación de registro', 'Repuesto');
        return "Éxito";
    }
}
