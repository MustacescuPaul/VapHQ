/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue");
import Buefy from "buefy";

Vue.use(Buefy);
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

Vue.component("categ-menu", require("./components/casa/categMenu.vue"));
Vue.component("cash-card", require("./components/casa/cashcard.vue"));
Vue.component("casa-index", require("./components/casa/casaIndex.vue"));
Vue.component("cart", require("./components/casa/cart.vue"));
Vue.component("product-list", require("./components/casa/productList.vue"));
Vue.component("date-client", require("./components/casa/dateClient.vue"));

Vue.component("stoc-index", require("./components/stoc/stocIndex.vue"));
Vue.component("stock-list", require("./components/stoc/stockList.vue"));
Vue.component("stoc-sidebar", require("./components/stoc/stocSidebar.vue"));
Vue.component("modificari-list", require("./components/stoc/modificariList.vue"));

Vue.component("monetar-index", require("./components/monetar/monetarIndex.vue"));
Vue.component("monetar-sidebar", require("./components/monetar/monetarSidebar.vue"));
Vue.component("monetar-casa", require("./components/monetar/monetarCasa.vue"));

Vue.component("sertar-index", require("./components/sertar/sertarIndex.vue"));
Vue.component("sertar-afisare", require("./components/sertar/sertarAfisare.vue"));
Vue.component("retragere-depunere", require("./components/sertar/retragereDepunere.vue"));
Vue.component("depunere-banca", require("./components/sertar/depunereBanca.vue"));
Vue.component("sertar-sidebar", require("./components/sertar/sertarSidebar.vue"));

Vue.component("redirect-index", require("./components/redirect/redirectIndex.vue"));
Vue.component("redirect-menu", require("./components/redirect/redirectMenu.vue"));

Vue.component("comanda-list", require("./components/comanda/comandaList.vue"));
Vue.component("comanda-sidebar", require("./components/comanda/comandaSidebar.vue"));
Vue.component("comanda-index", require("./components/comanda/comandaIndex.vue"));
Vue.component("comanda-cos", require("./components/comanda/comandaCos.vue"));
Vue.component("comanda-istoric", require("./components/comanda/comandaIstoric.vue"));
Vue.component("asteptare-cos", require("./components/comanda/asteptareCos.vue"));

Vue.component("garantii-index", require("./components/garantii/garantiiIndex.vue"));
Vue.component("date-intrare", require("./components/garantii/dateIntrare.vue"));
Vue.component("bon-deschis", require("./components/garantii/bonDeschis.vue"));
Vue.component("intrare-produs", require("./components/garantii/intrareProdus.vue"));
Vue.component("garantii-intrate", require("./components/garantii/garantiiIntrate.vue"));
Vue.component("detalii-produse", require("./components/garantii/detaliiProduse.vue"));

Vue.component("admin-index", require("./components/admin/adminIndex.vue"));
Vue.component("admin-sidebar", require("./components/admin/adminSidebar.vue"));
Vue.component("admin-content", require("./components/admin/adminContent.vue"));
Vue.component("admin-accounts", require("./components/admin/adminAccounts.vue"));
Vue.component("accounts-settings", require("./components/admin/accountsSettings.vue"));
Vue.component("admin-service", require("./components/admin/adminService.vue"));

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
    el: "#app",
    data: {
        menu: [],
        cart: []
    }
});
