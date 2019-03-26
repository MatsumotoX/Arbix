require('../../bootstrap.js');

window.Vue = require('vue');

// import SweetModal from 'sweet-modal-vue/src/plugin.js';
// Vue.use(SweetModal);

import {GridPlugin} from "@syncfusion/ej2-vue-grids";
Vue.use(GridPlugin);

// Vue.component('alert', require('../../../components/Alert.vue'));
Vue.component('loader', require('../../components/Loader.vue').default);
// Vue.component('eec-inputbox', require('../../../components/Inputbox.vue'));
Vue.component('spinner', require('vue-spinner-component/src/Spinner.vue').default);
Vue.component('indexgrid', require('../../components/grids/properties/IndexGrid').default);
Vue.component('notify', require('../../components/Notification').default);

