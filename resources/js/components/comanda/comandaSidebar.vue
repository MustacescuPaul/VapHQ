<template>
  <div class="columns" style="list-style-type: none; margin-top: 2%;">
    <div class="column" style="max-width:160px;padding-left:0px;padding-right:0px;">
      <li v-for="cat in menu">
        <button
          class="button is-outlined is-fullwidth"
          :id_cat="cat[0]"
          @click="getSubCat"
        >{{ cat[1] }}</button>
      </li>

      <input
        type="text"
        class="input"
        style="margin-top: 20%;"
        placeholder="Nume produs"
        v-on:keyup.enter="search"
      >
    </div>
    <div class="column" style="max-width:190px;padding-left:0px;padding-right:0px;">
      <li v-for="cat in menu1">
        <button
          class="button is-outlined is-fullwidth"
          :id_cat="cat[0]"
          @click="getSubCat1"
        >{{ cat[1] }}</button>
      </li>
    </div>
    <div v-if="menu2[0]" class="column" style="max-width:190px;padding-left:0px;padding-right:0px;">
      <li v-for="cat in menu2">
        <button
          class="button is-outlined is-fullwidth"
          :id_cat="cat[0]"
          @click="getSubCat2"
        >{{ cat[1] }}</button>
      </li>
    </div>
  </div>
</template>
<script>
export default {
  props: ["menu"],

  data: function() {
    return {
      menu1: [],
      menu2: [],
      menu3: [],
      products: [],
      bOk: 0
    };
  },
  methods: {
    getSubCat: function(event) {
      let id_cat = event.target.getAttribute("id_cat");
      let but = event.target.parentElement;
      var ul = document.createElement("ul");
      this.menu2 = [];
      this.menu3 = [];
      this.$emit("getProd", id_cat);
      axios
        .post("../comanda/comenzi_sidebar", { id: id_cat })
        .then(response => {
          this.menu1 = response.data;
        });
    },
    getSubCat1: function(event) {
      let id_cat = event.target.getAttribute("id_cat");
      let but = event.target.parentElement;
      var ul = document.createElement("ul");
      this.menu2 = [];
      this.$emit("getProd", id_cat);
      axios
        .post("../comanda/comenzi_sidebar", { id: id_cat })
        .then(response => {
          this.menu2 = response.data;
        });
    },
    getSubCat2: function(event) {
      let id_cat = event.target.getAttribute("id_cat");
      let but = event.target.parentElement;
      var ul = document.createElement("ul");
      this.$emit("getProd", id_cat);
      axios
        .post("../comanda/comenzi_sidebar", { id: id_cat })
        .then(response => {
          this.menu3 = response.data;
        });
    },
    search: function(event) {
      this.$emit("search", event.target.value);
    }
  }
};
</script>
