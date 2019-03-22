<template>
  <div>
    <table class="is-scrollable table is-narrow is-fullwidth is-striped">
      <thead>
        <tr>
          <th>Nume</th>
          <th>Serial</th>
          <th>Defect</th>
          <th>Stare</th>
          <th>Detalii remediere defect</th>
        </tr>
      </thead>
      <tbody>
        {{this.message}}
        <tr v-for="produs in produse_intrare">
          <td>{{produs.nume}}</td>
          <td>{{produs.cod}}</td>
          <td>{{produs.defect}}</td>
          <td>{{produs.stare}}</td>
          <td>{{produs.remediere}}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script>
export default {
  props: ["produse_intrare"],
  data: function() {
    return {
      message: ""
    };
  },
  methods: {
    back: function(event) {
      this.$emit("Back");
    }
  },
  beforeUpdate() {
    if (this.produse_intrare.length == 0)
      this.message = "Nu au fost gasite produse pentru aceasta intrare!";
  }
};
</script>
