/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import Vuetify from './plugins/vuetify';
//import 'material-design-icons-iconfont/dist/material-design-icons.css'
import Vuelidate from './plugins/vuelidate';
import router from './routes';
import common from './mixins/common'
import store from "./store";

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('App', require('./components/App.vue').default);
Vue.component('TheAdminLayout', require('./pages/admin/TheAdminLayout.vue').default);


Vue.mixin(common)
//Vue.use(TheNotifier)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    vuetify:Vuetify,
    router,
    store,
    el: '#app'
});
