<template>
  <div>
    <h1>Gestión de Pedidos</h1>
    <form @submit.prevent="guardarPedido">
      <!-- Formulario para los datos del cliente -->
      <div>
        <label>Cliente:</label>
        <select v-model="pedido.persona_id">
          <option
            v-for="persona in personas"
            :key="persona.id"
            :value="persona.id"
          >
            {{ persona.nombre }}
          </option>
        </select>
      </div>

      <div>
        <label>Tipo de Pago:</label>
        <select v-model="pedido.tipo_pago">
          <option value="contado">Contado</option>
          <option value="credito">Crédito</option>
        </select>
      </div>

      <div>
        <label>Tipo de Cliente:</label>
        <select v-model="pedido.tipo_cliente">
          <option value="tallerista">Tallerista</option>
          <option value="mayorista">Mayorista</option>
          <option value="distribuidor">Distribuidor</option>
          <option value="importador">Importador</option>
        </select>
      </div>

      <!-- Agregar productos -->
      <div v-for="(detalle, index) in pedido.detalles" :key="index">
        <label>Tipo de Producto:</label>
        <select
          v-model="detalle.tipo_producto"
          @change="cargarProducto(detalle, index)"
        >
          <option value="llanta">Llanta</option>
          <option value="tubo">Tubo</option>
          <option value="repuesto">Repuesto</option>
        </select>

        <label>Código del Producto:</label>
        <input
          type="text"
          v-model="detalle.codigo_producto"
          @blur="cargarProducto(detalle, index)"
        />

        <label>Nombre del Producto:</label>
        <input type="text" v-model="detalle.nombre_producto" disabled />

        <label>Cantidad:</label>
        <input type="number" v-model="detalle.cantidad" />
      </div>

      <button type="button" @click="agregarDetalle">Agregar Producto</button>
      <button type="submit">Guardar Pedido</button>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      personas: [],
      pedido: {
        persona_id: null,
        tipo_pago: 'contado',
        tipo_cliente: 'tallerista',
        detalles: [],
      },
    };
  },
  methods: {
    cargarPersonas() {
      axios.get('/api/cliente').then((response) => {
        this.personas = response.data;
      });
    },
    agregarDetalle() {
      this.pedido.detalles.push({
        tipo_producto: '',
        codigo_producto: '',
        nombre_producto: '',
        cantidad: 1,
      });
    },
    cargarProducto(detalle, index) {
      if (detalle.tipo_producto && detalle.codigo_producto) {
        axios
          .get('/producto/${detalle.tipo_producto}/${detalle.codigo_producto}')
          .then((response) => {
            this.pedido.detalles[index].nombre_producto =
              response.data.nombre || 'Sin nombre';
          })
          .catch((error) => {
            console.log('Producto no encontrado o error en la solicitud');
            this.pedido.detalles[index].nombre_producto =
              'Producto no encontrado';
          });
      }
    },
    guardarPedido() {
      axios
        .post('/api/pedidos', this.pedido)
        .then((response) => {
          console.log('Pedido guardado:', response.data);
          // Aquí puedes redirigir o limpiar el formulario
        })
        .catch((error) => {
          console.log('Error al guardar el pedido:', error);
        });
    },
  },
  created() {
    this.cargarPersonas();
  },
};
</script>
