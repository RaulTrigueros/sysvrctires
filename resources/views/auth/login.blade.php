@extends('auth.contenido')

@section('login')
<div class="row justify-content-left">
      <div class="col-md-4">
        <div class="card-group mb-0">
          <div class="card p-4 form-container">
          <form class ="form-horizontal was-validated" method="POST" action="{{route('login')}}">
               {{csrf_field()}}   
              <div class="card-body">
              <h1>Acceder</h1>
              <p class="text-muted">Control de acceso al sistema</p>
              <div class="form-group mb-3{{$errors->has('usuario' ? 'is-invalid' : '')}}">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input type="text" value="{{old('usuario')}}" name="usuario" id="usuario" class="form-control" placeholder="Usuario">
                  {!!$errors->first('usuario','<span class="invalid-feedback">:message</span>')!!}
              </div>
              <div class="form-group mb-4{{$errors->has('password' ? 'is-invalid' : '')}}">
                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña">
                 {!!$errors->first('password','<span class="invalid-feedback">:message</span>')!!}
              </div>
              <div class="row">
                <div class="col-6">
                  <button type="submit" class="btn btn-red px-4">Acceder</button>
                </div>
              </div>
            </div>
          </form>
          </div>
        </div>
      </div>
    </div>
@endsection
