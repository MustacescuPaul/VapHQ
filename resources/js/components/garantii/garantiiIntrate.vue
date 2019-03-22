<template>
  <div class="container is-fluid">
    <table class="is-scrollable table is-narrow is-fullwidth is-striped">
      <thead>
        <tr>
          <th>Nume Vapoint</th>
          <th>Nume client</th>
          <th>Adresa</th>
          <th>Telefon</th>
          <th>Email</th>
          <th>Data</th>
          <th>Status</th>
          <th>Expediata</th>
          <th>Primita</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="intrare in intrarii">
          <td @click="afiseazaProduse(intrare.id)">
            <i class="far fa-eye"></i>
            {{intrare.nume_vapoint}}
          </td>
          <td>{{intrare.nume_client}}</td>
          <td>{{intrare.adresa_client}}</td>
          <td>{{intrare.telefon_client}}</td>
          <td>{{intrare.email_client}}</td>
          <td>{{intrare.data}}</td>
          <td>{{intrare.status}}</td>
          <td>
            <input
              type="checkbox"
              :checked="temp"
              value="Expediat"
              :id="intrare.id"
              @click="primitVap"
              :disabled="intrare.status != 'Primit @ ' + intrare.nume_vapoint"
              class="checkbox"
            >
          </td>
          <td>
            <input
              type="checkbox"
              :checked="temp"
              value="Returnat"
              :id="intrare.id"
              @click="primitVap"
              :disabled="intrare.status != 'Expediat @ Service'"
              class="checkbox"
            >
          </td>
        </tr>
      </tbody>
    </table>
    <detalii-produse
      v-if="show == 'produse'"
      @Back="show = 'garantii'"
      :produse_intrare="produse_intrare"
    ></detalii-produse>
  </div>
</template>
<script>
export default {
  props: ["intrari"],
  data: function() {
    return {
      produse_intrare: [],
      show: "garantii",
      intrarii: this.intrari,

      temp: ""
    };
  },
  methods: {
    primitVap: function(event) {
      let id = event.currentTarget.id;
      let stat = event.currentTarget.value;
      axios.post("primit_vap", { id: id, stat: stat }).then(response => {
        console.log(stat);
        this.intrarii = response.data;
      });
    },
    afiseazaProduse: function(event) {
      axios.post("produse_intrare", { id_intrare: event }).then(response => {
        this.produse_intrare = response.data;
        this.show = "produse";
      });
    }
  },
  mounted() {}
};
</script>
