<div class="sidebar">
  <nav class="sidebar-nav">
    <ul class="nav">
      <li class="nav-item">
        <a class="nav-link" href="#" @click="menu=0">
          <i class="nav-icon fa fa-dashboard"></i> MENU
          <!-- <span class="badge badge-info">NEW</span-->
        </a>
      </li>
      <li class="nav-item nav-dropdown" >
        <a class="nav-link nav-dropdown-toggle" href="#">
          <i class="nav-icon fa fa-handshake-o"></i>Gesti&oacuten de tienda</a>
        <ul class="nav-dropdown-items">
          <li class="nav-item">
            <a class="nav-link" @click="menu=70" href="#">
              <i class="nav-icon fa fa-handshake-o"></i>Pedidos</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" @click="menu=71" href="#">
              <i class="nav-icon fa fa-handshake-o"></i>Clientes</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" @click="menu=72" href="#">
              <i class="nav-icon fa fa-handshake-o"></i>Productos</a>
          </li>
         <!-- <li class="nav-item">
            <a class="nav-link" @click="menu=73" href="#">
              <i class="nav-icon fa fa-handshake-o"></i>Repuestos</a>
          </li>-->
        </ul>
      </li>
    <!-- <li class="nav-title">ACCESO</li>-->
      <li class="nav-item nav-dropdown" >  <!--id="nav-admin" -->
        <a class="nav-link nav-dropdown-toggle" href="#">
          <i class="nav-icon fa fa-user"></i> Acceso</a>
        <ul class="nav-dropdown-items">

          <li class="nav-item">
            <a class="nav-link" @click="menu=3" href="#">
              <i class="nav-icon fa fa-user"></i>Usuarios</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" @click="menu=4" href="#">
              <i class="nav-icon fa fa-user"></i>Roles</a>
          </li>

        </ul>
      </li>
      
  <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#">
          <i class="nav-icon fa fa-book"></i>Bitacora</a>
        <ul class="nav-dropdown-items">
          <li class="nav-item">
            <a class="nav-link" @click="menu=50" href="#">
              <i class="nav-icon fa fa-book"></i>Bitacora</a>
          </li>
        </ul>
      </li>

 
  </nav>
  <button class="sidebar-minimizer brand-minimizer" type="button"> </button>
</div>