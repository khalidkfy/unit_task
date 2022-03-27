/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
const Vue = require("vue");

window.Vue = require('vue').default;

import ElementUI from 'element-ui'
import locale from 'element-ui/lib/locale/lang/ar'

import 'element-ui/lib/theme-chalk/index.css';
import TableComponent from 'vue-table-component';

Vue.use(TableComponent, {
  tableClass: 'table table-bordered table-hover table-custom',
  filterNoResults: "No Data",
  filterInputClass: 'form-control m-input',
  filterPlaceholder: "Search",
  theadClass: 'font-weight-bold text-center',
  tbodyClass: 'font-weight-bold text-center'

});
import myMixin from './mixins'

Vue.mixin(myMixin);
Vue.use(ElementUI, { locale })

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('fg-input', require('./components/Input').default);
Vue.component('fg-textarea', require('./components/Textarea').default);
Vue.component('img-upload', require('./components/ImageUpload').default);
Vue.component('multi-imgs', require('./components/custom-multi-files-upload').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


require('./components/home/index')
require('./components/home/form')

const app = new Vue({
  el: '#app',
  components: {
    TableComponent
  }
});
