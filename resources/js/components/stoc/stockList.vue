<template>
  <table class="is-scrollable table is-fullwidth">
    <thead>
      <tr>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="product in products">
        <td>
          <button class="button is-primary">Info</button>
        </td>
        <td>[38]</td>
        <td>23</td>
        <td>
          <button class="button is-primary">Cos</button>
        </td>
        <td>{{product.nume}}</td>
        <td>
          <button class="button is-primary is-fullwidth is-block" :id_prod="product.id_prod"></button>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script>
export default {
  props: ["products"],
  data: function() {
    return {};
  },
  methods: {}
};
</script>

<style scoped>
</style>