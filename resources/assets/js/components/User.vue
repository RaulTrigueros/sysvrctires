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
          <i class="fa fa-align-justify"></i> Usuarios
          <button
            type="button"
            @click="abrirModal('persona', 'registrar')"
            class="btn btn-primary"
          >
            <i class="fa fa-plus-square"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">
          <div class="form-group row">
            <div class="col-md-8">
              <div class="input-group">
                <select class="form-control col-md-3" v-model="criterio">
                  <option value="nombre">Nombre</option>
                </select>
                <input
                  type="text"
                  v-model="buscar"
                  @keyup.enter="listarPersona(1, buscar, criterio)"
                  class="form-control"
                  placeholder="Texto a buscar"
                />
                <button
                  type="submit"
                  @click="listarPersona(1, buscar, criterio)"
                  class="btn btn-primary"
                >
                  <i class="fa fa-search"></i> Buscar
                </button>
              </div>
            </div>
          </div>
          <table
            class="table table-bordered table-striped table-sm table-responsive"
          >
            <thead>
              <tr>
                <th style="vertical-align: middle">Opciones</th>
                <th style="vertical-align: middle">Nombre</th>
                <th style="vertical-align: middle">Teléfono</th>
                <th style="vertical-align: middle">Email</th>
                <th style="vertical-align: middle">Cargo</th>
                <th style="vertical-align: middle">Usuario</th>
                <th style="vertical-align: middle">Rol</th>
                <th style="vertical-align: middle">Estado</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="persona in arrayPersona" :key="persona.id">
                <td>
                  <button
                    type="button"
                    @click="abrirModal('persona', 'actualizar', persona)"
                    class="btn btn-warning btn-sm"
                  >
                    <i class="fa fa-edit"></i>
                  </button>
                  &nbsp;
                  <button
                        class="btn btn-sm"
                        :class="persona.condicion == 1 ? 'btn-success' : 'btn-danger'"
                        @click="cambiarEstado(persona.id)"
                      >
                        <i :class="persona.condicion == 1 ? 'fa fa-lock' : 'fa fa-check'"></i>
                  </button>
                </td>
                <td class="align-middle" v-text="persona.nombre"></td>
                <td class="align-middle" v-text="persona.telefono"></td>
                <td class="align-middle" v-text="persona.email"></td>
                <td class="align-middle" v-text="persona.cargo"></td>
                <td class="align-middle" v-text="persona.usuario"></td>
                <td class="align-middle" v-text="persona.rol"></td>
                <td>
                    <span v-if="persona.condicion == 1" class="badge badge-success">Activo</span>
                    <span v-else class="badge badge-danger">Inactivo</span>
                </td>
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
                    cambiarPagina(pagination.current_page - 1, buscar, criterio)
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
                    cambiarPagina(pagination.current_page + 1, buscar, criterio)
                  "
                  >Sig</a
                >
              </li>
            </ul>
          </nav>
        </div>
      </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal agregar/actualizar-->
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
            <form
              action=""
              method="post"
              enctype="multipart/form-data"
              class="form-horizontal"
            >
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input"
                  >Nombre(*)</label
                >
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="nombre"
                    class="form-control"
                    placeholder="Nombre de la persona"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input"
                  >Cargo</label
                >
                <div class="col-md-9">
                  <select v-model="cargo" class="form-control">
                    <option value="Gestor de venta">Gestor de venta</option>
                    <option value="Asistente de administrador de tienda">
                      Asistente de administrador de tienda
                    </option>
                    <option value="Bodega">Bodega</option>
                    <option value="CEO">CEO</option>
                    <option value="Despacho">Despacho</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="tel"
                  >Teléfono</label
                >
                <div class="col-md-9">
                  <input
                    type="tel"
                    v-model="telefono"
                    class="form-control"
                    placeholder="Teléfono"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input"
                  >Email</label
                >
                <div class="col-md-9">
                  <input
                    type="email"
                    v-model="email"
                    class="form-control"
                    placeholder="Email"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input"
                  >Rol</label
                >
                <div class="col-md-9">
                  <select v-model="idrol" class="form-control">
                    <option value="0" disabled>Seleccione</option>
                    <option
                      v-for="role in arrayRol"
                      :key="role.id"
                      :value="role.id"
                      v-text="role.nombre"
                    ></option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input"
                  >Usuario</label
                >
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="usuario"
                    class="form-control"
                    placeholder="Nombre del usuario"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input"
                  >password</label
                >
                <div class="col-md-9">
                  <input
                    type="password"
                    v-model="password"
                    class="form-control"
                    placeholder="password del usuario"
                  />
                </div>
              </div>
              <div v-show="errorPersona" class="form-group row div-error">
                <div class="text-center text-error">
                  <div
                    v-for="error in errorMostrarMsjPersona"
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
export default {
  props: ['ruta'],
  data() {
    return {
      persona_id: 0,
      nombre: '',
      telefono: '',
      email: '',
      cargo: '',
      usuario: '',
      password: '',
      idrol: '',
      arrayPersona: [],
      arrayRol: [],
      modal: 0,
      tituloModal: '',
      tipoAccion: 0,
      errorPersona: 0,
      errorMostrarMsjPersona: [],
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
    };
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
  },
  methods: {
    listarPersona(page, buscar, criterio) {
      let me = this;
      var url =
        '/user?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          me.arrayPersona = respuesta.personas.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    selectRol() {
      let me = this;
      var url = '/rol/selectRol';
      axios
        .get(url)
        .then(function (response) {
          //console.log(response);
          var respuesta = response.data;
          me.arrayRol = respuesta.roles;
        })
        .catch(function (error) {
          console.log(error);
        });
    },

    cambiarPagina(page, buscar, criterio) {
      let me = this;
      //Actualiza la página actual
      me.pagination.current_page = page;
      //Envia la petición para visualizar la data de esa página
      me.listarPersona(page, buscar, criterio);
    },
    registrarPersona() {
      if (this.validarPersona()) {
        return;
      }

      let me = this;

      axios
        .post('/user/registrar', {
          nombre: this.nombre,
          telefono: this.telefono,
          email: this.email,
          cargo: this.cargo,
          idrol: this.idrol,
          usuario: this.usuario,
          password: this.password,
        })
        .then(function (response) {
          me.cerrarModal();
          me.listarPersona(1, '', 'nombre');
        })
        .catch(function (error) {
          console.log(error);
          if (error.response && error.response.status === 409) {
            Swal.fire({
              icon: 'warning',
              title: 'Usuario existente',
              text: error.response.data.error,
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Ocurrió un error al registrar el usuario',
            });
          }
        });
    },
    actualizarPersona() {
      if (this.validarPersona()) {
        return;
      }

      let me = this;

      axios
        .put('/user/actualizar', {
          nombre: this.nombre,
          telefono: this.telefono,
          email: this.email,
          cargo: this.cargo,
          idrol: this.idrol,
          usuario: this.usuario,
          password: this.password,
          id: this.persona_id,
        })
        .then(function (response) {
          me.cerrarModal();
          me.listarPersona(1, '', 'nombre');
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    validarPersona() {
      this.errorPersona = 0;
      this.errorMostrarMsjPersona = [];

      if (!this.nombre)
        this.errorMostrarMsjPersona.push(
          'El nombre de la persona no puede estar vacío.'
        );
      if (!this.usuario)
        this.errorMostrarMsjPersona.push(
          'El nombre de usuario no puede estar vacío.'
        );
      if (!this.password)
        this.errorMostrarMsjPersona.push(
          'La password del usuario no puede estar vacía.'
        );
      if (this.idrol == 0)
        this.errorMostrarMsjPersona.push('Seleccione una Role.');
      if (this.errorMostrarMsjPersona.length) this.errorPersona = 1;

      return this.errorPersona;
    },
    cerrarModal() {
      this.modal = 0;
      this.tituloModal = '';
      this.nombre = '';
      this.cargo = 'CEO';
      this.telefono = '';
      this.email = '';
      this.usuario = '';
      this.password = '';
      this.idrol = 0;
      this.errorPersona = 0;
    },
    abrirModal(modelo, accion, data = []) {
      this.selectRol();
      switch (modelo) {
        case 'persona': {
          switch (accion) {
            case 'registrar': {
              this.modal = 1;
              this.tituloModal = 'Registrar Usuario';
              this.nombre = '';
              this.cargo = 'CEO';
              this.telefono = '';
              this.email = '';
              this.usuario = '';
              this.password = '';
              this.idrol = 0;
              this.tipoAccion = 1;
              break;
            }
            case 'actualizar': {
              //console.log(data);
              this.modal = 1;
              this.tituloModal = 'Actualizar Usuario';
              this.tipoAccion = 2;
              this.persona_id = data['id'];
              this.nombre = data['nombre'];
              this.cargo = data['cargo'];
              this.telefono = data['telefono'];
              this.email = data['email'];
              this.usuario = data['usuario'];
              this.password = data['password'];
              this.idrol = data['idrol'];
              break;
            }
          }
        }
      }
    },
    cambiarEstado(id) {
    let me = this;
    axios
      .patch(`/user/${id}/cambiarEstado`)
      .then(function (response) {
        // Muestra un mensaje y actualiza la lista
       // alert(response.data.message);
        me.listarPersona(me.pagination.current_page, '', ''); // Actualiza el listado
      })
      .catch(function (error) {
        console.error("Error al cambiar el estado:", error);
      });
      Swal.fire({
        title: 'Estado cambiado',
        text: 'El estado del usuario ha sido actualizado.',
        icon: 'success',
        confirmButtonText: 'Aceptar'
      });
    },

  },
  mounted() {
    this.listarPersona(1, this.buscar, this.criterio);
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
</style>
