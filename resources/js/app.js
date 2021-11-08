window.Vue = require('vue').default;

Vue.component('register-form', require('./components/RegisterForm.vue').default);

const app = new Vue({
    el: '#app',
});
