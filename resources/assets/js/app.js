
import DateFilter from './filtros/date'

require('./bootstrap');

window.Vue = require('vue');

import VueFormWizard from 'vue-form-wizard'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'
//import Swal from 'sweetalert2'
//Vue.prototype.$Swal = Swal


Vue.use(VueFormWizard)

Vue.component('pedido', require('./components/Pedido.vue').default);
Vue.component('cliente', require('./components/Cliente.vue').default);
Vue.component('llanta', require('./components/Llanta.vue').default);
Vue.component('repuesto', require('./components/Repuesto.vue').default);

Vue.component('rol', require('./components/Rol.vue').default);
Vue.component('usuario', require('./components/User.vue').default);

//**** Componentes vue para Bitacora ****
Vue.component('bitacora', require('./components/Bitacora.vue').default);

Vue.filter('fecha', DateFilter);

//**** Componentes vue para menu 26 ****
Vue.component('menu26', require('./components/menu26.vue').default);
//***************************************


const app = new Vue({
    el: '#app',
    data: {
        menu: 0,
        idProyecto: 0,
    }
});
export default app;
