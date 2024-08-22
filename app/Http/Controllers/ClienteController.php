<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Persona;
use App\Services\BitacoraService;

class ClienteController extends Controller
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
            $personas = Persona::orderBy('id', 'desc')->paginate(10);
        } else {
            $personas = Persona::where($criterio, 'like', '%' . $buscar . '%')->orderBy('id', 'desc')->paginate(10);
        }


        return [
            'pagination' => [
                'total'        => $personas->total(),
                'current_page' => $personas->currentPage(),
                'per_page'     => $personas->perPage(),
                'last_page'    => $personas->lastPage(),
                'from'         => $personas->firstItem(),
                'to'           => $personas->lastItem(),
            ],
            'personas' => $personas
        ];
    }

    public function selectCliente(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $clientes = Persona::where('nombre', 'like', '%' . $filtro . '%')
            ->orWhere('codigo', 'like', '%' . $filtro . '%')
            ->select('id', 'codigo', 'nombre')
            ->orderBy('nombre', 'asc')->get();

        return ['clientes' => $clientes];
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $persona = new Persona();
        //$persona->codigo = $request->codigo;
        $persona->nombre = $request->nombre;
        $persona->dui = $request->dui;
        $persona->nit = $request->nit;
        $persona->nrc = $request->nrc;
        $persona->giro = $request->giro;
        $persona->direccion = $request->direccion;
        $persona->telefono = $request->telefono;
        $persona->email = $request->email;

        $persona->save();

        // Generar el código correlativo usando el ID
        $persona->codigo = 'CLI' . str_pad($persona->id, 4, '0', STR_PAD_LEFT);
        $persona->save();
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $persona = Persona::findOrFail($request->id);
        $persona->codigo = $request->codigo;
        $persona->nombre = $request->nombre;
        $persona->dui = $request->dui;
        $persona->nit = $request->nit;
        $persona->nrc = $request->nrc;
        $persona->giro = $request->giro;
        $persona->direccion = $request->direccion;
        $persona->telefono = $request->telefono;
        $persona->email = $request->email;
        $persona->save();
    }

    public function destroy(Request $request)
    {
        $persona = Persona::findOrFail($request->id);
        $persona->delete();

        $this->bitacoraService->store('Eliminación de registro', 'Cliente');
        return "Éxito";
    }
}
