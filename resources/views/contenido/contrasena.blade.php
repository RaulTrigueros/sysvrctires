@section('contenido')
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <meta name="description" content="Sistema de gestión de pedidos">
  <meta name="author" content="Ing. Raul Trigueros>
  <!--  <title>Modificar Contraseña</title>-->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link href="/css/app.css" rel="stylesheet">
  <link href="css/portal/portal.css" rel="stylesheet">
  <link href="css/general.css" rel="stylesheet">
  <title>VRC Moto Parts</title>

  <style>
    
    header {
      width: 100%;
      height: 20%;
      display: flex;
      align-items: center;
      padding: 1px 2px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      background-color: #c53232; /* Fondo blanco para el encabezado */
      position: absolute;
      top: 0;
      left: 0;
    }
    header img {
      max-width: 150px; /* Ajusta el tamaño del logo aquí */
    }

    body {
    
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    footer {
      width: 100%;
      text-align: center;
      padding: 10px 0;
      position: fixed;
      bottom: 0;
      left: 0;
      background-color: #f8f9fa;
      font-family: "Poppins", sans-serif;
      font-size: 0.9em;
      color: #6c757d;
    }

    button {
      align-content: center;
      align: center
    }

    a {
      font-family: "Poppins", sans-serif;;
      color: black
    }

    title {
      background-color: rgb(153, 153, 175);
      background-size: 12px
    }

    .text-danger {
      color: red
    }

    .errors {
      color: red
    }

    .div-error {
      display: flex;
      justify-content: center;
      color: red;
    }

    h1 {
      color: black;
      font-family: "Poppins", sans-serif;
      text-align: center;
    }

    form {
      width: 380px;
      font-family: "Poppins", sans-serif;;
      background: rgb(247, 94, 94);
      padding: 10px 30px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.5); /* Borde difuminado */
      border-radius: 20px;
      font-size: 90%;
      margin-top: 8%;
    }

    form input {
      font-family: "Poppins", sans-serif;;
    }

    form>div {
      margin: 2px;
      color: black
    }

    .logo{
      max-width: 100px;
      margin-bottom: 20px;
      display: block;
      margin-left: auto;
      margin-right: auto;
    }
  </style>
</head>

<body>
  <header>
    <a href="/main"><img src="{{ asset('img/logomoto-sinfondo.png') }}" alt="Logo"> </a>
  </header>
  @if (Session::has('message'))
  <div class="text-danger">
    {{Session::get('message')}}
  </div>
  @endif

  <form method="post" action="{{url('user/updatepassword')}}">
    {{csrf_field()}}
    <div>
      <img src="{{ asset('../img/logomoto-sinfondo.png') }}" alt="Logo" class="logo">
      <h1>Cambiar mi Contraseña</h1>
    </div>
    <hr />
    <div class="form-group">
      <label for="mypassword">Introduce tu actual password:</label>
      <input type="password" name="mypassword" class="form-control">
      <div class="text-danger">{{$errors->first('mypassword')}}</div>
    </div>
    <div class="form-group">
      <label for="password">Introduce tu nuevo password:</label>
      <input type="password" name="password" class="form-control">
      <div class="text-danger">{{$errors->first('password')}}</div>
    </div>
    <div class="form-group">
      <label for="mypassword">Confirma tu nuevo password:</label>
      <input type="password" name="password_confirmation" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Cambiar mi password</button>

    </br></br>
    <button type="button" class="btn btn-primary">
      <a class="btn btn-primary" href="/main" target="_blank">Regresar</a>
    </button>
  </form>


  @if(session('message')) {{session('message')}} @endif
  @if(session()->has('alert-success'))
  {{ session()->get('alert-success') }}
  @endif

  <footer>
    &copy; {{ date('Y') }} VRC Moto Parts Company. Todos los derechos reservados.
  </footer>
</body>

</html>