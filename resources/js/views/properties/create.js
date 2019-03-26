require('../../bootstrap.js');

window.Vue = require('vue');

import SweetModal from 'sweet-modal-vue/src/plugin.js';

Vue.use(SweetModal);

// Syncfusion

// Vue Component
Vue.component('alert', require('../../components/Alert.vue').default);
Vue.component('loader', require('../../components/Loader.vue').default);
Vue.component('eec-inputbox', require('../../components/Inputbox.vue').default);
Vue.component('eec-chip', require('../../components/Chip').default);
Vue.component('spinner', require('vue-spinner-component/src/Spinner.vue').default);
Vue.component('tab', require('../../components/Tab.vue').default);
Vue.component('tabs', require('../../components/Tabs.vue').default);
Vue.component('notify', require('../../components/Notification').default);

