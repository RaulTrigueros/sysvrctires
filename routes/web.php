<?php

// use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PedidoController;

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
    Route::resource('pedidos', PedidoController::class);


    Route::get('/persona/selectPersona', 'ClienteController@selectCliente');
    Route::get('/energia', 'EnergiaController@index');
    Route::post('/energia/registrar', 'EnergiaController@store');
    Route::put('/energia/actualizar', 'EnergiaController@update');
    Route::put('/energia/desactivar', 'EnergiaController@desactivarFactura');
    Route::put('/energia/activar', 'EnergiaController@activarFactura');
    Route::put('/energia/eliminar', 'EnergiaController@destroy');
    Route::get('/energia/generarReporteEnergia', 'EnergiaController@generarReporteEnergia')->name('energia_pdf');
    Route::get('/energia/pdfFactura/{id}', 'EnergiaController@facturaPDF')->name('facturas_pdf');
    Route::get('/energia/pdf/nopagado', 'EnergiaController@filtropago')->name('energia_pdf');
    Route::get('/energia/pdf/fecha/{inicio}/{fin}', 'EnergiaController@filtrofechamensual')->name('energia_pdfmensual');
    Route::get('/energia/pdf/puesto/{puesto}', 'EnergiaController@filtropuesto')->name('energia_pdf');

    //GESTION DE LLANTAS Y TUBOS
    Route::get('/llanta/selectLlanta', 'LlantaController@selectLlanta');
    Route::get('/llanta', 'LlantaController@index');
    Route::post('/llanta/registrar', 'LlantaController@store');
    Route::put('/llanta/actualizar', 'LlantaController@update');
    Route::put('/llanta/eliminar', 'LlantaController@destroy');

    //GESTION DE REPUESTOS
    Route::get('/repuesto/selectRepuesto', 'RepuestoController@selectRepuesto');
    Route::get('/repuesto', 'RepuestoController@index');
    Route::post('/repuesto/registrar', 'RepuestoController@store');
    Route::put('/repuesto/actualizar', 'RepuestoController@update');
    Route::put('/repuesto/eliminar', 'RepuestoController@destroy');
});
