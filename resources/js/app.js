/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//require('./bootstrap');

window.Vue = require('vue');

//import BootstrapVue from 'bootstrap-vue';
//Vue.use(BootstrapVue);
//import 'bootstrap/dist/css/bootstrap.css'
//import 'bootstrap-vue/dist/bootstrap-vue.css'

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('records-mgnt-component', require('./components/RecordsMgntComponent.vue').default);
Vue.component('records-repo-view-component', require('./components/RecordsRepoViewComponent.vue').default);
Vue.component('database-record-view-component', require('./components/dbViews/databaseView.vue').default);
Vue.component('data-report-view-component', require('./components/dbViews/databaseView.vue').default);

Vue.component('reporting-view-component', require('./components/reportingViewComponent.vue').default);
Vue.component('reporting-report-component', require('./components/reportingReportComponent.vue').default);

Vue.component('livetracking-dash-component', require('./components/liveTracking/dashComponent.vue').default);
Vue.component('livetracking-view-component', require('./components/liveTracking/viewComponent.vue').default);

Vue.component('main-repo-component', require('./components/dataRepository/mainRepoComponent.vue').default);
Vue.component('store-select-component', require('./components/dataRepository/storeSelectComponent.vue').default);

// Widgets
Vue.component('cool-counter-component', require('./components/widgets/CoolCounterComponent.vue').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
