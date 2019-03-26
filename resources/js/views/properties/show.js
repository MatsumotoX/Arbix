require('../../bootstrap.js');

window.Vue = require('vue');

import SweetModal from 'sweet-modal-vue/src/plugin.js';

Vue.use(SweetModal);

// Syncfusion

import {GridPlugin} from "@syncfusion/ej2-vue-grids";
Vue.use(GridPlugin);

// Vue Component
Vue.component('alert', require('../../components/Alert.vue').default);
Vue.component('loader', require('../../components/Loader.vue').default);
Vue.component('eec-inputbox', require('../../components/Inputbox.vue').default);
Vue.component('eec-button', require('../../components/Button').default);
Vue.component('spinner', require('vue-spinner-component/src/Spinner.vue').default);
Vue.component('showgrid', require('../../components/grids/properties/ShowGrid').default);
Vue.component('notify', require('../../components/Notification').default);
