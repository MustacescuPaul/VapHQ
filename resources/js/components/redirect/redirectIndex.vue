<template>
  <div>
    <redirect-menu
      :menu="menu"
      v-if="show == 'menu'"
      @comenzi="interfataComenzi"
      @vanzare="interfataVanzare"
      @primireService="primireService"
    ></redirect-menu>
  </div>
</template>

<script>
export default {
  props: ["menu"],
  data: function() {
    return {
      show: "menu",
      comenzi: [],
      comenzi_menu: [],
      comenzi_cart: []
    };
  },
  methods: {
    interfataComenzi: function() {
      window.location.href = "comanda/";
    },
    interfataVanzare: function() {
      window.location.href = "casa/";
    },
    primireService: function() {
      window.location.href = "garantii/";
    }
  }
};
</script>

<style>
.mar-top-30 {
  margin-top: 30px;
}
.mar-top-5 {
  margin-top: 5px;
}
</style>
