/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import DateFilter from './filtros/date'

require('./bootstrap');

window.Vue = require('vue');

import VueFormWizard from 'vue-form-wizard'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'
Vue.use(VueFormWizard)
/**

 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('energia', require('./components/Energia.vue').default);
Vue.component('cliente', require('./components/Cliente.vue').default);
Vue.component('llanta', require('./components/Llanta.vue').default);

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
        idProyecto: 0
    }
});
export default app;
