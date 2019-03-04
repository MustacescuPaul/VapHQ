<template>
  <div class="container">
    <div v-if="eroare_rezultate" class="notification is-danger has-text-centered">
      <strong>{{eroare_rezultate}}</strong>
    </div>
    <ul v-for="product in cart.rezultate">
      <div class="columns">
        <div class="column">
          <hr>
          <li>
            <figure class="image is-128x128">
              <img :src="'https://vapez.ro/' +product.img + '-home_default/poza.jpg'">
            </figure>
          </li>
        </div>
        <div class="column" style="margin-top:5%;">
          <li>{{product.name}}</li>
        </div>
        <div class="column" style="margin-top:5%;">
          <li>{{product.sn}}</li>
        </div>
        <div class="column is-one-third">
          <li>
            <div class="field" style="margin-top:15%; margin-right:10%;">
              <p class="control">
                <a
                  class="button is-primary"
                  :id_prod="product.id_prod"
                  :sn="product.sn"
                  @click="primeste"
                >Primeste in garantie</a>
              </p>
            </div>
          </li>
        </div>
      </div>
    </ul>
  </div>
</template>
<script>
export default {
  props: ["cart"],
  data: function() {
    return {
      eroare_rezultate: ""
    };
  },
  methods: {
    primeste: function(event) {
      let id_prod = event.target.getAttribute("id_prod");
      let sn = event.target.getAttribute("sn");

      this.$emit("primeste", { id_prod: id_prod, sn: sn });
    }
  },
  beforeUpdate() {
    if (this.cart.nr_rezultate > 1) {
      this.eroare_rezultate =
        "Atentie!Au fost gasite mai multe produse cu acelasi S/N!";
    }
    if (this.cart.nr_rezultate == 1) {
      this.eroare_rezultate = "";
    }
    if (this.cart.nr_rezultate == 0) {
      this.eroare_rezultate = "Nu a fost gasit nici un produs.";
    }
  }
};
</script>
