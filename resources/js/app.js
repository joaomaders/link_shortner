window.Vue = require('vue').default;

import { library } from '@fortawesome/fontawesome-svg-core'
import { faSync, faCopy, faSearch, faChevronLeft } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'

import Vue from 'vue';

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.use(BootstrapVue);
Vue.use(IconsPlugin);

library.add(faSync, faCopy, faSearch, faChevronLeft);

Vue.component('font-awesome-icon', FontAwesomeIcon);
Vue.component('main-app', require('./components/MainApp.vue').default);
Vue.component('register-form', require('./components/RegisterForm.vue').default);
Vue.component('links-list', require('./components/LinksList.vue').default);
Vue.component('list-page', require('./components/ListPage.vue').default);

const app = new Vue({
    el: '#app',
});
