<template>
  <div class="container is-fluid">
    <div class="columns">
      <div class="colum">
        <stoc-sidebar @motiv="motiv = $event" :menu="menu" @getProd="getProdList($event)"></stoc-sidebar>
      </div>

      <modificari-list v-if="show == 'modificari'" :modificari="modificari"></modificari-list>
      <stock-list v-if="show == 'products'" @modifica="modifica($event)" :products="products"></stock-list>
    </div>
  </div>
</template>
<script>
export default {
  props: ["modificari"],
  data: function() {
    return {
      show: "modificari",
      products: [],
      menu: [],
      cart: [],
      motiv: ""
    };
  },
  methods: {
    getProdList: function(event) {
      axios.get("../stoc/lista_produse/" + event).then(response => {
        this.products = response.data;
        if (response.data.data) this.show = "products";
        else this.show = ",odificari";
      });
    },
    search: function(event) {
      axios.get("../stoc/search/" + event).then(response => {
        this.products = response.data;

        this.show = "products";
      });
    },
    modifica: function(event) {
      axios
        .post("../stoc/modifica", {
          cantitate: event.cantitate,
          id_prod: event.id,
          motiv: this.motiv
        })
        .then(response => {});
    }
  },
  created() {
    axios({
      method: "post",
      url: "stoc/sidebar",
      data: {
        id: 2
      },
      responseType: "text"
    }).then(response => {
      this.menu = response.data;
    });
  }
};
</script>
