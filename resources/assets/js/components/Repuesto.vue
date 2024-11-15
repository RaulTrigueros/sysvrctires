<template>
  <main class="">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/main">Escritorio</a></li>
    </ol>
    <!--  <div>-->
    <div class="container-fluid">
      <div class="animated fadeIn">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <i class="fa fa-align-justify"></i> Gestión Repuestos
                <button
                  type="button"
                  @click="abrirModal('repuesto', 'registrar')"
                  class="btn btn-primary"
                >
                  &nbsp;<i class="fa fa-plus-square"></i>&nbsp;Nuevo
                </button>
                <div class="card-body">
                  <div class="form-group row">
                    <div class="col-md-8">
                      <div class="input-group">
                        <select
                          class="form-control col-md-3"
                          v-model="criterio"
                        >
                          <option value="codigo">Codigo</option>
                          <option value="nombre">Nombre</option>
                        </select>
                        <input
                          type="text"
                          v-model="buscar"
                          @keyup.enter="listarRepuesto(1, buscar, criterio)"
                          class="form-control"
                          placeholder="Texto a buscar"
                        />
                        <button
                          type="submit"
                          @click="listarRepuesto(1, buscar, criterio)"
                          class="btn btn-primary"
                        >
                          <i class="fa fa-search"></i>
                          Buscar
                        </button>
                      </div>
                    </div>
                  </div>
                  <br />
                  <!-- INICIO DEL LISTADO-->
                  <table class="table table-bordered table-striped table-sm">
                    <thead>
                      <tr>
                        <th>Opciones</th>
                        <th>Codigo</th>
                        <th>Nombre</th>
                        <th>Precio ($)</th>
                        <th>Descripcion</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="repuesto in arrayRepuesto" :key="repuesto.id">
                        <td>
                          <button
                            type="button"
                            @click="
                              abrirModal('repuesto', 'actualizar', repuesto)
                            "
                            class="btn btn-warning btn-sm"
                          >
                            <i class="fa fa-edit"></i>
                          </button>
                          <button
                            type="button"
                            class="btn btn-danger btn-sm"
                            @click="eliminarRepuesto(repuesto.id)"
                          >
                            <i class="fa fa-trash"></i>
                          </button>
                        </td>
                        <td class="align-middle" v-text="repuesto.codigo"></td>
                        <td class="align-middle" v-text="repuesto.nombre"></td>
                        <td class="align-middle" v-text="repuesto.precio"></td>
                        <td
                          class="align-middle"
                          v-text="repuesto.descripcion"
                        ></td>
                      </tr>
                    </tbody>
                  </table>
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
                          >Anterior</a
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
                          >Siguiente</a
                        >
                      </li>
                    </ul>
                  </nav>
                  <!-- FIN DEL LISTADO-->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--Inicio del modal agregar/actualizar-->
      <div
        class="modal"
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
              <form
                action=""
                method="post"
                enctype="multipart/form-data"
                class="form-horizontal"
              >
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="text-input"
                    >Codigo</label
                  >
                  <div class="col-md-9">
                    <input
                      type="text"
                      v-model="codigo"
                      class="form-control"
                      placeholder=""
                    />
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="text-input"
                    >Nombre</label
                  >
                  <div class="col-md-9">
                    <input
                      type="text"
                      v-model="nombre"
                      class="form-control"
                      placeholder=""
                    />
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="text-input"
                    >Precio ($)</label
                  >
                  <div class="col-md-9">
                    <input
                      step="0.01"
                      v-model="precio"
                      class="form-control"
                      placeholder="$0.00"
                    />
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="text-input"
                    >Descripcion</label
                  >
                  <div class="col-md-9">
                    <input
                      type="text"
                      v-model="descripcion"
                      class="form-control"
                      placeholder=""
                    />
                  </div>
                </div>
                <div v-show="errorRepuesto" class="form-group row div-error">
                  <div class="text-center text-error">
                    <div
                      v-for="error in errorMostrarMsjRepuesto"
                      :key="error"
                      v-text="error"
                    ></div>
                  </div>
                </div>
              </form>
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
                @click="registrarRepuesto()"
              >
                Guardar
              </button>
              <button
                type="button"
                v-if="tipoAccion == 2"
                class="btn btn-primary"
                @click="actualizarRepuesto()"
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
    </div>
  </main>
</template>

<script>
//import axios from 'axios'

export default {
  data() {
    return {
      repuesto_id: 0,
      codigo: '',
      nombre: '',
      precio: '',
      descripcion: '',
      arrayRepuesto: [],
      modal: 0,
      modal2: 0,
      tituloModal: '',
      tipoAccion: 0,
      errorRepuesto: 0,
      errorMostrarMsjRepuesto: [],
      //    bandImagenValida: false,
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
      },
      offset: 3,
      criterio: 'codigo',
      buscar: '',
    };
  },
  computed: {
    isActived: function () {
      return this.pagination.current_page;
    },
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
  },
  methods: {
    listarRepuesto(page, buscar, criterio) {
      let me = this;
      var url =
        '/repuesto?page=' +
        page +
        '&buscar=' +
        buscar +
        '&criterio=' +
        criterio;
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          me.arrayRepuesto = respuesta.repuestos.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function (error) {
          console.log(error);
        });
    },

    cambiarPagina(page, buscar, criterio) {
      let me = this;
      me.pagination.current_page = page;
      me.listarLlanta(page, buscar, criterio);
    },
    registrarRepuesto() {
      if (this.validarRepuesto()) {
        return;
      }

      let me = this;
      axios
        .post('/repuesto/registrar', {
          codigo: this.codigo,
          nombre: this.nombre,
          precio: this.precio,
          descripcion: this.descripcion,
        })
        .then(function (response) {
          me.cerrarModal();
          me.listarRepuesto(1, '', 'codigo');
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    actualizarRepuesto() {
      if (this.validarRepuesto()) {
        return;
      }

      let me = this;
      axios
        .put('/repuesto/actualizar', {
          codigo: this.codigo,
          nombre: this.nombre,
          precio: this.precio,
          descripcion: this.descripcion,
          id: this.repuesto_id,
        })
        .then(function (response) {
          me.cerrarModal();
          me.listarRepuesto(1, '', 'codigo');
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    eliminarRepuesto(id) {
      swal
        .fire({
          title: 'Esta seguro de eliminar este repuesto?',
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
              .put('/repuesto/eliminar', {
                id: id,
              })
              .then(function (response) {
                me.listarRepuesto(1, '', 'codigo');
                swal.fire(
                  'Eliminado!',
                  'Registro de Repuesto Eliminado con éxito!',
                  'success'
                );
              })
              .catch(function (error) {
                console.log(error);
              });
          } else if (
            // Read more about handling dismissals
            result.dismiss === swal.DismissReason.cancel
          ) {
          }
        });
    },
    validarRepuesto() {
      this.errorRepuesto = 0;
      this.errorMostrarMsjRepuesto = [];

      if (this.errorLRepuesto === 0) {
        if (!this.codigo)
          this.errorMostrarMsjRepuesto.push(
            'Debe ingresar un codigo de Repuesto'
          );
        if (this.errorMostrarMsjRepuesto.length) this.errorRepuesto = 1;
      }

      if (this.errorRepuesto === 0) {
        if (!this.nombre)
          this.errorMostrarMsjRepuesto.push(
            'Debe ingresar un nombre de Repuesto'
          );
        if (this.errorMostrarMsjRepuesto.length) this.errorRepuesto = 1;
      }

      if (this.errorRepuesto === 0) {
        if (!this.precio)
          this.errorMostrarMsjRepuesto.push('Debe ingresar precio de Repuesto');
        if (this.errorMostrarMsjRepuesto.length) this.errorRepuesto = 1;
      }

      return this.errorRepuesto;
    },

    cerrarModal() {
      this.modal2 = 0;
      this.modal = 0;
      this.tituloModal = '';
      this.codigo = '';
      this.nombre = '';
      this.precio = '';
      this.descripcion = '';
      this.errorRepuesto = 0;
    },
    abrirModal(modelo, accion, data = []) {
      switch (modelo) {
        case 'repuesto': {
          switch (accion) {
            case 'registrar': {
              this.modal = 1;
              this.tipoAccion = 1;
              this.tituloModal = 'Registrar Repuesto';
              this.codigo = '';
              this.nombre = '';
              this.precio = '';
              this.descripcion = '';
              break;
            }
            case 'actualizar': {
              this.modal = 1;
              this.tipoAccion = 2;
              this.tituloModal = 'Actualizar Repuesto';
              this.repuesto_id = data['id'];
              this.codigo = data['codigo'];
              this.nombre = data['nombre'];
              this.precio = data['precio'];
              this.descripcion = data['descripcion'];
              break;
            }
          }
        }
      }
    },
  },

  mounted() {
    this.listarRepuesto(1, this.buscar, this.criterio);
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
  overflow-y: scroll;
  opacity: 1 !important;
  position: absolute !important;
  background-color: #3c29297a !important;
}

.fechaPdf {
  display: list-item !important;
  overflow-y: scroll;
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
.img {
  width: 30%;
  margin: auto;
  display: block;
  margin-bottom: 10px;
}
</style>
