<template>
  <div class="columns">
    <div class="column">
      <p class="has-text-centered mar-top-30">Vanzare</p>
      <button
        v-for="men in menu.vanzare"
        @click="vanzare"
        class="button mar-top-5 is-fullwidth is-primary"
        :id="men"
      >{{men}}</button>
    </div>
    <div class="column">
      <p class="has-text-centered mar-top-30">Admin</p>
      <button
        v-for="men in menu.admin"
        @click="admin"
        class="button mar-top-5 is-fullwidth is-primary"
        :id="men"
      >{{men}}</button>
    </div>
    <div class="column">
      <p class="has-text-centered mar-top-30">Garantii</p>
      <button
        v-for="men in menu.garantii"
        @click="garantii"
        class="button mar-top-5 is-fullwidth is-primary"
        :id="men"
      >{{men}}</button>
    </div>
    <div class="column">
      <p class="has-text-centered mar-top-30">Aprovizionare</p>
      <button
        v-for="men in menu.aprovizionare"
        @click="aprovizionare"
        class="button mar-top-5 is-fullwidth is-primary"
        :id="men"
      >{{men}}</button>
    </div>
    <div class="column">
      <p class="has-text-centered mar-top-30">Utile</p>
      <button class="button mar-top-5 is-fullwidth is-primary">Bonus</button>
      <button class="button mar-top-5 is-fullwidth is-primary">Info</button>
      <button class="button mar-top-5 is-fullwidth is-primary">Documente</button>
      <button @click="logout" class="button mar-top-5 is-fullwidth is-danger">Logout</button>
    </div>
  </div>
</template>

<script>
export default {
  props: ["menu"],
  methods: {
    aprovizionare: function(event) {
      var id = event.target.getAttribute("id");
      if (id == "Comenzi") window.location.href = "comanda";
      if (id == "Comenzi in asteptare")
        window.location.href = "comanda/asteptare";
      if (id == "Istoric") window.location.href = "comanda/istoric";
      if (id == "Stocare") window.location.href = "#";
      if (id == "Upload stoc") window.location.href = "#";
    },
    vanzare: function(event) {
      var id = event.target.getAttribute("id");
      if (id == "Vanzare") window.location.href = "casa";
      if (id == "Casa") window.location.href = "#";
      if (id == "Stocuri") window.location.href = "#";
      if (id == "Sertar") window.location.href = "#";
      if (id == "Mixer baze") window.location.href = "#";
    },
    garantii: function(event) {
      var id = event.target.getAttribute("id");
      if (id == "Emitere") window.location.href = "#";
      if (id == "Primire service") window.location.href = "garantii";
      if (id == "Eliberare service") window.location.href = "#";
      if (id == "Intrari service") window.location.href = "garantii/intrate";
    },
    admin: function(event) {
      var id = event.target.getAttribute("id");
      if (id == "Dashboard") window.location.href = "admin";
    },
    logout: function(event) {
      axios.post("logout", {}).then(response => {
        window.location.href = "login";
      });
    }
  }
};
</script>

<style>
</style>
