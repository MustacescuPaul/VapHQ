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
      <tr v-for="(product,index) in products">
        <td style=" padding: 0px;
  margin: 0px;">
          <button class="button is-primary">{{product.id}}</button>
        </td>
        <td class="has-text-centered" style=" padding: 0px;
  margin: 0px;">{{product.ref}}</td>
        <td class="has-text-centered" style=" padding: 0px;
  margin: 0px;">{{product.nume}}</td>
        <td>{{product.stoc}}</td>
        <td>{{product.cos}}</td>
        <td v-if="product.stoc != 'Nu este disponibil pt comanda!'">
          <button
            @click="addToCart"
            cantitate="1"
            :pos="index"
            :class="index == line_color ? 'button is-danger': 'button'"
          >+</button>
          <button @click="addToCart" cantitate="-1" :pos="index" class="button">-</button>
          <button @click="addToCart" cantitate="5" :pos="index" class="button">+5</button>
          <button @click="addToCart" cantitate="10" :pos="index" class="button">+10</button>
          <button @click="rmCmd" :pos="index" class="button is-danger">DEL</button>
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
    return {
      line_color: ""
    };
  },
  methods: {
    addToCart: function(event) {
      let cantitate = event.target.getAttribute("cantitate");
      let pos = event.target.getAttribute("pos");
      let id_prod = this.products[pos]["id"];
      if (this.products[pos].cos <= this.products[pos].stoc_s) {
        axios
          .post("../comanda/addToCmd", {
            cantitate: cantitate,
            id_prod: id_prod,
            list: 1
          })
          .then(response => {
            console.log(response.data);
            this.$set(this.products, pos, response.data.prods[pos]);

            this.viz_preturi = response.data.viz_preturi;
          });
      }
      if (this.products[pos].cos == this.products[pos].stoc_s) {
        this.line_color = pos;
      }
    },
    rmCmd: function(event) {
      let pos = event.target.getAttribute("pos");
      let id_prod = this.products[pos]["id"];
      axios
        .post("../comanda/rmCmd", {
          id_prod: id_prod,
          list: 1
        })
        .then(response => {
          this.$set(this.products, pos, response.data.prods[pos]);

          this.viz_preturi = response.data.viz_preturi;
        });
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

