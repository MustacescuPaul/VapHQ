<template>
  <div>
    <table class="is-scrollable table is-narrow is-fullwidth is-striped">
      <thead>
        <tr>
          <th class="has-text-centered">
            Comanda
            <i class="fas fa-fingerprint"></i>
          </th>
          <th class="has-text-centered">
            <i class="fas fa-calendar-day"></i>
          </th>
          <th class="has-text-centered">
            <i class="fas fa-clock"></i>
          </th>
          <th class="has-text-centered">RON</th>
          <th class="has-text-centered">Status</th>
          <th class="has-text-centered">Factura</th>
          <th class="has-text-centered">Date NIR</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(comanda,index) in istoricl.orderInfo">
          <td :id_order="comanda.id" @click="detalii" class="has-text-centered">
            <i :id_order="comanda.id" @click="detalii" class="far fa-eye"></i>
            {{comanda.ref}}
          </td>
          <td class="has-text-centered">{{comanda.date}}</td>
          <td class="has-text-centered">{{comanda.time}}</td>
          <td class="has-text-centered">{{comanda.valoare}}</td>
          <td class="has-text-centered">{{comanda.state}}</td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>
            <i @click="paginate" page="-1" class="fas fa-long-arrow-alt-left fa-4x is-pulled-left"></i>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td>
            <p class="pagination has-text-centered">{{ currentPage }} / {{ totalPages }}</p>
          </td>
          <td></td>
          <td>
            <i @click="paginate" page="1" class="fas fa-long-arrow-alt-right fa-4x is-pulled-right"></i>
          </td>
        </tr>
      </tbody>
    </table>

    <table v-if="show == 'detalii'" class="is-scrollable table is-narrow is-fullwidth is-striped">
      <thead>
        <tr>
          <th class="has-text-centered">
            <i class="fas fa-image fa-2x"></i>
          </th>
          <th class="has-text-centered">Nume</th>
          <th class="has-text-centered">Cantitate</th>
          <th class="has-text-centered">
            Unitar
            fTVA
          </th>
          <th class="has-text-centered">
            Linie
            cTVA
          </th>
          <th class="has-text-centered">
            Adaos
            fTVA
          </th>
          <th class="has-text-centered">
            Total
            vanzare
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(produs,index) in order[0]">
          <td class="is-flex" style="justify-content: center;">
            <figure class="image is-50x50" style="width:50px;heigth:50;">
              <img :src="'https://vapez.ro/' +produs.poza + '-home_default/poza.jpg'">
            </figure>
          </td>
          <td class="has-text-centered">{{produs.nume}}</td>
          <td class="has-text-centered">{{produs.cantitate}}</td>
          <td class="has-text-centered">{{produs.unitftva.toFixed(2)}}</td>
          <td class="has-text-centered">{{produs.liniectva.toFixed(2)}}</td>
          <td class="has-text-centered">
            {{produs.adaos_nr.toFixed(2)}}
            <br>
            {{produs.adaos_proc}}%
          </td>
          <td class="has-text-centered">{{produs.total_vanzare}}</td>
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
      order: [],
      currentPage: "1",
      totalPages: this.istoric.pages,
      istoricl: this.istoric
    };
  },
  methods: {
    detalii: function(event) {
      let id = event.target.getAttribute("id_order");
      this.show = "detalii";

      this.$set(this.order, 0, this.istoricl.produse[id]);
    },
    paginate: function(event) {
      let page = event.target.getAttribute("page");

      if (
        Number(this.currentPage) + Number(page) <= this.totalPages &&
        Number(this.currentPage) + Number(page) > 0
      ) {
        this.currentPage = Number(this.currentPage) + Number(page);
        axios
          .get("../comanda/istoric?page=" + this.currentPage + "&paginate=1")
          .then(response => {
            this.istoricl = response.data;
          });
      }
    }
  },
  mounted() {}
};
</script>
<style>
table {
  font-size: 15px;
  padding-bottom: 0px;
  margin-bottom: 0px !important;
}
.button {
  padding-left: 5px;
  padding-right: 5px;
  padding-top: 0px;
  padding-bottom: 0px;
  font-size: 15px;
}
.fa-long-arrow-alt-left {
  padding: 0px;
  margin: 0px;
}
.pagination {
  margin-top: 8px;
}
</style>

