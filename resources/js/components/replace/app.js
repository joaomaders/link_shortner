window.Vue = require('vue').default;

import { library } from '@fortawesome/fontawesome-svg-core'
import { faUserSecret } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

library.add(faUserSecret)

Vue.component('font-awesome-icon', FontAwesomeIcon)


Vue.component('main-app', require('./components/MainApp.vue').default);
Vue.component('register-form', require('./components/RegisterForm.vue').default);
Vue.component('recent-links', require('./components/RecentLinks.vue').default);

const app = new Vue({
    el: '#app',
});
