<template>
  <div class="container is-fluid">
    <div class="columns">
      <div class="colum">
        <categ-menu
          @getProd="getProdList($event)"
          @search="search($event)"
          @showChanged="show = $event"
          :menu="menu"
        ></categ-menu>
      </div>
      <div class="column">
        <product-list v-if="show == 'products'" :products="products"></product-list>
      </div>
      <div class="column is-two-fifths is-offset-two-fifths" v-if="show == 'cash-card'">
        <cash-card @Incasat="showCh2('cart')" class="column"></cash-card>
      </div>
      <cart @showChanged="showCh($event)" :cart="cart" v-if="show == 'cart'"></cart>
      <div v-if="show == 'date-client'" class="column is-half is-offset-3">
        <date-client @Primit="showCh2('cash-card')"></date-client>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: [],
  data: function() {
    return {
      show: "cart",
      products: [],
      menu: "",
      cart: [],
      reduceri: []
    };
  },
  methods: {
    showCh: function(event) {
      this.show = event;
    },
    showCh2: function(event) {
      axios.get("casa/showcart").then(response => {
        this.cart = response.data.produse;
      });
      this.show = event;
    },
    getProdList: function(event) {
      axios.get("casa/lista_produse/" + event).then(response => {
        this.products = response.data.products;
        this.show = "products";
      });
    },
    search: function(event) {
      axios.get("casa/search/" + event).then(response => {
        this.products = response.data;

        this.show = "products";
      });
    }
  },
  created() {
    // axios.post("casa/sidebar_categ", { id: 2 }).then(function(response) {
    //   console.log(response);
    //   this.menu = response.data;
    // });responseType: 'json',
    axios({
      method: "post",
      url: "casa/sidebar_categ",
      data: {
        id: 2
      },
      responseType: "text"
    }).then(response => {
      this.menu = response.data;
    });
    axios.get("casa/showcart").then(response => {
      this.cart = response.data.produse;
    });
  }
};
</script>
