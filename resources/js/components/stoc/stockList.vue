<template>
  <table class="is-scrollable table is-narrow is-fullwidth is-striped">
    <thead>
      <tr>
        <th class="has-text-centered">
          <i class="fas fa-fingerprint"></i>
        </th>
        <th class="has-text-centered">
          <i class="fas fa-tag"></i>
        </th>
        <th class="has-text-centered">
          <i class="fas fa-shopping-basket"></i>
        </th>
        <th class="has-text-right">
          <i class="fas fa-plus"></i>
        </th>
        <th class="has-text-centered">Stoc</th>
        <th class="has-text-left">
          <i class="fas fa-minus"></i>
        </th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(product,index) in prods">
        <td class="has-text-centered">{{product.id}}</td>
        <td class="has-text-centered">{{product.ref}}</td>
        <td class="has-text-centered">{{product.nume}}</td>
        <td class="has-text-right">
          <input
            :id_prod="product.id"
            sign="1"
            :pos="index"
            v-on:keyup.enter="trimite"
            type="text"
            class="input"
          >
        </td>
        <td class="has-text-centered">{{product.stoc}}</td>
        <td class="has-text-left">
          <input
            :id_prod="product.id"
            sign="-1"
            :pos="index"
            v-on:keyup.enter="trimite"
            type="text"
            class="input"
          >
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script>
export default {
  props: ["products"],
  data: function() {
    return {};
  },
  methods: {
    trimite: function(event) {
      let id = event.target.getAttribute("id_prod");
      let pos = event.target.getAttribute("pos");
      let cantitate = event.target.value;
      let sign = event.target.getAttribute("sign");
      cantitate = Number(cantitate) * Number(sign);
      this.$set(
        this.products.data[pos],
        "stoc",
        this.products.data[pos]["stoc"] + cantitate
      );
      this.$emit("modifica", { id: id, cantitate: cantitate });
    }
  },
  computed: {
    prods: function() {
      if ("meta" in this.products)
        if ("search" in this.products.meta)
          return _.orderBy(this.products.data, ["id"], ["desc"]);
      return _.orderBy(this.products.data, ["id"], ["asc"]);
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
input {
  width: 50px !important;
}
</style>
