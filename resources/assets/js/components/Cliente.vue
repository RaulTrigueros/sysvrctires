<template>
    <main class="">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Clientes
                    <button
                        type="button"
                        @click="abrirModal('persona', 'registrar')"
                        class="btn btn-secondary"
                    >
                        <i class="fa fa-plus-square"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
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
                                    @keyup.enter="
                                        listarPersona(1, buscar, criterio)
                                    "
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
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Codigo</th>
                                <th>Nombre</th>
                                <th>DUI</th>
                                <th>NIT</th>
                                <th>NRC</th>
                                <th>Giro</th>
                                <th>Dirección</th>
                                <th>Teléfono</th>
                                <th>Correo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="persona in arrayPersona"
                                :key="persona.id"
                            >
                                <td>
                                    <button
                                        type="button"
                                        @click="
                                            abrirModal(
                                                'persona',
                                                'actualizar',
                                                persona
                                            )
                                        "
                                        class="btn btn-warning btn-sm"
                                    >
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-danger btn-sm"
                                        @click="eliminarPersona(persona.id)"
                                    >
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                                <td v-text="persona.codigo"></td>
                                <td v-text="persona.nombre"></td>
                                <td v-text="persona.dui"></td>
                                <td v-text="persona.nit"></td>
                                <td v-text="persona.nrc"></td>
                                <td v-text="persona.giro"></td>
                                <td v-text="persona.direccion"></td>
                                <td v-text="persona.telefono"></td>
                                <td v-text="persona.email"></td>
                            </tr>
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li
                                class="page-item"
                                v-if="pagination.current_page > 1"
                            >
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
                                    @click.prevent="
                                        cambiarPagina(page, buscar, criterio)
                                    "
                                    v-text="page"
                                ></a>
                            </li>
                            <li
                                class="page-item"
                                v-if="
                                    pagination.current_page <
                                    pagination.last_page
                                "
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
                                <label
                                    class="col-md-3 form-control-label"
                                    for="text-input"
                                    >Nombre (*)</label
                                >
                                <div class="col-md-9">
                                    <input
                                        type="text"
                                        v-model="nombre"
                                        class="form-control"
                                        placeholder="Nombre de la persona o empresa"
                                    />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label
                                    class="col-md-3 form-control-label"
                                    for="text-input"
                                    >DUI</label
                                >
                                <div class="col-md-9">
                                    <input
                                        type="text"
                                        v-model="dui"
                                        class="form-control"
                                        placeholder="Número de Documento Unico de Identidad"
                                    />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label
                                    class="col-md-3 form-control-label"
                                    for="text-input"
                                    >NIT</label
                                >
                                <div class="col-md-9">
                                    <input
                                        type="text"
                                        v-model="nit"
                                        class="form-control"
                                        placeholder="Número de Identificacion Tributaria"
                                    />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label
                                    class="col-md-3 form-control-label"
                                    for="text-input"
                                    >NRC</label
                                >
                                <div class="col-md-9">
                                    <input
                                        type="text"
                                        v-model="nrc"
                                        class="form-control"
                                        placeholder="Número de Registro de Contribuyentes"
                                    />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label
                                    class="col-md-3 form-control-label"
                                    for="text-input"
                                    >Giro</label
                                >
                                <div class="col-md-9">
                                    <input
                                        type="text"
                                        v-model="giro"
                                        class="form-control"
                                        placeholder="Giro de la empresa"
                                    />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label
                                    class="col-md-3 form-control-label"
                                    for="email-input"
                                    >Dirección</label
                                >
                                <div class="col-md-9">
                                    <input
                                        type="text"
                                        v-model="direccion"
                                        class="form-control"
                                        placeholder="Dirección"
                                    />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label
                                    class="col-md-3 form-control-label"
                                    for="email-input"
                                    >Teléfono</label
                                >
                                <div class="col-md-9">
                                    <input
                                        type="text"
                                        v-model="telefono"
                                        class="form-control"
                                        placeholder="Teléfono"
                                    />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label
                                    class="col-md-3 form-control-label"
                                    for="email-input"
                                    >Correo</label
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
                            <div
                                v-show="errorPersona"
                                class="form-group row div-error"
                            >
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
    data() {
        return {
            persona_id: 0,
            codigo: "",
            nombre: "",
            dui: "",
            nit: "",
            nrc: "",
            giro: "",
            direccion: "",
            telefono: "",
            email: "",
            arrayPersona: [],
            modal: 0,
            tituloModal: "",
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
            criterio: "nombre",
            buscar: "",
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
                "/cliente?page=" +
                page +
                "&buscar=" +
                buscar +
                "&criterio=" +
                criterio;
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
                .post("/cliente/registrar", {
                    nombre: this.nombre,
                    dui: this.dui,
                    nit: this.nit,
                    nrc: this.nrc,
                    giro: this.giro,
                    direccion: this.direccion,
                    telefono: this.telefono,
                    email: this.email,
                })
                .then(function (response) {
                    me.cerrarModal();
                    me.listarPersona(1, "", "nombre");
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        actualizarPersona() {
            if (this.validarPersona()) {
                return;
            }

            let me = this;

            axios
                .put("/cliente/actualizar", {
                    codigo: this.codigo,
                    nombre: this.nombre,
                    dui: this.dui,
                    nit: this.nit,
                    nrc: this.nrc,
                    giro: this.giro,
                    direccion: this.direccion,
                    telefono: this.telefono,
                    email: this.email,
                    id: this.persona_id,
                })
                .then(function (response) {
                    me.cerrarModal();
                    me.listarPersona(1, "", "nombre");
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        eliminarPersona(id) {
            swal.fire({
                title: "Esta seguro de eliminar a este cliente?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Aceptar",
                cancelButtonText: "Cancelar",
                confirmButtonClass: "btn btn-success",
                cancelButtonClass: "btn btn-danger",
                buttonsStyling: false,
                reverseButtons: true,
            }).then((result) => {
                if (result.value) {
                    let me = this;
                    axios
                        .put("/cliente/eliminar", {
                            id: id,
                        })
                        .then(function (response) {
                            me.listarPersona(1, "", "nombre");
                            swal.fire(
                                "Eliminado!",
                                "El cliente ha sido eliminado con éxito.",
                                "success"
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

        validarPersona() {
            this.errorPersona = 0;
            this.errorMostrarMsjPersona = [];

            if (!this.nombre) {
                this.errorMostrarMsjPersona.push("El nombre de la persona no puede estar vacío.");
            }
            if (this.errorMostrarMsjPersona.length) this.errorPersona = 1;

            if (this.dui && this.dui.length > 10) {
                this.errorMostrarMsjPersona.push("El numero de DUI no puede tener mas de 10 digitos.");
            }
            if (this.errorMostrarMsjPersona.length) this.errorPersona = 1;

            if (this.nit && this.nit.length > 17) {
                this.errorMostrarMsjPersona.push("El numero de NIT no puede tener mas de 17 digitos.");
            }
            if (this.errorMostrarMsjPersona.length) this.errorPersona = 1;

            if (this.nrc && this.nrc.length > 17) {
                this.errorMostrarMsjPersona.push("El numero de NRC no puede tener mas de 17 digitos.");
            }
            if (this.errorMostrarMsjPersona.length) this.errorPersona = 1;

            return this.errorPersona;
        },
        cerrarModal() {
            this.modal = 0;
            this.tituloModal = "";
            this.codigo = "";
            this.nombre = "";
            this.dui = "";
            this.nit = "";
            this.nrc = "";
            this.giro = "";
            this.direccion = "";
            this.telefono = "";
            this.email = "";
            this.errorPersona = 0;
        },
        abrirModal(modelo, accion, data = []) {
            switch (modelo) {
                case "persona": {
                    switch (accion) {
                        case "registrar": {
                            this.modal = 1;
                            this.tituloModal = "Registrar Cliente";
                            this.nombre = "";
                            this.dui = "";
                            this.nit = "";
                            this.nrc = "";
                            this.giro = "";
                            this.direccion = "";
                            this.telefono = "";
                            this.email = "";
                            this.tipoAccion = 1;
                            break;
                        }
                        case "actualizar": {
                            //console.log(data);
                            this.modal = 1;
                            this.tituloModal = "Actualizar Cliente";
                            this.tipoAccion = 2;
                            this.persona_id = data["id"];
                            this.codigo = data["codigo"];
                            this.nombre = data["nombre"];
                            this.dui = data["dui"];
                            this.nit = data["nit"];
                            this.nrc = data["nrc"];
                            this.giro = data["giro"];
                            this.direccion = data["direccion"];
                            this.telefono = data["telefono"];
                            this.email = data["email"];
                            break;
                        }
                    }
                }
            }
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
