<template>
  <div>
    <form @submit.prevent="guardarPedido">
      <div>
        <label>Cliente:</label>
        <select class="form-control" v-model="pedido.persona_id">
          <option value="0" disabled selected>Selecciona un Cliente</option>
          <option
            v-for="persona in arrayPersona"
            :key="persona.id"
            :value="persona.id"
            v-text="persona.nombre"
          ></option>
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

      <div v-for="(producto, index) in pedido.productos" :key="index">
        <label>Tipo de Producto:</label>
        <select v-model="producto.tipo_producto">
          <option value="LlantaTubo">Llanta/Tubo</option>
          <option value="Repuesto">Repuesto</option>
        </select>

        <label>Código de Producto:</label>
        <input type="text" v-model="producto.codigo_producto" />

        <label>Cantidad:</label>
        <input type="number" v-model="producto.cantidad" />

        <label>Precio:</label>
        <input type="number" v-model="producto.precio" />

        <button @click="removerProducto(index)">Eliminar</button>
      </div>

      <button @click="agregarProducto">Añadir Producto</button>
      <button type="submit">Guardar Pedido</button>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      personas: [], // Obtén la lista de personas desde la API
      pedidos: [], // Aquí se almacenarán los pedidos obtenidos
      arrayPersona: [],
      pedido: {
        persona_id: 0,
        tipo_pago: 'contado',
        tipo_cliente: 'tallerista',
        productos: [
          {
            tipo_producto: 'LlantaTubo',
            codigo_producto: '',
            cantidad: 0,
            precio: 0,
          },
        ],
      },
    };
  },
  methods: {
    agregarProducto() {
      this.pedido.productos.push({
        tipo_producto: 'LlantaTubo',
        codigo_producto: '',
        cantidad: 0,
        precio: 0,
      });
    },
    removerProducto(index) {
      this.pedido.productos.splice(index, 1);
    },
    guardarPedido() {
      axios
        .post('/pedidos/registrar', this.pedido)
        .then((response) => {
          console.log('Pedido guardado:', response.data);
          // Puedes redirigir o hacer algo más después de guardar
        })
        .catch((error) => {
          console.error('Error al guardar el pedido:', error);
          // Maneja los errores aquí
        });
    },
    obtenerPedidos() {
      axios
        .get('/pedidos')
        .then((response) => {
          this.pedidos = response.data;
        })
        .catch((error) => {
          console.error('Error al obtener los pedidos:', error);
        });
    },
    selectCliente() {
      let me = this;
      var url = '/persona/selectPersona';
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          me.arrayPersona = respuesta.personas;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
  },
  mounted() {
    this.obtenerPedidos();
    // Obtén la lista de personas desde la API
    this.selectCliente();
  },
};
</script>
