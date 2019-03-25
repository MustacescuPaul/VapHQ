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
          <i class="fas fa-shopping-basket"></i>
        </th>
        <th class="has-text-centered">
          <i class="fas fa-gem"></i>
        </th>
        <th class="has-text-centered">
          <i class="fas fa-bars"></i>
        </th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(modificare,index) in modificari.data">
        <td class="has-text-centered">{{modificare.data}}</td>
        <td class="has-text-centered">{{modificare.ora}}</td>
        <td class="has-text-centered">{{modificare.user}}</td>
        <td class="has-text-centered">{{modificare.ref}}</td>
        <td class="has-text-centered">{{modificare.nume}}</td>
        <td class="has-text-centered">{{modificare.cantitate}}</td>
        <td class="has-text-centered">{{modificare.motiv}}</td>
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
</template>

<script>
export default {
  props: ["modificari"],
  data: function() {
    return {
      currentPage: "1",
      totalPages: this.modificari.meta.pages
    };
  },
  methods: {
    paginate: function(event) {
      let page = event.target.getAttribute("page");

      if (
        Number(this.currentPage) + Number(page) <= this.totalPages &&
        Number(this.currentPage) + Number(page) > 0
      ) {
        this.currentPage = Number(this.currentPage) + Number(page);
        axios
          .get("../stoc?page=" + this.currentPage + "&paginate=1")
          .then(response => {
            this.modificari = response.data;
          });
      }
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

