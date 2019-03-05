<template>
  <div>
    <redirect-menu v-if="show == 'menu'" @comenzi="interfataComenzi"></redirect-menu>
  </div>
</template>

<script>
export default {
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
      // axios
      //   .get("comanda/")
      //   .then(response => {
      //     this.comenzi = response.data;
      //     axios({
      //       method: "post",
      //       url: "comanda/comenzi_sidebar",
      //       data: {
      //         id: 2
      //       },
      //       responseType: "text"
      //     }).then(response => {
      //       this.comenzi_menu = response.data;
      //     });
      //     //   axios.get("comanda/showcart").then(response => {
      //     //     this.comenzi_cart = response.data.produse;
      //     //   });
      //     this.show = "comenzi";
      //   })
      //   .catch(error => {
      //     console.log(error.response.data.errors);
      //   });
      window.location.href = "comanda/";
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
