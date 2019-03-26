require('../../bootstrap.js');

window.Vue = require('vue');

import {GridPlugin} from "@syncfusion/ej2-vue-grids";
Vue.use(GridPlugin);
// Vue.component('alert', require('../../../components/Alert.vue'));
Vue.component('loader', require('../../components/Loader.vue').default);
Vue.component('spinner', require('vue-spinner-component/src/Spinner.vue').default);
Vue.component('allpurpose-grid', require('../../components/grids/AllPurposeGrid').default);
Vue.component('notify', require('../../components/Notification').default);
Vue.component('eec-inputbox', require('../../components/Inputbox.vue').default);
