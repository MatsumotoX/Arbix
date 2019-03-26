require('../../../bootstrap.js');

window.Vue = require('vue');

import SweetModal from 'sweet-modal-vue/src/plugin.js';

Vue.use(SweetModal);

import {GridPlugin} from "@syncfusion/ej2-vue-grids";
Vue.use(GridPlugin);

// Vue Component
Vue.component('contact-grid', require('../../../components/grids/hrs/customers/ContactGrid').default);
Vue.component('contract-grid', require('../../../components/grids/hrs/customers/ContractGrid').default);
Vue.component('tab', require('../../../components/Tab.vue').default);
Vue.component('tabs', require('../../../components/Tabs.vue').default);
Vue.component('loader', require('../../../components/Loader.vue').default);
Vue.component('eec-inputbox', require('../../../components/Inputbox.vue').default);
Vue.component('spinner', require('vue-spinner-component/src/Spinner.vue').default);
Vue.component('hc-contact', require('../../../components/hc/Contact.vue').default);
Vue.component('hc-contract', require('../../../components/hc/Contract.vue').default);
Vue.component('hc-location', require('../../../components/hc/Location.vue').default);
Vue.component('allpurpose-grid', require('../../../components/grids/AllPurposeGrid').default);
Vue.component('notify', require('../../../components/Notification').default);
