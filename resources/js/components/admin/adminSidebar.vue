<template>
  <aside class="is-hidden-mobile">
    <div class="panel">
      <p class="panel-heading has-text-centered">Panou de Control</p>
      <a @click="switchContent('dashboard')" class="panel-block">
        <span class="panel-icon">
          <i class="fa fa-home"></i>
        </span>
        <span class="name">Dashboard</span>
      </a>
      <a @click="switchContent('creeare conturi')" class="panel-block">
        <span class="panel-icon">
          <i class="fa fa-user-circle"></i>
        </span>
        <span class="name">Creeare conturi</span>
      </a>
      <a @click="switchContent('administrare conturi')" class="panel-block">
        <span class="panel-icon">
          <i class="fa fa-th-list"></i>
        </span>
        <span class="name">Administrare Conturi</span>
      </a>
      <a @click="switchContent('service')" class="panel-block">
        <span class="panel-icon">
          <i class="fa fa-wrench"></i>
        </span>
        <span class="name">Service</span>
      </a>
    </div>
  </aside>
</template>
<script>
export default {
  props: [],
  data: function() {
    return {};
  },
  methods: {
    switchContent: function(text) {
      if (text == "administrare conturi") {
        this.$emit("getAccounts");
      } else {
        if (text == "service") {
          this.$emit("getService");
        } else {
          this.$emit("switchContent", text);
        }
      }
    }
  },
  mounted() {}
};
</script>
