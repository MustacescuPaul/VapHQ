<template>
  <div class="container is-fluid">
    <div class="columns">
      <div class="column is-offset-1">
        <date-intrare @cartBon="cart = $event"></date-intrare>
        <bon-deschis @primeste="primit($event)" :cart="cart"></bon-deschis>
        <intrare-produs :sn="sn" :id="id" v-if="pr == 1"></intrare-produs>
      </div>
      <div class="column"></div>
    </div>
  </div>
</template>
<script>
export default {
  data: function() {
    return {
      cart: "",
      pr: 0,
      sn: "",
      id: ""
    };
  },
  methods: {
    primit: function(event) {
      axios
        .post("garantii/primit", { sn: event.sn, id_prod: event.id_prod })
        .then(response => {
          this.id = event.id_prod;
          this.sn = event.sn;
          this.pr = 1;
        });
    }
  },
  mounted() {
    console.log("Component mounted.");
  }
};
</script>
