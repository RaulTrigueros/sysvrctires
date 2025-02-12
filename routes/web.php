<?php

// use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ClienteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

//GRUPO DE RUTAS
/*Route::get('/', function () {
    if (Auth::user()) {
        return view('contenido/index', ['log' => 'si']);
    } else {
        return view('contenido/index', ['log' => 'no']);
    }
});*/

Route::get('/', 'Auth\LoginController@showLoginForm');
Route::post('/login1', 'Auth\LoginController@login')->name('login');

//Cambiar contraseña
Route::get('user/password', 'UserController@password');
Route::post('user/updatepassword', 'UserController@updatePassword');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');


//GRUPO DE RUTAS PARA ADMINISTRADOR
Route::group(['middleware' => ['Administrador']], function () {

    Route::get('/main', function () {
        if (Auth::user()) {
            return view('contenido/contenido', ['rol' => Auth::user()->role_id, 'user' => Auth::user()]);
        } else {
            return redirect("/");
        }
    })->name('main'); //muestra el contenido principal de la aplicacion

    Route::get('/portal', function () {
        if (Auth::user()) {
            return view('contenido/index', ['log' => 'si']);
        } else {
            return view('contenido/index', ['log' => 'no']);
        }
    });
    //********* BITACORA ***********
    Route::get('/bitacora', 'BitacoraController@index');
    //******************************
    Route::get('/registro', function () {
        return view('contenido/registroUser');
    });
    //MODULO DE GESTIÓN DE USUARIOS
    Route::get('/rol', 'RolController@index');
    Route::get('/rol/selectRol', 'RolController@selectRol');
    Route::get('/user', 'UserController@index');
    Route::post('/user/registrar', 'UserController@store');
    Route::put('/user/actualizar', 'UserController@update');
    Route::put('/user/desactivar', 'UserController@desactivar');
    Route::put('/user/activar', 'UserController@activar');

    //GESTION DE CLIENTES
    Route::get('/cliente', 'ClienteController@index');
    Route::post('/cliente/registrar', 'ClienteController@store');
    Route::put('/cliente/actualizar', 'ClienteController@update');
    Route::put('/cliente/eliminar', 'ClienteController@destroy');
    Route::get('/cliente/selectCliente', 'ClienteController@selectCliente');


    //MÓDULO DE PEDIDO
    Route::get('/pedido', 'PedidoController@index');
    Route::post('/pedido/registrar', 'PedidoController@store');
    Route::put('/pedido/entregar', 'PedidoController@desactivarPedido')->middleware('web'); //Cambia el estado del pedido de PROCESO a ENTREGADO
    Route::put('/pedido/anular', 'PedidoController@activarPedido')->middleware('web'); //Cambia el estado del pedido de ENTREGADO a PROCESO
    Route::get('/pedido/obtenerCabecera', 'PedidoController@obtenerCabecera');
    Route::get('/pedido/obtenerDetalles', 'PedidoController@obtenerDetalles');
    Route::get('/pedido/pdf/{id}', 'PedidoController@pdf')->name('pedido_pdf');
    Route::put('/pedido/eliminar', 'PedidoController@destroy');


    //GESTION DE LLANTAS Y TUBOS
    Route::get('/llanta/selectLlanta', 'LlantaController@selectLlanta');
    Route::get('/llanta', 'LlantaController@index');
    Route::post('/llanta/registrar', 'LlantaController@store');
    Route::put('/llanta/actualizar', 'LlantaController@update');
    Route::put('/llanta/eliminar', 'LlantaController@destroy');
    Route::get('/llanta/buscarTipoproductoPedido', 'LlantaController@buscarTipoproductoPedido');
    Route::get('/llanta/listarTipoproductoPedido', 'LlantaController@listarTipoproductoPedido');

    //GESTION DE REPUESTOS
    Route::get('/repuesto/selectRepuesto', 'RepuestoController@selectRepuesto');
    Route::get('/repuesto', 'RepuestoController@index');
    Route::post('/repuesto/registrar', 'RepuestoController@store');
    Route::put('/repuesto/actualizar', 'RepuestoController@update');
    Route::put('/repuesto/eliminar', 'RepuestoController@destroy');
});
