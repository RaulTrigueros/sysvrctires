<template>
  <main class="">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/main">Escritorio</a></li>
    </ol>
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Pedidos
          <button
            type="button"
            @click="mostrarDetalle()"
            class="btn btn-secondary"
          >
            <i class="fa fa-plus-square"></i>&nbsp;Nuevo
          </button>
        </div>
        <!-- Listado-->
        <template v-if="listado == 1">
          <div class="card-body">
            <div class="form-group row">
              <div class="col-md-6">
                <div class="input-group">
                  <select class="form-control col-md-3" v-model="criterio">
                    <option value="codigo_persona">Codigo de Cliente</option>
                    <option value="nombre">Nombre de Cliente</option>
                    <option value="fecha_hora">Fecha-Hora</option>
                  </select>
                  <input
                    type="text"
                    v-model="buscar"
                    @keyup.enter="listarPedido(1, buscar, criterio)"
                    class="form-control"
                    placeholder="Texto a buscar"
                  />
                  <button
                    type="submit"
                    @click="listarPedido(1, buscar, criterio)"
                    class="btn btn-primary"
                  >
                    <i class="fa fa-search"></i> Buscar
                  </button>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th>Opciones</th>
                    <th>Codigo Cliente</th>
                    <th>Cliente</th>
                    <th>Tipo Cliente</th>
                    <th>Total a pagar ($)</th>
                    <th>Fecha Hora</th>
                    <th>Estado</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="pedido in arrayPedido" :key="pedido.id">
                    <td>
                      <button
                        type="button"
                        @click="verPedido(pedido.id)"
                        class="btn btn-info btn-sm"
                      >
                        <i class="fa fa-eye"></i>
                      </button>
                      &nbsp;
                      <button
                        type="button"
                        @click="pdfPedido(pedido.id)"
                        class="btn btn-info btn-sm"
                      >
                        <i class="fa fa-file-pdf-o"></i>
                      </button>
                      &nbsp;
                      <!--
                      <template v-if="pedido.estado">
                          <button type="button" class="btn btn-success btn-sm" @click="desactivarPedido(pedido.id)">
                            <i class="fa fa-money"></i>
                          </button>
                      </template>
                      <template v-else>
                          <button type="button" class="btn btn-info btn-sm" @click="activarPedido(pedido.id)">
                              <i class="fa fa-check"></i>
                          </button>
                      </template>-->
                      <button
                        class="btn btn-sm"
                        :class="pedido.estado == 1 ? 'btn-danger' : 'btn-success'"
                        @click="cambiarEstado(pedido.id)"
                      >
                        <!--{{ pedido.estado == 1 ? 'Desactivar' : 'Activar' }}-->
                        <i :class="pedido.estado == 1 ? 'fa fa-money' : 'fa fa-check'"></i>
                      </button>
                      &nbsp;
                      <button
                            type="button"
                            class="btn btn-danger btn-sm"
                            @click="eliminarPedido(pedido.id)"
                          >
                            <i class="fa fa-trash"></i>
                      </button>
                    </td>
                    <td v-text="pedido.codigo_persona"></td>
                    <td v-text="pedido.nombre"></td>
                    <td v-text="pedido.tipo_cliente"></td>
                    <td v-text="pedido.totalPagar"></td>
                    <td v-text="pedido.fecha_hora"></td>
                  <!--  <td class="align-middle">
                        <div v-if="pedido.estado">
                            <span class="badge badge-success">Pendiente</span>
                        </div>
                        <div v-else>
                            <span class="badge badge-danger">Entregado</span>
                        </div>
                    </td>-->
                    <td>
                      <span v-if="pedido.estado == 1" class="badge badge-danger">Pendiente</span>
                      <span v-else class="badge badge-success">Entregado</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <nav>
              <ul class="pagination">
                <li class="page-item" v-if="pagination.current_page > 1">
                  <a
                    class="page-link"
                    href="#"
                    @click.prevent="
                      cambiarPagina(
                        pagination.current_page - 1,
                        buscar,
                        criterio
                      )
                    "
                    >Ant</a
                  >
                </li>
                <li
                  class="page-item"
                  v-for="page in pagesNumber"
                  :key="page"
                  :class="[page == isActived ? 'active' : '']"
                >
                  <a
                    class="page-link"
                    href="#"
                    @click.prevent="cambiarPagina(page, buscar, criterio)"
                    v-text="page"
                  ></a>
                </li>
                <li
                  class="page-item"
                  v-if="pagination.current_page < pagination.last_page"
                >
                  <a
                    class="page-link"
                    href="#"
                    @click.prevent="
                      cambiarPagina(
                        pagination.current_page + 1,
                        buscar,
                        criterio
                      )
                    "
                    >Sig</a
                  >
                </li>
              </ul>
            </nav>
          </div>
        </template>
        <!--Fin Listado-->

        <!-- Agregar productos al Detalle de pedido-->
        <template v-else-if="listado == 0">
          <div class="card-body">
            <!-- Inicio cabecera de registro-->
            <div class="form-group row border">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="">Cliente(*)</label>
                  <v-select
                    @search="selectCliente"  
                    label="nombre"
                    :options="arrayCliente"
                    placeholder="Buscar Clientes..."
                    @input="getDatosCliente"
                  >
                  </v-select>
                </div>
                <div class="form-group">
                  <label for="">Tipo de Cliente</label>
                    <select v-model="tipo_cliente" class="form-control">
                      <option value="tallerista">Tallerista</option>
                      <option value="mayoreo">Mayoreo</option>
                      <option value="distribuidor">Distribuidor</option>
                      <option value="importador">Importador</option>
                    </select>
                </div>
              </div>
            </div>
            <!--FIN cabecera de registro-->

            <!--INICIO Obtener Detalle-->
            <div class="form-group row border">
              <div class="col-md-3">
                <div class="form-group">
                    <label>Seleccionar Código</label>
                    <select v-model="codigo" @change="buscarTipoproducto" class="form-control">
                      <option v-for="llanta in llantas" :key="llanta.codigo" :value="llanta.codigo">
                        {{ llanta.codigo }}
                      </option>
                    </select>

                    <label
                    >Cantidad
                    <span style="color: red" v-show="cantidad == 0"
                      >(*Ingrese)</span
                    ></label
                    >
                    <input
                      type="number"
                      value="0"
                      class="form-control"
                      v-model="cantidad"
                    />
                    <button @click="abrirModal()" class="btn btn-secondary">
                      ...
                    </button>
                 <!-- </div>-->
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label
                    >Tipo Producto</label
                  >
                  <input
                    type="text"
                    readonly
                    class="form-control"
                    v-model="tipoproducto"
                  />
                </div>
              </div>
              <div class="col-md-2" v-if="tipoproducto === 'llanta' || tipoproducto === 'tubo'">
                  <div class="form-group">
                    <label
                      >Medida</label
                    >
                    <input
                      type="text"
                      readonly
                      class="form-control"
                      v-model="medida"
                    />
                  </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label
                    >Precio</label
                  >
                  <input
                    type="number"
                    readonly
                    step="any"
                    class="form-control"
                    v-model="precio"
                  />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label
                    >Descripcion</label
                  >
                  <input
                    type="text"
                    readonly
                    class="form-control"
                    v-model="descripcion"
                  />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <button
                    @click="agregarDetalle()"
                    class="btn btn-success form-control btnagregar"
                  >
                    <i class="fa fa-plus-square"></i>
                  </button>
                </div>
              </div>
              <!--Mensajes de error-->
              <div class="col-md-12">
                <div v-show="errorPedido" class="form-group row div-error">
                  <div class="text-center text-error">
                    <div
                      v-for="error in errorMostrarMsjPedido"
                      :key="error"
                      v-text="error"
                    ></div>
                  </div>
                </div>
              </div>
            </div>
            <!--FIN Agregar productos al Detalle de pedido -->
            <!--Tabla de productos agregados al detalle-->
            <div class="form-group row border">
              <div class="table-responsive col-md-12">
                <table class="table table-bordered table-striped table-sm">
                  <thead>
                    <tr>
                      <th style="text-align: center;">Opciones</th>
                      <th style="text-align: center;">Codigo Producto</th>
                      <th style="text-align: center;">Tipo Producto</th>
                      <th style="text-align: center;">Medida</th>
                      <th style="text-align: center;">Descripción</th>
                      <th style="text-align: center;">Precio</th>
                      <th style="text-align: center;">Cantidad</th>
                      <th style="text-align: center;">Subtotal</th>
                    </tr>
                  </thead>
                  <tbody v-if="arrayDetalle.length">
                    <tr
                      v-for="(detalle, index) in arrayDetalle"
                      :key="detalle.id"
                    >
                      <td style="text-align: center">
                        <button
                          @click="eliminarDetalle(index)"
                          type="button"
                          class="btn btn-danger btn-sm"
                        >
                          <i class="fa fa-close"></i>
                        </button>
                      </td>
                      <td
                        style="text-align: center"
                        v-text="detalle.codigo"
                      ></td>
                      <td
                        style="text-align: center"
                        v-text="detalle.tipoproducto"
                      ></td>
                      <td
                        style="text-align: center"
                        v-text="detalle.medida"
                      ></td>
                      <td
                        style="text-align: center"
                        v-text="detalle.descripcion"
                      ></td>
                      <td
                        style="text-align: center"
                        v-text="detalle.precio"
                      ></td>
                      <td style="text-align: center">
                        <input style="text-align: center;"
                          v-model="detalle.cantidad"
                          type="number"
                          class="form-control"
                        />
                      </td>
                      <td colspan="2" style="text-align: center">
                        ${{(detalle.precio*detalle.cantidad).toFixed(2)}}
                      </td>
                    </tr>
                    <tr style="background-color: #CEECF5;">
                      <td colspan="7" align="right"><strong>Total Parcial:</strong></td>
                      <td style="text-align: center">$ {{ totalParcial.toFixed(2) }}</td>
                    </tr>
                    <tr style="background-color: #CEECF5;">
                      <td colspan="7" align="right"><strong>Descuento ({{ porcentajeDescuento }}%):</strong></td>
                      <td style="text-align: center">$ {{ calculoDescuento.toFixed(2) }}</td>
                    </tr>
                    <tr style="background-color: #CEECF5;">
                      <td colspan="7" align="right"><strong>Total a Pagar:</strong></td>
                      <td style="background-color: #f7ed17;" ><strong>$ {{ totalPagar.toFixed(2) }}</strong></td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr>
                      <td colspan="6">NO hay artículos agregados</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <!--FIN Tabla de productos agregados al detalle-->
            <div class="form-group row">
              <div class="col-md-12">
                <button
                  type="button"
                  @click="ocultarDetalle()"
                  class="btn btn-secondary"
                >
                  Cerrar
                </button>
                <button
                  type="button"
                  class="btn btn-primary"
                  @click="registrarPedido()"
                >
                  Registrar Pedido
                </button>
              </div>
            </div>
          </div>
        </template>
        <!-- Fin Detalle-->
         
        <!-- Ver pedido ingresado -->
        <template v-else-if="listado == 2">
          <div class="card-body">
            <div class="form-group row border">
              <div class="col-md-4">
                <div class="form-group">
                  <label for=""><strong>Nombre de Cliente</strong></label>
                  <p v-text="nombre"></p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for=""><strong>Codigo de Cliente</strong></label>
                  <p v-text="codigo_persona"></p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label><strong>NIT</strong></label>
                  <p v-text="nit"></p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label><strong>NRC</strong></label>
                  <p v-text="nrc"></p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label><strong>Giro</strong></label>
                  <p v-text="giro"></p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label><strong>Dirección</strong></label>
                  <p v-text="direccion"></p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label><strong>Telefono</strong></label>
                  <p v-text="telefono"></p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label><strong>Correo</strong></label>
                  <p v-text="email"></p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label><strong>Tipo de Cliente</strong></label>
                  <p v-text="tipo_cliente"></p>
                </div>
              </div>
              <div class="col-md-4">
                <label for=""><strong>Fecha y Hora</strong></label>
                <p v-text="fecha_hora"></p>
              </div>
            </div>
            <div class="form-group row border">
              <div class="table-responsive col-md-12">
                <table class="table table-bordered table-striped table-sm">
                  <thead>
                    <tr>
                      <th>Codigo Producto</th>
                      <th>Tipo Producto</th>
                      <th>Medida</th>
                      <th>Descripcion</th>
                      <th>Precio ($)</th>
                      <th>Cantidad</th>
                      <th colspan="3">Subtotal</th>
                    </tr>
                  </thead>
                  <tbody v-if="arrayDetalle.length">
                    <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                      <td v-text="detalle.codigo"></td>
                      <td v-text="detalle.tipoproducto"></td>
                      <td v-text="detalle.medida"></td>
                      <td v-text="detalle.descripcion"></td>
                      <td v-text="detalle.precio"></td>
                      <td v-text="detalle.cantidad"></td>
                      <td colspan="3" style="text-align: left">
                        ${{(detalle.precio*detalle.cantidad).toFixed(2)}}
                      </td>
                    </tr>
                    <tr style="background-color: #CEECF5;">
                      <td colspan="7" align="right"><strong>Total Parcial:</strong></td>
                      <td>$ {{ totalParcial.toFixed(2) }}</td>
                    </tr>
                    <tr style="background-color: #CEECF5;">
                      <td colspan="7" align="right"><strong>Descuento ({{ porcentajeDescuento }}%):</strong></td>
                      <td>$ {{ calculoDescuento.toFixed(2) }}</td>
                    </tr>
                    <tr style="background-color: #CEECF5;">
                      <td colspan="7" align="right"><strong>Total a Pagar:</strong></td>
                      <td style="background-color: #f7ed17;" ><strong>$ {{ totalPagar.toFixed(2) }}</strong></td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr>
                      <td colspan="5">NO hay artículos agregados</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-12">
                <button
                  type="button"
                  @click="ocultarDetalle()"
                  class="btn btn-secondary"
                >
                  Cerrar
                </button>
              </div>
            </div>
          </div>
        </template>
        <!-- fin ver pedido ingresado -->
      </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>

    <!--Inicio del modal agregar/actualizar (...)-->
    <div
      class="modal fade"
      tabindex="-1"
      :class="{ mostrar: modal }"
      role="dialog"
      aria-labelledby="myModalLabel"
      style="display: none"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-primary modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" v-text="tituloModal"></h4>
            <button
              type="button"
              class="close"
              @click="cerrarModal()"
              aria-label="Close"
            >
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group row">
              <div class="col-md-6">
                <div class="input-group">
                  <select class="form-control col-md-3" v-model="criterioA">
                    <option value="tipoproducto">Tipo de Producto</option>
                    <option value="descripcion">Descripción</option>
                    <option value="medida">Medida</option>
                    <option value="codigo">Código</option>
                  </select>
                  <input
                    type="text"
                    v-model="buscarA"
                    @keyup.enter="listarTipoproducto(buscarA, criterioA)"
                    class="form-control"
                    placeholder="Texto a buscar"
                  />
                  <button
                    type="submit"
                    @click="listarTipoproducto(buscarA, criterioA)"
                    class="btn btn-primary"
                  >
                    <i class="fa fa-search"></i> Buscar
                  </button>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th>Opciones</th>
                    <th>Código de Producto</th>
                    <th>Tipo de Producto</th>
                    <th>Descripcion</th>
                    <th>Precio ($)</th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="tipoproducto in arrayTipoproducto"
                    :key="tipoproducto.id"
                  >
                    <td>
                      <button
                        type="button"
                        @click="agregarDetalleModal(tipoproducto)"
                        class="btn btn-success btn-sm"
                      >
                        <i class="fa fa-check"></i>
                      </button>
                    </td>
                    <td v-text="tipoproducto.codigo"></td>
                    <td v-text="tipoproducto.tipoproducto"></td>
                    <td v-text="tipoproducto.descripcion"></td>
                    <td v-text="tipoproducto.precio"></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              @click="cerrarModal()"
            >
              Cerrar
            </button>
            <button
              type="button"
              v-if="tipoAccion == 1"
              class="btn btn-primary"
              @click="registrarPersona()"
            >
              Guardar
            </button>
            <button
              type="button"
              v-if="tipoAccion == 2"
              class="btn btn-primary"
              @click="actualizarPersona()"
            >
              Actualizar
            </button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->
  </main>
</template>

<script>
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css'; // importar los estilos
import axios from 'axios';
import Swal from 'sweetalert2';
export default {
  props: ['ruta'],
  data() {
    return {
      pedido_id: 0,
      persona_id: 0,
      nombre: '',
      tipo_cliente: 'tallerista',
      descuentos: {
        tallerista: 0, // %
        mayoreo: 7,    // %
        distribuidor: 14, // %
        importador: 20, // %
      },
      tipo_pago: 'CONTADO',
      codigo_persona: '',
      direccion: '',
      telefono: '',
      email:'',
      nit: "",
      nrc: "",
      giro: "",
      fecha_hora: '',
      pedidos: [], // Lista de pedidos
      arrayPedido: [],
      arrayCliente: [],
      arrayDetalle: [],
      listado: 1,
      modal: 0,
      tituloModal: '',
      tipoAccion: 0,
      errorPedido: 0,
      errorMostrarMsjPedido: [],
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
      },
      offset: 3,
      criterio: 'nombre',
      buscar: '',
      criterioA: 'codigo',
      buscarA: '',
      arrayTipoproducto: [],
      llantas: [], // Lista de productos cargada desde la API
      codigoSeleccionado: null, // Código seleccionado
      llanta_id: 0,
      codigo: '',
      tipoproducto: '',
      cantidad: 0,
      descripcion: '',
      precio: '',
      medida: '',
    };
  },
  components: {
    vSelect,
  },
  computed: {
    isActived: function () {
      return this.pagination.current_page;
    },
    //Calcula los elementos de la paginación
    pagesNumber: function () {
      if (!this.pagination.to) {
        return [];
      }

      var from = this.pagination.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }

      var to = from + this.offset * 2;
      if (to >= this.pagination.last_page) {
        to = this.pagination.last_page;
      }

      var pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    },
     // Total parcial calculado con los precios y cantidades
    totalParcial: function(){  
        var resultado=0.0;
        for(var i=0;i<this.arrayDetalle.length;i++){
            resultado=resultado+(this.arrayDetalle[i].precio*this.arrayDetalle[i].cantidad)
        }
        return resultado;
    },
    // Solo devuelve el porcentaje
    porcentajeDescuento() {
      return this.descuentos[this.tipo_cliente] || 0; 
    },
     // Calcula el descuento total como un porcentaje del total parcial
     calculoDescuento() {
      return (this.totalParcial * this.porcentajeDescuento) / 100;
    },
    totalPagar() {
      return this.totalParcial - this.calculoDescuento;
    },
  },

  methods: {
    listarPedido(page, buscar, criterio) {
      let me = this;
      var url =
        this.ruta +
        '/pedido?page=' +
        page +
        '&buscar=' +
        buscar +
        '&criterio=' +
        criterio;
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          me.arrayPedido = respuesta.pedidos.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    selectCliente(search, loading) {
      let me = this;
      loading(true);

       // Si no hay texto en el campo de búsqueda, carga todos los clientes
      let filtro = search || ''; // Si `search` es vacío, usa una cadena vacía
      var url = this.ruta + '/cliente/selectCliente?filtro=' + filtro;
      
      axios
        .get(url)
        .then(function (response) {
          console.log(response.data); // Verifica la estructura de la respuesta aquí
          let respuesta = response.data;
          q: search;
          me.arrayCliente = respuesta.clientes;
          loading(false);
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    getDatosCliente(val1) {
      let me = this;
      me.loading = true;
      me.persona_id = val1.id;
    },
    async cargarProductos() { //este metodo obtiene los productos por medio de su codigo desde la tabla Llanta
      try {
        const response = await axios.get('/llanta/selectLlanta'); 
        this.llantas = response.data.llantas; // Cargar códigos de llantas
      } catch (error) {
        console.error("Error al cargar productos:", error);
      }
    },
    seleccionarLlanta(codigo) {
      this.codigoSeleccionado = codigo; // Guardar el código seleccionado
    },
    buscarTipoproducto() {
      let me = this;
      var url =
        this.ruta + '/llanta/buscarTipoproductoPedido?filtro=' + me.codigo;

      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          me.arrayTipoproducto = respuesta.llantas;

          if (me.arrayTipoproducto.length > 0) {
            me.codigo = me.arrayTipoproducto[0]['codigo'];
            me.tipoproducto = me.arrayTipoproducto[0]['tipoproducto'];
            me.llanta_id = me.arrayTipoproducto[0]['id'];
            me.descripcion = me.arrayTipoproducto[0]['descripcion'];
            me.medida = me.arrayTipoproducto[0]['medida'];
            me.precio = me.arrayTipoproducto[0]['precio'];
          } else {
            me.tipoproducto = 'No existe artículo';
            me.llanta_id = 0;
          }
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    pdfPedido(id) {
      window.open(this.ruta + '/pedido/pdf/' + id + ',' + '_blank');
    },
    cambiarPagina(page, buscar, criterio) {
      let me = this;
      //Actualiza la página actual
      me.pagination.current_page = page;
      //Envia la petición para visualizar la data de esa página
      me.listarPedido(page, buscar, criterio);
    },
    encuentra(id) {
      var sw = 0;
      for (var i = 0; i < this.arrayDetalle.length; i++) {
        if (this.arrayDetalle[i].llanta_id == id) {
          sw = true;
        }
      }
      return sw;
    },
    eliminarDetalle(index) {
      let me = this;
      me.arrayDetalle.splice(index, 1);
    },
    agregarDetalle() {
      let me = this;

      // Validar si algún campo requerido está vacío
      if (!me.llanta_id || !me.codigo || !me.tipoproducto) {
        swal.fire({
          type: 'warning',
          title: 'Advertencia',
          text: 'Debe completar todos los campos obligatorios.',
        });
        return;
      }

      // Validar si la cantidad es cero o está vacía
      if (!me.cantidad || me.cantidad <= 0) {
        swal.fire({
          type: 'error',
          title: 'Upss!...',
          text: 'Ingrese la cantidad de productos!',
        });
        return;
      }

      // Validar si el producto ya está agregado
      if (me.encuentra(me.llanta_id)) {
        swal.fire({
          type: 'error',
          title: 'Upss!...',
          text: 'Ese producto ya se encuentra agregado!',
        });
        return;
      }

      // Si todas las validaciones pasan, agregar el detalle
      me.arrayDetalle.push({
        llanta_id: me.llanta_id,
        tipoproducto: me.tipoproducto,
        cantidad: me.cantidad,
        codigo: me.codigo,
        medida: me.medida,
        descripcion: me.descripcion,
        precio: me.precio,
      });

      // Reiniciar los campos después de agregar
      me.codigo = '';
      me.llanta_id = 0;
      me.tipoproducto = '';
      me.medida = '';
      me.cantidad = 0;
      me.descripcion = '';
      me.precio = '';
     
    },
    agregarDetalleModal(data = []) {
      let me = this;
      if (me.encuentra(data['id'])) {
        swal.fire({
          type: 'error',
          title: 'Error...',
          text: 'Ese producto ya se encuentra agregado!',
        });
      } else {
        me.arrayDetalle.push({
          llanta_id: data['id'],
          codigo: data['codigo'],
          tipoproducto: data['tipoproducto'],
          cantidad: 1,
          descripcion: data['descripcion'],
          medida: data['medida'],
          precio: data['precio'],
        });
         // Notificación de éxito al agregar el producto
          Swal.fire({
            type: 'success',
            title: 'Producto agregado',
            timer: 1000, // Duración de la notificación (en milisegundos)
            showConfirmButton: false, // Elimina el botón "OK"
          });
      }
    },
    listarTipoproducto(buscar, criterio) {
      let me = this;
      var url =
        this.ruta +
        '/llanta/listarTipoproductoPedido?buscar=' +
        buscar +
        '&criterio=' +
        criterio;
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          me.arrayTipoproducto = respuesta.llantas.data;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    registrarPedido() {
     /* if (this.validarPedido()) {
      return;
      }*/
      let me = this;

      // Validar si el campo cliente está vacío
      if (!me.persona_id) {
        Swal.fire({
          type: 'warning',
          title: 'Advertencia',
          text: 'No ha agregado un cliente.',
        });
        return;
      }
       // Validar si algún campo requerido está vacío
       if (me.arrayDetalle.length <= 0) {
        Swal.fire({
          type: 'warning',
          title: 'Advertencia',
          text: 'No existe detalle de pedido.',
        });
        return;
      }
      axios
        .post(this.ruta + '/pedido/registrar', {
          persona_id: this.persona_id,
          codigo_persona: this.codigo_persona,
          nombre: this.nombre,
          tipo_cliente: this.tipo_cliente,
         // nit: this.nit,
         // nrc: this.nrc,
         // giro: this.giro,
         // tipo_pago: this.tipo_pago,
         // fecha_hora: this.fecha_hora,
          direccion: this.direccion,
          telefono: this.telefono,
          totalPagar: this.totalPagar,
          data: this.arrayDetalle,
        })
        .then(function (response) {
          me.listado = 1;
          me.listarPedido(1, '', 'persona_id');
          me.persona_id = 0;
          me.codigo_persona = '';
          me.tipo_cliente = 'tallerista';
         // me.nit = '';
         // me.nrc = '';
         // me.giro = '';
          //me.tipo_pago = 'CONTADO';
         // me.fecha_hora = '';
          me.direccion = '';
          me.telefono = '';
          me.llanta_id = 0;
          me.codigo = '';
          me.tipoproducto = '';
          me.medida = '';
          me.precio = 0;
          me.cantidad = 0;
          me.totalPagar = 0.0;
          me.descripcion = '';
          me.arrayDetalle = [];
          window.open(
            this.ruta + '/pedido/pdf/' + response.data.id + ',' + '_blank'
          );
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    mostrarDetalle() {
      let me = this;
      me.listado = 0;

      me.persona_id = 0;
      me.codigo_persona = '';
      me.tipo_cliente = 'tallerista';
     /* me.nit = '';
      me.nrc = '';
      me.giro = '';*/
      //me.tipo_pago = 'CONTADO';
     // me.fecha_hora = '';
      me.direccion = '';
      me.telefono = '';
      me.llanta_id = 0;
      me.codigo = '';
      me.tipoproducto = '';
      me.cantidad = 0;
      me.totalPagar = 0.0;
      me.descripcion = '';
      me.medida = '';
      me.precio = 0;
      me.arrayDetalle = [];
    },
    ocultarDetalle() {
      this.listado = 1;
    },
    verPedido(id) {
      let me = this;
      me.listado = 2;

      //Obtener los datos del ingreso
      var arrayPedidoT = [];
      var url = this.ruta + '/pedido/obtenerCabecera?id=' + id;

      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          arrayPedidoT = respuesta.pedido;

          me.nombre = arrayPedidoT[0]['nombre'];
          me.codigo_persona = arrayPedidoT[0]['codigo_persona'];
          me.tipo_cliente = arrayPedidoT[0]['tipo_cliente'];
          me.nit = arrayPedidoT[0]['nit'];
          me.nrc = arrayPedidoT[0]['nrc'];
          me.giro = arrayPedidoT[0]['giro'];
          me.tipo_pago = arrayPedidoT[0]['tipo_pago'];
          me.fecha_hora = arrayPedidoT[0]['fecha_hora'];
          me.direccion = arrayPedidoT[0]['direccion'];
          me.telefono = arrayPedidoT[0]['telefono'];
          me.email = arrayPedidoT[0]['email'];
          me.totalPagar = arrayPedidoT[0]['totalPagar'];
        })
        .catch(function (error) {
          console.log(error);
        });

      //Obtener los datos de los detalles
      var urld = '/pedido/obtenerDetalles?id=' + id;

      axios
        .get(urld)
        .then(function (response) {
          console.log(response);
          var respuesta = response.data;
          me.arrayDetalle = respuesta.detalles;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    cerrarModal() {
      this.modal = 0;
      this.tituloModal = '';
    },
    abrirModal() {
      this.arrayTipoproducto = [];
      this.modal = 1;
      this.tituloModal = 'Seleccione uno o varios productos';
    },

    eliminarPedido(id) {
      Swal
        .fire({
          title: 'Esta seguro de anular este pedido?',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Aceptar',
          cancelButtonText: 'Cancelar',
          confirmButtonClass: 'btn btn-success',
          cancelButtonClass: 'btn btn-danger',
          buttonsStyling: false,
          reverseButtons: true,
        })
        .then((result) => {
          if (result.value) {
            let me = this;
            axios
              .put('/pedido/eliminar', {
                id: id,
              })
              .then(function (response) {

                me.listarPedido(1, '', 'persona_id');
                Swal.fire(
                  'Eliminado!',
                  'Registro de Pedido anulado con éxito!',
                  'success'
                );
              })
              .catch(function (error) {
                console.log(error);
              });
          } else if (
            // Read more about handling dismissals
            result.dismiss === Swal.DismissReason.cancel
          ) {
          }
        });
    },

    // Cambiar el estado de un pedido
    cambiarEstado(id) {
    let me = this;
    axios
      .patch(`${this.ruta}/pedido/${id}/cambiarEstado`)
      .then(function (response) {
        // Muestra un mensaje y actualiza la lista
       // alert(response.data.message);
        me.listarPedido(me.pagination.current_page, '', ''); // Actualiza el listado
      })
      .catch(function (error) {
        console.error("Error al cambiar el estado:", error);
      });
      Swal.fire({
        title: 'Estado cambiado',
        text: 'El estado del pedido ha sido actualizado.',
        icon: 'success',
        confirmButtonText: 'Aceptar'
      });
  },
 /*
   desactivarPedido(id) {
      Swal.fire({
        title: 'Marcar como pedido entregado?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar!',
        cancelButtonText: 'Cancelar',
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger',
        buttonsStyling: false,
        reverseButtons: true,
      }).then((result) => {
        if (result.value) {
          let me = this;
          axios
            .put(this.ruta + '/pedido/entregar', {
              'id': id,
            })
            .then(function (response) {
              me.listarPedido(1, '', 'persona_id');
              Swal.fire(
                'Realizado!',
                'El pedido ha sido entregado.',
                'success'
              );
            })
            .catch(function (error) {
              console.log(error);
            });
        } else if (
          // Read more about handling dismissals
          result.dismiss === Swal.DismissReason.cancel
        ) {
        }
      });
    },
    
    activarPedido(id){
                Swal.fire({
                title: 'Está seguro de Revertir el estado del pedido?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: "#d33",
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;
                    axios.put(this.ruta + '/pedido/anular',{    //se llama a la ruta para cambiar el estado del pedido
                        'id': id
                    }).then(function(response){
                        console.log(response.data); // Mostrar respuesta en la consola
                        me.listarPedido(1,'','persona_id');
                         Swal.fire(
                            'Realizado!',
                            'Este pedido no ha sido entregado!',
                            'success'
                            )
                    }).catch(function(error){
                        console.log(error);
                    });
                   
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                }
                })
    },
*/
  },
  
  mounted() {
    this.listarPedido(1, this.buscar, this.criterio);
    this.selectCliente('', () => {}); // Carga todos los clientes al iniciar
    this.cargarProductos();
    
  },
};
</script>
<style>
.modal-content {
  width: 100% !important;
  position: absolute !important;
}
.mostrar {
  display: list-item !important;
  opacity: 1 !important;
  position: absolute !important;
  background-color: #3c29297a !important;
}
.div-error {
  display: flex;
  justify-content: center;
}
.text-error {
  color: red !important;
  font-weight: bold;
}
@media (min-width: 600px) {
  .btnagregar {
    margin-top: 2rem;
  }
}


</style>
