<template>
  <div>
    <table v-show="comandapr" class="is-scrollable table is-narrow is-fullwidth">
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
          <td></td>
          <td
            v-if="viz_preturi > 0 && product.stoc != 'Nu este disponibil pt comanda!'"
          >{{product.ctva.toFixed(2)}}</td>
          <td
            v-if="viz_preturi > 0 && product.stoc != 'Nu este disponibil pt comanda!'"
          >{{product.total_ctva.toFixed(2)}}</td>
          <td
            v-if="viz_preturi > 0 && product.stoc != 'Nu este disponibil pt comanda!'"
          >{{product.adaos_nr.toFixed(2)}}</td>
          <td
            v-if="viz_preturi > 0 && product.stoc != 'Nu este disponibil pt comanda!'"
          >{{product.adaos_proc}}%</td>
        </tr>
        <tr>
          <td>
            <button
              @click="finalizeazaCmd"
              class="button is-fullwidth is-primary"
            >Finalizeaza Comanda</button>
          </td>
        </tr>
      </tbody>
    </table>
    <p class="has-text-centered">{{message_text}}</p>
  </div>
</template>
<script>
export default {
  props: ["response"],
  props: {
    response: Object
  },
  data: function() {
    return {
      comandapr: this.response.prods,
      viz_preturi: this.response.viz_preturi,
      message_toggle: false,
      message_text: this.response.message
    };
  },
  methods: {
    finalizeazaCmd: function(event) {
      axios.post("../comanda/finalizareCmd", {}).then(response => {
        this.message_text =
          "Comanda a fost finalizata si este in asteptarea prelucrarii de catre depozit.";
        this.comandapr = "";
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

