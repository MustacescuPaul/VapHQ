<template>
  <div class="container is-fluid">
    <div class="columns">
      <div class="column">
        <sertar-sidebar></sertar-sidebar>
      </div>

      <sertar-afisare :response="response" v-if="show == 'sertar'"></sertar-afisare>
      <!-- <retragere-depunere></retragere-depunere> -->
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
  }
};
</script>

<style scoped>
</style>
