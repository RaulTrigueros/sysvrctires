<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Persona;
use Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Services\BitacoraService;

class UserController extends Controller
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
            $personas = User::join('personas', 'users.id', '=', 'personas.id')
                ->join('roles', 'users.idrol', '=', 'roles.id')
                ->select('personas.id', 'personas.nombre', 'personas.telefono', 'personas.email', 'users.cargo', 'users.usuario', 'users.password', 'users.condicion', 'users.idrol', 'roles.nombre as rol')
                ->orderBy('personas.id', 'desc')->paginate(10);
        } else {
            $personas = User::join('personas', 'users.id', '=', 'personas.id')
                ->join('roles', 'users.idrol', '=', 'roles.id')
                ->select('personas.id', 'personas.nombre', 'personas.telefono', 'personas.email', 'users.cargo', 'users.usuario', 'users.password', 'users.condicion', 'users.idrol', 'roles.nombre as rol')
                ->where('personas.' . $criterio, 'like', '%' . $buscar . '%')->orderBy('id', 'desc')->paginate(10);
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

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try {
            DB::beginTransaction();

            // Verificar si el usuario ya existe por su nombre de usuario o email
            $usuarioExistente = User::where('usuario', $request->usuario)->first();

            if ($usuarioExistente) {
                return response()->json(['error' => 'El usuario ya existe en la base de datos'], 409);
            }

            $persona = new Persona();
            $persona->nombre = $request->nombre;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;
            $persona->save();

            $user = new User();
            $user->id = $persona->id;
            $user->cargo = $request->cargo;
            $user->idrol = $request->idrol;
            $user->usuario = $request->usuario;
            $user->password = bcrypt($request->password);
            $user->condicion = '1';
            $user->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        $this->bitacoraService->store('Creación de registro', 'Usuario');
        return "Éxito";
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try {
            DB::beginTransaction();

            $user = User::findOrFail($request->id);
            $persona = Persona::findOrFail($user->id);
            $persona->nombre = $request->nombre;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;
            $persona->save();
            $user->cargo = $request->cargo;
            $user->usuario = $request->usuario;
            $user->password = bcrypt($request->password);
            $user->condicion = '1';
            $user->idrol = $request->idrol;
            $user->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        $this->bitacoraService->store('Actualizacion de registro', 'Usuario');
        return "Éxito";
    }

    public function cambiarEstado(Request $request, $id)
    {
        if (!$request->ajax()) return redirect('/');

        $user = User::findOrFail($id); // Encuentra el user
        $user->condicion = !$user->condicion; // Cambia el estado (1 a 0, o viceversa)
        $user->save(); // Guarda los cambios

        $accion = $user->condicion ? 'Activación' : 'Desactivación';
        $this->bitacoraService->store("{$accion} de usuario", 'Usuario');

        return response()->json([
            'success' => true,
            'message' => 'El estado del user ha cambiado.',
            'estado' => $user->condicion
        ]);
    }


    //Modificar contraseña
    public function password()
    {
        return View('contenido/contrasena');
    }

    public function updatePassword(Request $request)
    {
        $rules = [
            'mypassword' => 'required',
            'password' => 'required|confirmed',
        ];

        $messages = [
            'mypassword.required' => '**El campo es requerido**',
            'password.required' => '**El campo es requerido**',
            'password.confirmed' => '**Los passwords no coinciden**',
            //            'password.min' => 'El mínimo permitido son 6 caracteres',
            //            'password.max' => 'El máximo permitido son 18 caracteres',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('user/password')->withErrors($validator);
        } else {
            if (Hash::check($request->mypassword, Auth::user()->password)) {
                $user = new User;
                $user->where('usuario', '=', Auth::user()->usuario)
                    ->update(['password' => bcrypt($request->password)]);
                return redirect('/main')->with('alert-success', 'La contraseña ha sido modificada Exitosamente!');
            } else {
                return redirect('user/password')->with('message', 'Credenciales incorrectas');
            }
        }
    }
}
