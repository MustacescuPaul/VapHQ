<template>
  <table class="is-scrollable table is-narrow is-fullwidth">
    <thead>
      <tr>
        <th>ID</th>
        <th>Ref</th>
        <th>Nume</th>
        <th>Stoc</th>
        <th>Cos</th>
        <th></th>
        <th v-if="viz_preturi > 0">fTVA</th>
        <th v-if="viz_preturi > 0">cTVA</th>
        <th v-if="viz_preturi > 0">Site</th>
        <th v-if="viz_preturi > 0">Adaos</th>
        <th v-if="viz_preturi > 0"></th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="product in products">
        <td style=" padding: 0px;
  margin: 0px;">
          <button class="button is-primary">{{product.id}}</button>
        </td>
        <td class="has-text-centered" style=" padding: 0px;
  margin: 0px;">{{product.ref}}</td>
        <td class="has-text-centered" style=" padding: 0px;
  margin: 0px;">{{product.nume}}</td>
        <td>{{product.stoc}}</td>
        <td></td>
        <td v-if="product.stoc != 'Nu este disponibil pt comanda!'">
          <button class="button">+</button>
          <button class="button">-</button>
          <button class="button">+5</button>
          <button class="button">+10</button>
          <button class="button is-danger">DEL</button>
        </td>
        <td
          v-if="viz_preturi > 0 && product.stoc != 'Nu este disponibil pt comanda!'"
        >{{product.ftva}}</td>
        <td
          v-if="viz_preturi > 0 && product.stoc != 'Nu este disponibil pt comanda!'"
        >{{product.ctva}}</td>
        <td
          v-if="viz_preturi > 0 && product.stoc != 'Nu este disponibil pt comanda!'"
        >{{product.site}}</td>
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
  props: ["products", "viz_preturi"],

  data: function() {
    return {};
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

