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
Vue.component('spinner', require('vue-spinner-component/src/Spinner.vue').default);
Vue.component('allpurpose-grid', require('../../components/grids/AllPurposeGrid').default);
Vue.component('lineaction-grid', require('../../components/grids/lines/LineActionGrid').default);
Vue.component('linearea-grid', require('../../components/grids/lines/LineAreaGrid').default);
Vue.component('linecomponent-grid', require('../../components/grids/lines/LineComponentGrid').default);
Vue.component('linequickreply-grid', require('../../components/grids/lines/LineQuickReplyGrid').default);
Vue.component('tab', require('../../components/Tab.vue').default);
Vue.component('tabs', require('../../components/Tabs.vue').default);
Vue.component('notify', require('../../components/Notification').default);
