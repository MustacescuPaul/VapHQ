<template>
  <div class="container is-fluid">
    <div class="columns">
      <div class="colum">
        <comanda-sidebar @search="search($event)" @getProd="getProdList($event)" :menu="menu"></comanda-sidebar>
      </div>
      <div class="column">
        <comanda-list v-if="show == 'products'" :viz_preturi="viz_preturi" :products="products"></comanda-list>
        <comanda-cos v-if="show == 'cos' && !response" :comanda="comanda"></comanda-cos>
        <asteptare-cos v-if="response" :response="response"></asteptare-cos>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: ["comanda", "response"],
  props: {
    response: Object,
    comanda: Object
  },
  data: function() {
    return {
      show: "cos",
      products: [],
      menu: [],
      cart: [],
      viz_preturi: ""
    };
  },
  methods: {
    getProdList: function(event) {
      axios.get("../comanda/lista_produse/" + event).then(response => {
        this.products = response.data.prods;
        this.viz_preturi = response.data.viz_preturi;

        if (response.data.prods) this.show = "products";
        else this.show = "";
      });
    },
    search: function(event) {
      axios.get("../comanda/search/" + event).then(response => {
        this.products = response.data.prods;
        this.viz_preturi = response.data.viz_preturi;

        this.show = "products";
      });
    }
  },
  created() {
    axios({
      method: "post",
      url: "../comanda/comenzi_sidebar",
      data: {
        id: 2
      },
      responseType: "text"
    }).then(response => {
      this.menu = response.data;
    });
    // axios.get("comanda/comenzi_cart").then(response => {
    //   this.cart = response.data.produse;
    // });
  }
};
</script>
