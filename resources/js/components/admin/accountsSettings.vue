<template>
  <table class="table is-centered">
    <thead>
      <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Nume</th>
        <th>Prenume</th>
        <th>Activ</th>
        <th>Bonus</th>
        <th>Banca</th>
        <th>Vapoint</th>
        <th>Creeat</th>
        <th>Modificat</th>
        <th>Sterge</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="user in llist">
        <td>{{user.id}}</td>
        <td>
          <input
            type="text"
            class="input"
            v-on:keyup.enter="chUsername"
            :id="user.id"
            :value="user.username"
            name="username"
          >
        </td>
        <td>
          <input
            type="text"
            class="input"
            v-on:keyup.enter="chNume"
            :id="user.id"
            :value="user.nume"
            name="lastname"
          >
        </td>
        <td>{{user.prenume}}</td>
        <td>
          <label class="checkbox">
            <input v-model="user.activ" name="activ" :id="user.id" @click="checked" type="checkbox">
          </label>
        </td>
        <td>
          <label class="checkbox">
            <input
              v-model="user.bonus"
              name="bonus"
              :id="user.id"
              @change="checked"
              type="checkbox"
            >
          </label>
        </td>
        <td>
          <label class="checkbox">
            <input
              v-model="user.banca"
              name="banca"
              :id="user.id"
              @change="checked"
              type="checkbox"
            >
          </label>
        </td>
        <td>
          <div class="select">
            <select v-model="vapoints[user.id_vapoint]" :id="user.id" @change="selectVap">
              <option
                v-for="(vapoint,index) in vapoints"
                :value="vapoint"
                :selected="index == user.id_vapoint"
              >{{vapoint}}</option>
            </select>
          </div>
        </td>
        <td>{{user.created_at}}</td>
        <td>{{user.updated_at}}</td>
        <td>
          <button class="button is-small is-danger" :id="user.id" @click="deleteUser">
            <span class="icon">
              <i class="fas fa-trash-alt"></i>
            </span>
          </button>
        </td>
      </tr>
    </tbody>
  </table>
</template>
<script>
export default {
  props: ["list"],
  data() {
    return {
      llist: this.list.users,
      vapoints: this.list.vapoints,
      errors: [],
      vap: []
    };
  },
  methods: {
    chUsername: function(event) {
      let id = event.currentTarget.id;
      let username = event.currentTarget.value;
      axios
        .post("admin/changeUsername", { id: id, username: username })
        .then(response => {
          this.llist = response.data;
          console.log(response);
        })
        .catch(error => {
          this.errors = error.response.data.errors;
          console.log(error.response.data.errors);
        });
    },
    chNume: function(event) {
      let id = event.currentTarget.id;
      let name = event.currentTarget.value;
      axios
        .post("admin/changeName", { id: id, name: name })
        .then(response => {
          this.llist = response.data;
          console.log(response);
        })
        .catch(error => {
          this.errors = error.response.data.errors;
          console.log(error.response.data.errors);
        });
    },
    deleteUser: function(event) {
      let id = event.currentTarget.id;

      axios
        .post("admin/deleteUser", { id: id })
        .then(response => {
          this.llist = response.data;
        })
        .catch(error => {
          this.errors = error.response.data.errors;
          console.log(error.response.data.errors);
        });
    },
    checked: function(event) {
      let id = event.currentTarget.id;
      let value = event.currentTarget.checked;
      let name = event.currentTarget.name;
      console.log(this.vapoints);

      axios
        .post("admin/checked", { id: id, value: value, name: name })
        .then(response => {
          this.llist = response.data;
        })
        .catch(error => {
          this.errors = error.response.data.errors;
          console.log(error.response.data.errors);
        });
    },
    selectVap: function(event) {
      let id = event.currentTarget.id;
      let v = event.currentTarget.value;

      axios
        .post("admin/selectVap", { id: id, vapoint: v })
        .then(response => {})
        .catch(error => {
          this.errors = error.response.data.errors;
          console.log(error.response.data.errors);
        });
    }
  }
};
</script>
