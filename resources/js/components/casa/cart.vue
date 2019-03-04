<template>
  <div style="margin-left: 18%;width: 70%; overflow-x: visible;position:fixed;">
    <table class="table is-fullwidth">
      <thead>
        <tr>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="product in cart">
          <td>
            <figure class="image is-128x128">
              <img :src="'https://vapez.ro/' +product.img + '-home_default/poza.jpg'">
            </figure>
          </td>
          <td
            style="justify-content: center;align-items: center;text-align: center;"
          >{{product.nume}}</td>
          <td
            style="justify-content: center;align-items: center;text-align: center;"
          >{{product.pret}} lei</td>
          <td>
            <div
              class="field is-grouped"
              style="justify-content: center;align-items: center;text-align: center;"
            >
              <p class="control">
                <a class="button is-primary" :id_prod="product.id_prod" @click="increaseQ">+</a>
              </p>
              <div style="width:50px;margin-right:12px;">
                <input type="text" class="input" :value="product.cantitate">
              </div>
              <a class="button is-danger" :id_prod="product.id_prod" @click="decreaseQ">-</a>
            </div>
          </td>
          <td>
            <input
              type="text"
              class="input"
              required
              :id_prod="product.id_prod"
              v-model="product.sn"
              @blur="serial"
              v-if="product.sn != 0"
            >
            <p class="help is-danger">{{errors.serial}}</p>
          </td>
        </tr>
      </tbody>
    </table>
    <button
      class="button is-success is-fullwidth"
      :disabled="bOk == 0"
      @click="bon"
    >Bon {{total - val_reducere}}</button>
    <input
      type="number"
      class="input"
      :max="r"
      style="width: 20%;margin-top: 1%;"
      placeholder="Reducere"
      v-on:keyup.enter="reducere"
    >
    <button
      class="button"
      style="width: 20%;margin-top: 1%;"
      id_tag="080047299BFD"
      @click="reducereTag"
    >Reducere TAG</button>
    <nav class="level" style="margin-top: 5%;">
      <div v-if="reduceri['reducere_tag']" class="level-item has-text-centered">
        <div>
          <p class="heading">Reducere TAG</p>
          <p class="title">{{reduceri['reducere_tag']}}</p>
        </div>
      </div>
      <div v-if="reduceri['reducere_magazin']" class="level-item has-text-centered">
        <div>
          <p class="heading">Reducere casa</p>
          <p class="title">{{reduceri['reducere_magazin']}}</p>
        </div>
      </div>
      <div v-if="reduceri['reducere_discount']" class="level-item has-text-centered">
        <div>
          <p class="heading">Reducere rotunjire</p>
          <p class="title">{{reduceri['reducere_discount']}}</p>
        </div>
      </div>
      <div v-if="reduceri['reducere_adaos']" class="level-item has-text-centered">
        <div>
          <p class="heading">Adaos rotunjire</p>
          <p class="title">{{reduceri['reducere_adaos']}}</p>
        </div>
      </div>
    </nav>
  </div>
</template>
<script>
export default {
  props: ["cart"],
  data: function() {
    return {
      bOk: 1,
      val_reducere: 0,
      total: "",
      r: 10,
      errors: [],
      reduceri: []
    };
  },
  methods: {
    reducereTag: function(event) {
      let id_tag = event.target.getAttribute("id_tag");

      axios.post("casa/incasare", { id_tag: id_tag }).then(response => {
        this.aplicare_reduceri();
      });
    },
    reducere: function(event) {
      let value = event.target.value;

      if (value <= this.total) {
        axios.post("casa/incasare", { reducere: value }).then(response => {
          this.aplicare_reduceri();
        });
      }
    },
    serial: function(event) {
      let id_prod = event.target.getAttribute("id_prod");
      let serial = event.target.value;

      axios
        .post("casa/savesn", { id_prod: id_prod, serial: serial })
        .then(response => {})
        .catch(error => {
          this.errors = error.response.data.errors;
        });
    },
    increaseQ: function(event) {
      let id_prod = event.target.getAttribute("id_prod");
      axios.get("casa/increase_q/" + id_prod).then(response => {
        for (let item of this.cart) {
          if (item["id_prod"] == id_prod) item["cantitate"] = response.data;
        }
        this.aplicare_reduceri();
      });
    },
    decreaseQ: function(event) {
      let id_prod = event.target.getAttribute("id_prod");

      axios.get("casa/decrease_q/" + id_prod).then(response => {
        let i = 0;
        if (response.data == 0)
          for (let item of this.cart) {
            if (item["id_prod"] == id_prod) this.cart.splice(i, 1);
            i++;
          }
        for (let item of this.cart) {
          if (item["id_prod"] == id_prod) item["cantitate"] = response.data;
        }
        this.aplicare_reduceri();
      });
    },
    bon: function(event) {
      let temp = 1;
      for (let item of this.cart) {
        if (item["sn"] > 0) {
          temp = 0;
        }
      }
      if (temp == 1) this.$emit("showChanged", "cash-card");
      else this.$emit("showChanged", "date-client");
    },
    aplicare_reduceri: function() {
      axios.post("casa/aplicare_reduceri", {}).then(response => {
        if (response.data.reducere_magazin)
          this.reduceri["reducere_magazin"] = Number(
            response.data.reducere_magazin
          ).toFixed(2);
        if (response.data.reducere_tag)
          this.reduceri["reducere_tag"] = Number(
            response.data.reducere_tag
          ).toFixed(2);
        if (response.data.reducere_adaos) {
          this.reduceri["reducere_adaos"] = Number(
            response.data.reducere_adaos
          ).toFixed(2);
          this.reduceri["reducere_discount"] = null;
        }
        if (response.data.reducere_discount) {
          this.reduceri["reducere_discount"] = Number(
            response.data.reducere_discount
          ).toFixed(2);
          this.reduceri["reducere_adaos"] = null;
        }
        this.total = Number(response.data.total).toFixed(1);
      });
    }
  },
  updated: function(event) {
    let temp = 1;

    if (this.cart.length > 0) {
      for (let item of this.cart) {
        if (item["sn"] == 1) {
          temp = 0;
        }
      }
      this.bOk = temp;
    } else {
      this.bOk = 0;
      this.$set(this.reduceri, "reducere_adaos", 0);
      this.$set(this.reduceri, "reducere_tag", 0);
      this.$set(this.reduceri, "reducere_magazin", 0);
      this.$set(this.reduceri, "reducere_discount", 0);
    }
  },
  mounted: function() {
    this.aplicare_reduceri();
  }
};
</script>
