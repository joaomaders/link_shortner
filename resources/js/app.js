window.Vue = require('vue').default;

Vue.component('main-app', require('./components/MainApp.vue').default);
Vue.component('register-form', require('./components/RegisterForm.vue').default);
Vue.component('recent-links', require('./components/RecentLinks.vue').default);

const app = new Vue({
    el: '#app',
});
