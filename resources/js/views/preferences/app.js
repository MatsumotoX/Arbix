require('../../bootstrap.js');

window.Vue = require('vue');

import SweetModal from 'sweet-modal-vue/src/plugin.js';
Vue.use(SweetModal);

import {GridPlugin} from "@syncfusion/ej2-vue-grids";
Vue.use(GridPlugin);

import draggable from 'vuedraggable';

// Vue.component('draggable', { components: { draggable }, template: '#items_table', props: ['items'] });

Vue.component('alert', require('../../components/Alert.vue').default);
Vue.component('loader', require('../../components/Loader.vue').default);
Vue.component('eec-inputbox', require('../../components/Inputbox.vue').default);
Vue.component('spinner', require('vue-spinner-component/src/Spinner.vue').default);
Vue.component('v-select', require('../../components/Select.vue').default);
Vue.component('propertygrid', require('../../components/grids/preferences/VehicleProperty').default);
Vue.component('groupgrid', require('../../components/grids/preferences/VehicleGroup').default);
Vue.component('optiongrid', require('../../components/grids/preferences/VehicleOption').default);
Vue.component('preference-reorder', require('../../components/grids/preferences/PreferenceReorder').default);
Vue.component('reorder-list', require('../../components/grids/preferences/ReorderList').default);
Vue.component('tab', require('../../components/Tab.vue').default);
Vue.component('tabs', require('../../components/Tabs.vue').default);
Vue.component('notify', require('../../components/Notification').default);
