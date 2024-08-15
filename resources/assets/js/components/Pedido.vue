<template>
  <div>
    <form @submit.prevent="guardarPedido">
      <div>
        <label>Cliente:</label>
        <select v-model="pedido.persona_id">
          <option v-for="persona in personas" :value="persona.id">
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
      pedido: {
        persona_id: null,
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
      // Llama a la API de Laravel para guardar el pedido
    },
  },
  mounted() {
    // Obtén la lista de personas desde la API
  },
};
</script>
