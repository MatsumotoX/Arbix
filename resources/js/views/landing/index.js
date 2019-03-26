require('../../bootstrap.js');

window.Vue = require('vue');

Vue.component('home', require('../../components/Home').default);
Vue.component('cryptovationx', require('../../components/CryptovationX').default);
Vue.component('cryptovationx2', require('../../components/CryptovationX2').default);
Vue.component('ava', require('../../components/Ava').default);
Vue.component('ava2', require('../../components/Ava2').default);

Vue.component('loader', require('../../components/Loader.vue').default);