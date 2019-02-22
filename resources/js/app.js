
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import Buefy from 'buefy';

Vue.use(Buefy);
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

/*Vue.component('sidebarComp', require('./components/sidebarComp.vue'));
Vue.component('subCat', require('./components/subCat.vue'));*/
Vue.component('categ-menu', require('./components/categmenu.vue'));
Vue.component('cash-card', require('./components/cashcard.vue'));
Vue.component('casa-index', require('./components/casaIndex.vue'));
Vue.component('cart', require('./components/cart.vue'));
Vue.component('product-list', require('./components/productList.vue'));
Vue.component('date-client', require('./components/dateClient.vue'));
Vue.component('stoc-index', require('./components/stocIndex.vue'));

Vue.component('garantii-index', require('./components/garantii/garantiiIndex.vue'));
Vue.component('date-intrare', require('./components/garantii/dateIntrare.vue'));
Vue.component('bon-deschis', require('./components/garantii/bonDeschis.vue'));
Vue.component('intrare-produs', require('./components/garantii/intrareProdus.vue'));
Vue.component('garantii-intrate', require('./components/garantii/garantiiIntrate.vue'));
Vue.component('detalii-produse', require('./components/garantii/detaliiProduse.vue'));

Vue.component('admin-index', require('./components/admin/adminIndex.vue'));
Vue.component('admin-sidebar', require('./components/admin/adminSidebar.vue'));
Vue.component('admin-content', require('./components/admin/adminContent.vue'));
Vue.component('admin-accounts', require('./components/admin/adminAccounts.vue'));
Vue.component('accounts-settings', require('./components/admin/accountsSettings.vue'));
Vue.component('admin-service', require('./components/admin/adminService.vue'));

/*const files = require.context('./', true, /\.vue$/i)

files.keys().map(key => {
    return Vue.component(_.last(key.split('/')).split('.')[0], files(key))
})*/

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    data: {
    	menu: [],
    	cart: [],
    },
    
});
