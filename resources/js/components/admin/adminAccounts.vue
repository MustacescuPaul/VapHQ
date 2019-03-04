<template>
  <div class="container has-text-centered columns">
    <div class="column is-3 is-offset-2">
      <h3 class="title has-text-grey">Inregistrare Admin</h3>
      <p class="subtitle has-text-grey">Inregistrare cont administrator</p>
      <div class="box">
        <figure class="avatar">
          <img src="https://placehold.it/128x128">
        </figure>

        <div class="field">
          <div class="control">
            <input
              class="input is-large"
              v-model="admin.username"
              type="text"
              placeholder="Username"
              autofocus
            >
            <p class="help is-danger">{{erra.username}}</p>
          </div>
        </div>
        <div class="field">
          <div class="control">
            <input
              class="input is-large"
              v-model="admin.parola"
              type="password"
              placeholder="Your Password"
            >
            <p class="help is-danger">{{erra.parola}}</p>
          </div>
        </div>
        <div class="field">
          <div class="control">
            <input
              class="input is-large"
              v-model="admin.email"
              type="email"
              placeholder="Your Email"
            >
            <p class="help is-danger">{{erra.email}}</p>
          </div>
        </div>
        <button
          class="button is-block is-info is-large is-fullwidth"
          @click="RegisterAdmin"
        >Inregistrare</button>
      </div>
    </div>
    <div class="column is-3 is-offset-1">
      <h3 class="title has-text-grey">Inregistrare Utilizator</h3>
      <p class="subtitle has-text-grey">Inregistrare cont angajat</p>
      <div class="box">
        <figure class="avatar">
          <img src="https://placehold.it/128x128">
        </figure>
        <div class="field">
          <div class="control">
            <input
              class="input is-large"
              v-model="user.username"
              type="text"
              placeholder="Username"
              autofocus
            >
            <p class="help is-danger">{{erru.username}}</p>
          </div>
        </div>
        <div class="field">
          <div class="control">
            <input
              class="input is-large"
              v-model="user.nume"
              type="text"
              placeholder="Nume"
              autofocus
            >
            <p class="help is-danger">{{erru.nume}}</p>
          </div>
        </div>
        <div class="field">
          <div class="control">
            <input
              class="input is-large"
              v-model="user.prenume"
              type="text"
              placeholder="Prenume"
              autofocus
            >
            <p class="help is-danger">{{erru.prenume}}</p>
          </div>
        </div>
        <div class="field">
          <div class="control">
            <input
              class="input is-large"
              v-model="user.parola"
              type="password"
              placeholder="Your Password"
            >
            <p class="help is-danger">{{erru.parola}}</p>
          </div>
        </div>
        <button
          class="button is-block is-info is-large is-fullwidth"
          @click="RegisterUser"
        >Inregistrare</button>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: [],
  data() {
    return {
      admin: {
        username: "",
        parola: "",
        email: ""
      },
      user: {
        username: "",
        nume: "",
        prenume: "",
        parola: ""
      },
      erru: [],
      erra: []
    };
  },
  methods: {
    RegisterUser: function(event) {
      axios
        .post("admin/createUser", {
          nume: this.user.nume,
          prenume: this.user.prenume,
          username: this.user.username,
          parola: this.user.parola
        })
        .then(response => {
          this.$emit("reg");
        })
        .catch(error => {
          this.erru = error.response.data.errors;
        });
    },
    RegisterAdmin: function(event) {
      axios
        .post("admin/createAdmin", {
          username: this.admin.username,
          parola: this.admin.parola,
          email: this.admin.email
        })
        .then(response => {})
        .catch(error => {
          this.erra = error.response.data.errors;
        });
    }
  },
  updated: function() {}
};
</script>
