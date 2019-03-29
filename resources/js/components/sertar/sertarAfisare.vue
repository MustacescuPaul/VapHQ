<template>
  <table class="is-scrollable table is-narrow is-fullwidth is-striped">
    <thead>
      <tr>
        <th class="has-text-centered">
          <i class="fas fa-calendar-day"></i>D
        </th>
        <th class="has-text-centered">
          <i class="fas fa-calendar-day"></i>I
        </th>
        <th class="has-text-centered">
          <i class="fas fa-clock"></i>D
        </th>
        <th class="has-text-centered">
          <i class="fas fa-clock"></i>I
        </th>
        <th class="has-text-centered">
          <i class="fas fa-user"></i>
        </th>

        <th class="has-text-centered">
          <i class="fas fa-money-bill-alt"></i>D
        </th>
        <th class="has-text-centered">
          <i class="fas fa-money-bill-alt"></i>V
        </th>
        <th class="has-text-centered">
          <i class="fas fa-money-bill-alt"></i>I
        </th>

        <th class="has-text-centered">
          <i class="fas fa-money-bill-alt"></i>R/D/DB
        </th>
        <th class="has-text-centered">
          <i class="fas fa-bars"></i>
        </th>
        <th class="has-text-centered">
          <i class="fas fa-exclamation-triangle"></i>D
        </th>
        <th class="has-text-centered">
          <i class="fas fa-exclamation-triangle"></i>I
        </th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(operatiune,index) in response">
        <td class="has-text-centered">{{operatiune.date}}</td>
        <td class="has-text-centered">{{operatiune.date_i}}</td>
        <td class="has-text-centered">{{operatiune.ora}}</td>
        <td class="has-text-centered">{{operatiune.ora_i}}</td>
        <td class="has-text-centered">{{operatiune.user}}</td>
        <td class="has-text-centered">{{operatiune.deschidere}}</td>
        <td class="has-text-centered">{{operatiune.vanzari}}</td>
        <td class="has-text-centered">{{operatiune.inchidere}}</td>

        <td class="has-text-centered">
          <p>{{operatiune.retragere}}</p>
          <p>{{operatiune.depunere}}</p>
          <p>{{operatiune.depunere_banca}}</p>
        </td>

        <td class="has-text-centered">
          <p>{{operatiune.motiv_r}}</p>
          <br v-if="operatiune.motiv_r == '' ">
          <p>{{operatiune.motiv_d}}</p>
          <br v-if="operatiune.motiv_d = ''">
          <p>{{operatiune.motiv_db}}</p>
        </td>
        <td class="has-text-centered has-text-danger">
          <p v-if="operatiune.eroare_deschidere">{{operatiune.eroare_deschidere}}</p>
        </td>
        <td class="has-text-centered has-text-danger">
          <p v-if="operatiune.eroare_inchidere">{{operatiune.eroare_inchidere}}</p>
        </td>
      </tr>
      <tr>
        <td>
          <i @click="paginate" page="-1" class="fas fa-long-arrow-alt-left fa-4x is-pulled-left"></i>
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td>
          <p class="pagination has-text-centered">{{ currentPage }}</p>
        </td>
        <td></td>
        <td>
          <i @click="paginate" page="1" class="fas fa-long-arrow-alt-right fa-4x is-pulled-right"></i>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script>
export default {
  props: ["response"],
  data: function() {
    return {
      currentPage: "1"
    };
  },
  methods: {
    paginate: function(event) {
      let page = event.target.getAttribute("page");

      let pg = Number(this.currentPage) + Number(page);

      this.currentPage = Number(this.currentPage) + Number(page);
      axios.get("../sertar?" + "paginate=1&page=" + pg).then(response => {
        this.response = response.data;
        if (page < 0) this.currentPage = 1;
      });
    }
  }
};
</script>

<style>
table {
  font-size: 15px;
  padding-bottom: 0px;
  margin-bottom: 0px !important;
  margin-top: 15px;
  margin-left: 5px;
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
