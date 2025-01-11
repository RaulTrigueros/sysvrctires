@extends('plantilla/principal')
@section('contenido')

<div class="container">
  <template v-if="menu==0">

    <div align="center">
      <img class="navbar-brand-full" src="img/logomoto-sinfondo.png" width="50%" height="auto" alt="Logo Tires">
    </div>

  </template>

  @if (Auth::check())
          @if(Auth::user()->idrol==1)
          <template v-if="menu==70">
            <pedido :ruta="'http://vrctireswamp.com'"></pedido>
          </template>
          <template v-if="menu==71">
            <cliente></cliente>
          </template>
          <template v-if="menu==72">
            <llanta></llanta>
          </template>
          <template v-if="menu==73">
            <repuesto></repuesto>
          </template>
          <template v-if="menu==3">
            <usuario></usuario>
          </template>
          <template v-if="menu==4">
            <rol></rol>
          </template>
          <template v-if="menu==6">
            <logout></logout>
          </template>
          <template v-if="menu==50">
            <bitacora></bitacora>
          </template>

          @elseif(Auth::user()->idrol==2)
          <template v-if="menu==70">
            <pedido :ruta="'http://vrctireswamp.com'"></pedido>
          </template>
          <template v-if="menu==71">
            <cliente></cliente>
          </template>
          <template v-if="menu==72">
            <llanta></llanta>
          </template>
          <template v-if="menu==73">
            <repuesto></repuesto>
          </template>
          
          @elseif(Auth::user()->idrol==3)
          <template v-if="menu==70">
            <pedido></pedido>
            </template>
            <template v-if="menu==71">
            <cliente></cliente>
            </template>
          @else
          @endif
  @endif

  <input class="form-control" type="hidden" name="" id="rol-admin" value="{{$rol}}">
</div>
@endsection