<template>
  <table class="is-scrollable table is-narrow is-fullwidth">
    <thead>
      <tr>
        <th>Poza</th>
        <th>Nume</th>
        <th>Stoc</th>
        <th>Cos</th>
        <th></th>
        <th v-if="viz_preturi > 0">cTVA</th>
        <th v-if="viz_preturi > 0">Total cTVA</th>
        <th v-if="viz_preturi > 0">Adaos</th>
        <th v-if="viz_preturi > 0"></th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="product in comandapr">
        <td style=" padding: 0px;
  margin: 0px;">
          <figure class="image is-128x128">
            <img :src="'https://vapez.ro/' +product.image + '-home_default/poza.jpg'">
          </figure>
        </td>
        <td class="has-text-centered" style=" padding: 0px;
  margin: 0px;">{{product.nume}}</td>
        <td class="has-text-centered" style=" padding: 0px;
  margin: 0px;">{{product.stoc}}</td>
        <td>{{product.cos}}</td>
        <td v-if="product.stoc != 'Nu este disponibil pt comanda!'">
          <button class="button">+</button>
          <button class="button">-</button>
          <button class="button">+5</button>
          <button class="button">+10</button>
          <button class="button is-danger">DEL</button>
        </td>
        <td
          v-if="viz_preturi > 0 && product.stoc != 'Nu este disponibil pt comanda!'"
        >{{product.ctva}}</td>
        <td
          v-if="viz_preturi > 0 && product.stoc != 'Nu este disponibil pt comanda!'"
        >{{product.total_ctva}}</td>
        <td
          v-if="viz_preturi > 0 && product.stoc != 'Nu este disponibil pt comanda!'"
        >{{product.adaos_nr}}</td>
        <td
          v-if="viz_preturi > 0 && product.stoc != 'Nu este disponibil pt comanda!'"
        >{{product.adaos_proc}}%</td>
      </tr>
    </tbody>
  </table>
</template>
<script>
export default {
  props: ["comanda"],
  props: {
    comanda: Object
  },
  data: function() {
    return {
      comandapr: this.comanda.prods,
      viz_preturi: this.comanda.viz_preturi
    };
  },
  methods: {
    addToCart: function(event) {
      let id_prod = event.target.getAttribute("id_prod");
      axios.get("casa/cart_content/" + id_prod).then(response => {});
      this.$emit("add");
    }
  }
};
</script>
<style>
table {
  font-size: 15px;
}
.button {
  padding-left: 5px;
  padding-right: 5px;
  padding-top: 0px;
  padding-bottom: 0px;
  font-size: 15px;
}
</style>

