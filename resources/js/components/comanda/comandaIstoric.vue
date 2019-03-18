<template>
  <div>
    <table class="is-scrollable table is-narrow is-fullwidth">
      <thead>
        <tr>
          <th>Comanda</th>
          <th>Data</th>
          <th>Valoare</th>
          <th>Status</th>
          <th>Factura</th>
          <th>Date NIR</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(comanda,index) in istoric.orderInfo">
          <td>
            <i :id_order="index" @click="detalii" class="fas fa-arrow-circle-down"></i>
            {{comanda.ref}}
          </td>
          <td>{{comanda.date}}</td>
          <td>{{comanda.valoare}}</td>
          <td>{{comanda.state}}</td>
          <td></td>
          <td></td>
        </tr>
      </tbody>
    </table>
    <table v-if="show == 'detalii'" class="is-scrollable table is-narrow is-fullwidth">
      <thead>
        <tr>
          <th>Poza</th>
          <th>Nume</th>
          <th>Cantitate</th>
          <th>
            Unitar
            fTVA
          </th>
          <th>
            Linie
            cTVA
          </th>
          <th>
            Adaos
            fTVA
          </th>
          <th>
            Total
            vanzare
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(produs,index) in order[0]">
          <td style=" padding: 0px;
  margin: 0px;">
            <figure class="image is-50x50" style="width:50px;heigth:50;">
              <img :src="'https://vapez.ro/' +produs.poza + '-home_default/poza.jpg'">
            </figure>
          </td>
          <td>{{produs.nume}}</td>
          <td>{{produs.cantitate}}</td>
          <td>{{produs.unitftva}}</td>
          <td>{{produs.liniectva}}</td>
          <td>
            {{produs.adaos_nr}}
            <br>
            {{produs.adaos_proc}}%
          </td>
          <td>{{produs.total_vanzare}}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script>
export default {
  props: ["istoric"],
  data: function() {
    return {
      show: "comenzi",
      order: []
    };
  },
  methods: {
    detalii: function(event) {
      let id = event.target.getAttribute("id_order");
      this.show = "detalii";

      this.$set(this.order, 0, this.istoric.produse[id]);
    }
  },
  mounted() {
    console.log(this.istoric);
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

