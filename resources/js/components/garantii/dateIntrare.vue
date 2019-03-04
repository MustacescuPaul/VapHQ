<template>
  <div class="columns">
    <div class="column">
      <div class="field">
        <label class="label">Serial Number</label>
        <div class="control">
          <input
            class="input"
            autofocus
            type="text"
            v-model="serial_number"
            v-on:keyup.enter="deschideBon"
            placeholder="Cod"
          >
        </div>
      </div>
    </div>
    <div class="column">
      <div class="field">
        <label class="label">Cod Unic de Identificare Garantie</label>
        <div class="control">
          <input
            class="input"
            autofocus
            type="text"
            v-model="cod_garantie"
            v-on:keyup.enter="deschideBon"
            placeholder="Cod"
          >
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data: function() {
    return {
      serial_number: "",
      cod_garantie: ""
    };
  },
  methods: {
    deschideBon: function(event) {
      axios
        .post("garantii/deschideBonuri", {
          serial_number: this.serial_number,
          cod_garantie: this.cod_garantie
        })
        .then(response => {
          this.$emit("cartBon", response.data);
        });
    }
  }
};
</script>
