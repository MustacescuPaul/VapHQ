<template>
  <table class="is-scrollable table is-narrow is-fullwidth is-striped">
    <thead>
      <tr>
        <th class="has-text-centered">
          <i class="fas fa-calendar-day"></i>
        </th>
        <th class="has-text-centered">
          <i class="fas fa-clock"></i>
        </th>
        <th class="has-text-centered">
          <i class="fas fa-user"></i>
        </th>
        <th class="has-text-centered">
          <i class="fas fa-tag"></i>
        </th>
        <th class="has-text-centered">
          <i class="fas fa-money-bill-alt"></i>
        </th>
        <th class="has-text-centered">
          <i class="fas fa-bars"></i>
        </th>
        <th class="has-text-centered">
          <i class="fas fa-exclamation-triangle"></i>
        </th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(operatiune,index) in response.data">
        <td class="has-text-centered">{{operatiune.date}}</td>
        <td class="has-text-centered">{{operatiune.ora}}</td>
        <td class="has-text-centered">{{operatiune.user}}</td>
        <td class="has-text-centered">{{operatiune.tip}}</td>
        <td class="has-text-centered">{{operatiune.suma}}</td>
        <td class="has-text-centered">{{operatiune.motiv}}</td>
        <td class="has-text-centered">{{operatiune.eroare}}</td>
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
