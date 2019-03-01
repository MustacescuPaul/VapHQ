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
      <stock-list v-if="show == 'products'" :products="products"></stock-list>
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
      menu: [],
      cart: []
    };
  },
  methods: {
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
    axios.get("casa/sidebar_categ/1").then(response => {
      this.menu = response.data;
    });
    axios.get("casa/showcart").then(response => {
      this.cart = response.data;
    });
  }
};
</script>
