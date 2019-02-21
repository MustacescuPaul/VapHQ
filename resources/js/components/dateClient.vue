<template>
    <div>
        <div class="field">
            <label class="label">Nume Client</label>
            <div class="control">
                <input class="input" type="text" v-model="nume" placeholder="Nume Complet">
                  <p class="help is-danger">{{errors.nume}}</p>
            </div>
        </div>
        <div class="field">
            <label class="label">Adresa Client</label>
            <div class="control">
                <input class="input" type="text" v-model="adresa" placeholder="Adresa Completa">
                <p class="help is-danger">{{errors.adresa}}</p>
            </div>
        </div>
        <div class="field">
            <label class="label">Telefon Client</label>
            <div class="control">
                <input class="input" type="tel" v-model="telefon" placeholder="Telefon">
                <p class="help is-danger">{{errors.telefon}}</p>
            </div>
        </div>
        <div class="field">
            <label class="label">Email Client</label>
            <div class="control">
                <input class="input" type="email" v-model="email" placeholder="exemplu@exemplu.ex">
                <p class="help is-danger">{{errors.email}}</p>
            </div>
        </div>
        <div class="field is-grouped">
            <div class="control">
                <button class="button is-link" @click="send()">Submit</button>
            </div>
        </div>
    </div>
</template>
<script>
export default {

    name: 'dateClient',

    data() {
        return {
            errors: [],
            nume: '',
            adresa: '',
            telefon: '',
            email: '',

        }
    },
    methods: {
        send: function(event) {
            axios.post('/casa/incasare', { cugarantie: 1, nume: this.nume, adresa: this.adresa, telefon: this.telefon, email: this.email }).then(response => {

                this.$emit('Primit');
            }).catch(error => {

                this.errors = error.response.data.errors;

            });

        },
    }
}

</script>
<style lang="css" scoped>
</style>
