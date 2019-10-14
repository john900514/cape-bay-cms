/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.Vuetify = require('vuetify');
import "vuetify/dist/vuetify.min.css";

Vue.use(Vuetify);

const opts = {};

export default new Vuetify(opts)

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('sidebar', require('./components/sidebar/SidebarComponent.vue').default);
Vue.component('wizard', require('./components/sidebar/WizardComponent.vue').default);

Vue.component('push-mobile-table', require('./components/tables/MobilePushNotesTableComponent.vue').default);
Vue.component('push-wallet-table', require('./components/tables/WalletPushNotesTableComponent.vue').default);

Vue.component('dashboard', require('./components/menus/dashboardMenu.vue').default);
Vue.component('push-notifications-menu', require('./components/menus/pushNotesMenu.vue').default);
Vue.component('grand-select', require('./components/menus/grandSelectComponent.vue').default);

// Widgets
Vue.component('info-box', require('./components/widgets/InfoBoxComponenet.vue').default);
Vue.component('info-box-grid', require('./components/widgets/InfoBoxGridComponent.vue').default);
Vue.component('pie-chart', require('./components/widgets/PieChartComponent.vue').default);
Vue.component('pie-chart-widget', require('./components/widgets/PieChartWidgetComponent.vue').default);
Vue.component('metered-info-grid', require('./components/widgets/MeteredInfoGridComponent.vue').default);
Vue.component('recent-added-list', require('./components/widgets/RecentlyAddedComponent.vue').default);

Vue.component('unauthorized', require('./components/widgets/UnAuthorizedComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const vueApp = new Vue({
    el: '#app',
    vuetify : new Vuetify({
        icons: {
            iconfont: 'fa', // 'mdi' || 'mdiSvg' || 'md' || 'fa' || 'fa4'
        },
    }),
    data() {
        return {
            anchorCMS
        };
    },
    computed: {
        backpackUser() {
            return this.anchorCMS.backpackUser;
        }
    },
    mounted() {
        console.log('AnchorCMS/vueJS Loaded!')
    }
});
