<template>
  <div>
    <div class="columns" style="list-style-type: none; margin-top: 3px;">
      <div class="column" style="max-width:160px;padding-left:0px;padding-right:0px;">
        <li>
          <button @click="show('sertar')" class="button is-outlined is-fullwidth">Sertar</button>
        </li>
        <li>
          <button
            @click="show('retragere')"
            class="button is-outlined is-fullwidth"
          >Retragere/Depunere</button>
        </li>
        <li>
          <button
            @click="show('depuneri_banca')"
            class="button is-outlined is-fullwidth"
          >Depunere banca</button>
        </li>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data: function() {
    return {};
  },
  methods: {
    show: function(button) {
      this.$emit("show", button);
    }
  },
  mounted() {
    axios.post("../sertar/butoane_sidebar").then(response => {
      this.butoane = response.data;
    });
  }
};
</script>

<style scoped>
</style>
