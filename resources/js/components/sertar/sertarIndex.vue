<template>
  <div class="container is-fluid">
    <div class="columns">
      <div class="column">
        <sertar-sidebar @show="sh($event)"></sertar-sidebar>
      </div>

      <sertar-afisare :response="response" v-if="show == 'sertar'"></sertar-afisare>
      <retragere-depunere v-if="show == 'retragere' "></retragere-depunere>
      <!-- <depunere-banca></depunere-banca> -->
    </div>
  </div>
</template>

<script>
export default {
  props: ["response"],
  data: function() {
    return {
      show: "sertar"
    };
  },
  methods: {
    sh: function(event) {
      this.show = event;
    }
  }
};
</script>

<style scoped>
</style>
