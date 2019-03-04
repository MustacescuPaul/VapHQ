<template>
  <div>
    <div class="field">
      <label class="label">Defect</label>
      <div class="control">
        <input
          class="input"
          type="text"
          v-model="defect"
          placeholder="Descrierea defectului cat mai detaliata"
        >
      </div>
    </div>
    <div class="field">
      <label class="label">Stare produs</label>
      <div class="control">
        <input
          class="input"
          type="text"
          v-model="stare"
          placeholder="Starea de uzura vizibila a produsului"
        >
      </div>
    </div>

    <div class="field is-grouped">
      <div class="control">
        <button class="button is-link" @click="primeste()">Primeste</button>
      </div>
    </div>
  </div>
</template>
	
	<script>
export default {
  props: ["sn", "id"],
  data() {
    return {
      defect: "",
      stare: ""
    };
  },
  methods: {
    primeste: function(event) {
      axios
        .post("garantii/primit_produs", {
          stare: this.stare,
          defect: this.defect,
          sn: this.sn,
          id: this.id
        })
        .then(response => {
          this.$emit("gata");
        });
    }
  }
};
</script>
	
	<style lang="css" scoped>
</style>	